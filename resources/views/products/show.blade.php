@extends('layouts.app')

@section('title', $product->name . ' - SoulSkin')

@section('content')
<div class="row">
    <div class="col-md-6 mb-4">
        @if($product->hasImage() && $product->image_url)
            <div class="product-image-container">
                <img src="{{ $product->image_url }}" class="img-fluid" alt="{{ $product->name }}">
            </div>
        @else
            <div class="product-image-placeholder d-flex align-items-center justify-content-center">
                <div class="text-center">
                    <i class="bi bi-image text-secondary" style="font-size: 5rem;"></i>
                    <p class="text-muted mt-2">No image available</p>
                </div>
            </div>
        @endif
    </div>
    <div class="col-md-6">
        <h1 class="product-title">{{ $product->name }}</h1>
        <p class="product-description">{{ $product->description }}</p>
        <h3 class="product-price">₱{{ number_format($product->price, 2) }}</h3>
        <p class="product-stock"><strong>Stock:</strong> {{ $product->stock }} units</p>
        
        @if(session('customer_id'))
            @if($product->stock > 0)
                <form action="{{ route('cart.add', $product) }}" method="POST" class="mb-4">
                    @csrf
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" name="quantity" id="quantity" class="form-control" value="1" min="1" max="{{ $product->stock }}" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="bi bi-cart-plus"></i> Add to Cart
                    </button>
                </form>
            @else
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle"></i> Out of Stock
                </div>
            @endif
        @else
            <div class="alert alert-info">
                <a href="{{ route('login') }}">Login</a> to add items to cart.
            </div>
        @endif

        @if(session('is_admin'))
            <div class="product-actions">
                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-warning">
                    <i class="bi bi-pencil"></i> Edit Product
                </a>
                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this product?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash"></i> Delete Product
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>
@endsection

