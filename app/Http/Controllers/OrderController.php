<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * Display a listing of orders
     */
    public function index()
    {
        $customerId = session('customer_id');
        $orders = Order::where('customer_id', $customerId)
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    /**
     * Show order details
     */
    public function show(Order $order)
    {
        $customerId = session('customer_id');

        if ($order->customer_id != $customerId && !session('is_admin')) {
            abort(403, 'Unauthorized access.');
        }

        $order->load('orderItems.product');

        return view('orders.show', compact('order'));
    }

    /**
     * Create a new order from cart
     */
    public function store(Request $request)
    {
        $customerId = session('customer_id');

        if (!$customerId) {
            return redirect()->route('login')
                ->with('error', 'Please login to place an order.');
        }

        $cartItems = Cart::where('customer_id', $customerId)
            ->with('product')
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty.');
        }

        $validated = $request->validate([
            'shipping_address' => 'required|string|max:500',
            'phone' => 'nullable|string|max:20',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Calculate total
        $totalAmount = $cartItems->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });

        // Create order
        $order = Order::create([
            'customer_id' => $customerId,
            'order_number' => 'ORD-' . strtoupper(Str::random(10)),
            'total_amount' => $totalAmount,
            'status' => 'pending',
            'shipping_address' => $validated['shipping_address'],
            'phone' => $validated['phone'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ]);

        // Create order items
        foreach ($cartItems as $cartItem) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $cartItem->product_id,
                'quantity' => $cartItem->quantity,
                'price' => $cartItem->product->price,
                'subtotal' => $cartItem->quantity * $cartItem->product->price,
            ]);

            // Update product stock
            $cartItem->product->decrement('stock', $cartItem->quantity);
        }

        // Clear cart
        Cart::where('customer_id', $customerId)->delete();

        return redirect()->route('orders.show', $order)
            ->with('success', 'Order placed successfully!');
    }

    /**
     * Show checkout form
     */
    public function checkout()
    {
        $customerId = session('customer_id');
        $cartItems = Cart::where('customer_id', $customerId)
            ->with('product')
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty.');
        }

        $total = $cartItems->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });

        return view('orders.checkout', compact('cartItems', 'total'));
    }
}
