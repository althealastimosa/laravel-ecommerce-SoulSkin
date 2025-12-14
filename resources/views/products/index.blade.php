@extends('layouts.app')

@section('title', 'Products - SoulSkin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Products</h1>
    @if(session('is_admin'))
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add Product
        </a>
    @endif
</div>

<div class="row">
    @forelse($products as $product)
        <div class="col-md-4 mb-4">
            <div class="card h-100 product-card">
                @if($product->hasImage() && $product->image_url)
                    <img src="{{ $product->image_url }}" class="card-img-top product-card-image" alt="{{ $product->name }}">
                @else
                    <div class="card-img-top product-card-image-placeholder d-flex align-items-center justify-content-center">
                        <i class="bi bi-image text-secondary" style="font-size: 3rem;"></i>
                    </div>
                @endif
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title product-card-title">{{ $product->name }}</h5>
                    <p class="card-text product-card-text">{{ Str::limit($product->description, 100) }}</p>
                    <div class="mt-auto">
                        <p class="card-text mb-3">
                            <span class="product-card-price">₱{{ number_format($product->price, 2) }}</span>
                            <span class="product-card-stock ms-2">Stock: {{ $product->stock }}</span>
                        </p>
                        <div class="d-flex gap-2">
                            <a href="{{ route('products.show', $product) }}" class="btn btn-outline-primary btn-sm flex-fill">
                                View Details
                            </a>
                            @if(session('customer_id') && $product->stock > 0)
                                <form action="{{ route('cart.add', $product) }}" method="POST" class="flex-fill">
                                    @csrf
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="btn btn-primary btn-sm w-100">
                                        <i class="bi bi-cart-plus"></i> Add to Cart
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info text-center">
                <h4>No products available</h4>
                <p>Check back later for new products.</p>
            </div>
        </div>
    @endforelse
</div>

<div class="d-flex justify-content-center">
    {{ $products->links() }}
</div>
@endsection

