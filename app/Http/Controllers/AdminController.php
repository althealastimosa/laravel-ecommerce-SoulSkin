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
     * Accept an order (change status to shipped)
     */
    public function acceptOrder(Request $request, Order $order)
    {
        // Verify the order exists and is pending
        if ($order->status !== 'pending') {
            return redirect()->route('admin.orders')
                ->with('error', 'Only pending orders can be accepted.');
        }

        try {
            // Use DB transaction to ensure data consistency
            DB::beginTransaction();
            
            // Update the order status
            $order->status = 'shipped';
            $saved = $order->save();
            
            if (!$saved) {
                throw new \Exception('Failed to save order status');
            }

            DB::commit();

            // Refresh to get latest data
            $order->refresh();

            Log::info('Order accepted successfully', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'new_status' => $order->status
            ]);

            return redirect()->route('admin.orders')
                ->with('success', 'Order ' . $order->order_number . ' has been accepted and marked as shipped.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to accept order', [
                'order_id' => $order->id ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('admin.orders')
                ->with('error', 'Failed to accept order: ' . $e->getMessage());
        }
    }

    /**
     * Decline an order (change status to cancelled and restore stock)
     */
    public function declineOrder(Request $request, Order $order)
    {
        // Verify the order exists and is pending
        if ($order->status !== 'pending') {
            return redirect()->route('admin.orders')
                ->with('error', 'Only pending orders can be declined.');
        }

        try {
            // Use DB transaction to ensure data consistency
            DB::beginTransaction();

            // Load order items with products
            $order->load('orderItems.product');

            // Restore product stock
            foreach ($order->orderItems as $orderItem) {
                if ($orderItem->product) {
                    $orderItem->product->increment('stock', $orderItem->quantity);
                }
            }

            // Update order status
            $order->status = 'cancelled';
            $saved = $order->save();
            
            if (!$saved) {
                throw new \Exception('Failed to save order status');
            }

            DB::commit();

            // Refresh to get latest data
            $order->refresh();

            Log::info('Order declined successfully', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'new_status' => $order->status
            ]);

            return redirect()->route('admin.orders')
                ->with('success', 'Order ' . $order->order_number . ' has been declined and stock has been restored.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to decline order', [
                'order_id' => $order->id ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('admin.orders')
                ->with('error', 'Failed to decline order: ' . $e->getMessage());
        }
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
