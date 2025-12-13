<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\Customer;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
    
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalCustomers = Customer::count();
        $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('total_amount');
        
        $recentOrders = Order::with('customer')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalOrders',
            'totalCustomers',
            'totalRevenue',
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
}
