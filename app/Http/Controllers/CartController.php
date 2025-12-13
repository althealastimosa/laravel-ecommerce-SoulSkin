<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display the cart
     */
    public function index()
    {
        $customerId = session('customer_id');
        $cartItems = Cart::where('customer_id', $customerId)
            ->with('product')
            ->get();
        
        $total = $cartItems->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });

        return view('cart.index', compact('cartItems', 'total'));
    }

    /**
     * Add product to cart
     */
    public function add(Request $request, Product $product)
    {
        $customerId = session('customer_id');

        if (!$customerId) {
            return redirect()->route('login')
                ->with('error', 'Please login to add items to cart.');
        }

        // Check if product is in stock
        if ($product->stock <= 0) {
            return redirect()->back()
                ->with('error', 'This product is out of stock.');
        }

        $validated = $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $product->stock,
        ]);

        $quantity = $validated['quantity'];

        // Check if item already in cart
        $cartItem = Cart::where('customer_id', $customerId)
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $quantity;
            // Ensure total quantity doesn't exceed stock
            if ($newQuantity > $product->stock) {
                return redirect()->back()
                    ->with('error', 'Cannot add more items. Available stock: ' . $product->stock);
            }
            $cartItem->quantity = $newQuantity;
            $cartItem->save();
        } else {
            Cart::create([
                'customer_id' => $customerId,
                'product_id' => $product->id,
                'quantity' => $quantity,
            ]);
        }

        return redirect()->back()
            ->with('success', 'Product added to cart successfully.');
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request, Cart $cart)
    {
        $customerId = session('customer_id');

        if ($cart->customer_id != $customerId) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart->update([
            'quantity' => $request->quantity,
        ]);

        return redirect()->route('cart.index')
            ->with('success', 'Cart updated successfully.');
    }

    /**
     * Remove item from cart
     */
    public function remove(Cart $cart)
    {
        $customerId = session('customer_id');

        if ($cart->customer_id != $customerId) {
            abort(403, 'Unauthorized action.');
        }

        $cart->delete();

        return redirect()->route('cart.index')
            ->with('success', 'Item removed from cart.');
    }

    /**
     * Clear entire cart
     */
    public function clear()
    {
        $customerId = session('customer_id');
        Cart::where('customer_id', $customerId)->delete();

        return redirect()->route('cart.index')
            ->with('success', 'Cart cleared successfully.');
    }
}
