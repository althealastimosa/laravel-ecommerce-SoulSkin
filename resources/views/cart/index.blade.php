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
            <div class="col-lg-9">
                <div class="card">
                    <div class="card-body p-4">
                        <div class="cart-items">
                            @foreach($cartItems as $item)
                                <div class="cart-item-row">
                                    <div class="cart-item-content">
                                        <div class="cart-item-delete-btn-wrapper">
                                            <form action="{{ route('cart.remove', $item) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-delete-cart-item" title="Remove item" onclick="return confirm('Are you sure you want to remove this item?');">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            </form>
                                        </div>
                                        <div class="cart-item-image-section">
                                            @if($item->product->hasImage() && $item->product->image_url)
                                                <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="cart-item-image">
                                            @else
                                                <div class="cart-item-image-placeholder">
                                                    <i class="bi bi-image"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="cart-item-info-section">
                                            <h6 class="cart-product-name">{{ $item->product->name }}</h6>
                                            <p class="cart-product-description">{{ Str::limit($item->product->description, 100) }}</p>
                                            <div class="cart-item-unit-price">
                                                <span class="unit-price-label">Price:</span>
                                                <strong class="unit-price-value">₱{{ number_format($item->product->price, 2) }}</strong>
                                            </div>
                                        </div>
                                        <div class="cart-item-quantity-section">
                                            <form action="{{ route('cart.update', $item) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <label class="quantity-label">Quantity</label>
                                                <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock }}" class="cart-quantity-input" onchange="this.form.submit()">
                                            </form>
                                        </div>
                                        <div class="cart-item-total-section">
                                            <div class="cart-item-total">
                                                <strong class="total-price">₱{{ number_format($item->quantity * $item->product->price, 2) }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
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

