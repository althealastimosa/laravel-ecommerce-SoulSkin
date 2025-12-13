@extends('layouts.app')

@section('title', $product->name . ' - SoulSkin')

@section('content')
<div class="row">
    <div class="col-md-6">
        @if($product->image)
            <img src="{{ $product->image }}" class="img-fluid rounded" alt="{{ $product->name }}">
        @else
            <div class="bg-secondary d-flex align-items-center justify-content-center rounded" style="height: 400px;">
                <i class="bi bi-image text-white" style="font-size: 5rem;"></i>
            </div>
        @endif
    </div>
    <div class="col-md-6">
        <h1>{{ $product->name }}</h1>
        <p class="text-muted">{{ $product->description }}</p>
        <h3 class="text-primary mb-3">${{ number_format($product->price, 2) }}</h3>
        <p><strong>Stock:</strong> {{ $product->stock }} units</p>
        
        @if(session('customer_id'))
            @if($product->stock > 0)
                <form action="{{ route('cart.add', $product) }}" method="POST" class="mb-3">
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
            <div class="mt-4">
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

