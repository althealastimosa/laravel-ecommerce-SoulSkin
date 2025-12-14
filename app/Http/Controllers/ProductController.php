<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
   
    public function index()
    {
        $products = Product::latest()->paginate(12);
        return view('products.index', compact('products'));
    }

   
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            // Store on the public disk
            $path = $image->store('products', 'public');
            // Use the correct path format for asset() helper
            $data['image'] = '/storage/' . $path;
        }

        $product = Product::create($data);

        return redirect()->route('products.show', $product)
            ->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

 
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            // remove existing file if present
            if ($product->image) {
                $existing = public_path(ltrim($product->image, '/'));
                if (file_exists($existing)) {
                    @unlink($existing);
                }
                // Also try to delete from storage if it exists there
                $storagePath = str_replace('/storage/', '', $product->image);
                if (Storage::disk('public')->exists($storagePath)) {
                    Storage::disk('public')->delete($storagePath);
                }
            }

            $image = $request->file('image');
            // Store on the public disk
            $path = $image->store('products', 'public');
            // Use the correct path format for asset() helper
            $data['image'] = '/storage/' . $path;
        }

        $product->update($data);

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

 
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }
}
