@extends('layouts.app')

@section('title', 'Shopping Cart - SoulSkin')

@section('content')
<div class="cart-page">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Shopping Cart</h1>
        @if($cartItems->count() > 0)
            <form action="{{ route('cart.clear') }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure you want to clear your cart?');">
                    <i class="bi bi-trash"></i> Clear Cart
                </button>
            </form>
        @endif
    </div>

    @if($cartItems->count() > 0)
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body p-4">
                        <div class="cart-items">
                            @foreach($cartItems as $item)
                                <div class="cart-item mb-4 pb-4 border-bottom">
                                    <div class="row align-items-center g-3">
                                        <div class="col-12 col-lg-4">
                                            <div class="d-flex align-items-center">
                                                @if($item->product->hasImage() && $item->product->image_url)
                                                    <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="cart-item-image me-3">
                                                @else
                                                    <div class="cart-item-image-placeholder me-3">
                                                        <i class="bi bi-image"></i>
                                                    </div>
                                                @endif
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1 fw-bold">{{ $item->product->name }}</h6>
                                                    <p class="text-muted mb-0 small">{{ Str::limit($item->product->description, 50) }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 col-lg-2 text-lg-center">
                                            <div class="cart-item-price">
                                                <span class="text-muted small d-block d-lg-none mb-1">Price</span>
                                                <strong class="price-value">₱{{ number_format($item->product->price, 2) }}</strong>
                                            </div>
                                        </div>
                                        <div class="col-6 col-lg-2 text-lg-center">
                                            <div class="cart-item-quantity">
                                                <span class="text-muted small d-block d-lg-none mb-2">Quantity</span>
                                                <form action="{{ route('cart.update', $item) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock }}" class="form-control cart-quantity-input" onchange="this.form.submit()">
                                                </form>
                                            </div>
                                        </div>
                                        <div class="col-6 col-lg-3 text-lg-center">
                                            <div class="cart-item-subtotal">
                                                <span class="text-muted small d-block d-lg-none mb-1">Subtotal</span>
                                                <strong class="text-primary price-value">₱{{ number_format($item->quantity * $item->product->price, 2) }}</strong>
                                            </div>
                                        </div>
                                        <div class="col-6 col-lg-1 text-lg-center">
                                            <form action="{{ route('cart.remove', $item) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-link text-danger p-0" title="Remove item">
                                                    <i class="bi bi-x-circle fs-5"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card cart-summary-card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-receipt"></i> Order Summary</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Subtotal:</span>
                            <strong>₱{{ number_format($total, 2) }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Shipping:</span>
                            <strong class="text-success">Free</strong>
                        </div>
                        <hr class="my-3">
                        <div class="d-flex justify-content-between mb-4">
                            <span class="fw-bold fs-5">Total:</span>
                            <strong class="cart-total-price fs-5">₱{{ number_format($total, 2) }}</strong>
                        </div>
                        <a href="{{ route('checkout') }}" class="btn btn-primary w-100 btn-lg">
                            <i class="bi bi-arrow-right-circle"></i> Proceed to Checkout
                        </a>
                        <a href="{{ route('products.index') }}" class="btn btn-outline-primary w-100 mt-2">
                            <i class="bi bi-arrow-left"></i> Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="bi bi-cart-x display-1 text-muted mb-3"></i>
                <h4 class="mb-3">Your cart is empty</h4>
                <p class="text-muted mb-4">Start shopping to add items to your cart.</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
                    <i class="bi bi-bag"></i> Browse Products
                </a>
            </div>
        </div>
    @endif
</div>
@endsection

