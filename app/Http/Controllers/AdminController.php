<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class AdminController extends Controller
{
    public function index()
    {
    
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalCustomers = Customer::count();
        
        $recentOrders = Order::with('customer')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalOrders',
            'totalCustomers',
            'recentOrders'
        ));
    }

    public function settings()
    {
        return view('admin.settings');
    }

    public function customers()
    {
        $customers = Customer::latest()->paginate(15);
        return view('admin.customers', compact('customers'));
    }

    public function orders()
    {
        $orders = Order::with('customer')->latest()->paginate(15);
        return view('admin.orders', compact('orders'));
    }

    /**
     * Accept an order (change status to processing)
     */
    public function acceptOrder(Order $order)
    {
        if ($order->status !== 'pending') {
            return back()->with('error', 'Only pending orders can be accepted.');
        }

        try {
            // Debug logging: record current status
            Log::info('Admin acceptOrder called', ['order_id' => $order->id, 'before_status' => $order->status]);

            // Mark as shipped when admin accepts (change as needed)
            $order->status = 'shipped';
            $order->save();
            $order->refresh();

            Log::info('Order status updated', ['order_id' => $order->id, 'after_status' => $order->status]);
        } catch (\Exception $e) {
            Log::error('Failed to accept order', ['order_id' => $order->id, 'error' => $e->getMessage()]);
            return redirect()->route('admin.orders')->with('error', 'Failed to accept order: ' . $e->getMessage());
        }

        return redirect()->route('admin.orders')->with(['success' => 'Order ' . $order->order_number . ' has been accepted.', 'order_status' => $order->status]);
    }

    /**
     * Decline an order (change status to cancelled and restore stock)
     */
    public function declineOrder(Order $order)
    {
        if ($order->status !== 'pending') {
            return back()->with('error', 'Only pending orders can be declined.');
        }

        // Load order items with products
        $order->load('orderItems.product');

        // Restore product stock
        foreach ($order->orderItems as $orderItem) {
            if ($orderItem->product) {
                $orderItem->product->increment('stock', $orderItem->quantity);
            }
        }

        try {
            $order->status = 'cancelled';
            $order->save();
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to decline order: ' . $e->getMessage());
        }

        return back()->with('success', 'Order ' . $order->order_number . ' has been declined and stock has been restored.');
    }

    /**
     * Debug endpoint: accept order and return before/after status and DB column info as JSON
     * Intended for temporary debugging only.
     */
    public function acceptOrderDebug(Order $order)
    {
        $before = $order->status;

        try {
            Log::info('acceptOrderDebug called', ['order_id' => $order->id, 'before' => $before]);

            $order->status = 'shipped';
            $order->save();
            $order->refresh();

            $after = $order->status;

            // Get column definition for status
            $column = DB::select("SHOW COLUMNS FROM `orders` LIKE 'status'");

            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'before_status' => $before,
                'after_status' => $after,
                'column_info' => $column,
                'order_row' => $order->toArray(),
            ]);
        } catch (\Exception $e) {
            Log::error('acceptOrderDebug failed', ['order_id' => $order->id, 'error' => $e->getMessage()]);
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Repair product images (run from admin only).
     */
    public function repairProductImages()
    {
        $dir = public_path('storage/products');
        $files = [];
        if (File::exists($dir)) {
            foreach (File::files($dir) as $f) {
                $files[] = $f->getFilename();
            }
        }

        $products = \App\Models\Product::all();
        $updated = 0;
        $changes = [];

        foreach ($products as $product) {
            if (! $product->image) continue;

            $basename = basename($product->image);
            $expectedPath = '/storage/products/' . $basename;

            if (in_array($basename, $files)) {
                if ($product->image !== $expectedPath) {
                    $old = $product->image;
                    $product->image = $expectedPath;
                    $product->save();
                    $changes[] = [ 'id' => $product->id, 'old' => $old, 'new' => $expectedPath ];
                    $updated++;
                }
                continue;
            }

            if (strpos($basename, '_') !== false) {
                $suffix = substr($basename, strpos($basename, '_'));
                $matches = array_filter($files, function ($f) use ($suffix) {
                    return str_ends_with($f, $suffix);
                });

                if (count($matches) === 1) {
                    $newBasename = array_values($matches)[0];
                    $newPath = '/storage/products/' . $newBasename;
                    $old = $product->image;
                    $product->image = $newPath;
                    $product->save();
                    $changes[] = [ 'id' => $product->id, 'old' => $old, 'new' => $newPath ];
                    $updated++;
                    continue;
                }
            }
        }

        return response()->json([
            'updated' => $updated,
            'changes' => $changes,
            'files_count' => count($files),
        ]);
    }
}
