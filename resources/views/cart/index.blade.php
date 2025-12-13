@extends('layouts.app')

@section('title', 'Shopping Cart - SoulSkin')

@section('content')
<h1 class="mb-4">Shopping Cart</h1>

@if($cartItems->count() > 0)
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cartItems as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($item->product->image)
                                                <img src="{{ $item->product->image }}" alt="{{ $item->product->name }}" class="me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                            @endif
                                            <div>
                                                <strong>{{ $item->product->name }}</strong>
                                            </div>
                                        </div>
                                    </td>
                                    <td>${{ number_format($item->product->price, 2) }}</td>
                                    <td>
                                        <form action="{{ route('cart.update', $item) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock }}" class="form-control form-control-sm" style="width: 80px; display: inline-block;" onchange="this.form.submit()">
                                        </form>
                                    </td>
                                    <td>${{ number_format($item->quantity * $item->product->price, 2) }}</td>
                                    <td>
                                        <form action="{{ route('cart.remove', $item) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <form action="{{ route('cart.clear') }}" method="POST" class="mt-3">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to clear your cart?');">
                            Clear Cart
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Order Summary</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <span>Subtotal:</span>
                        <strong>${{ number_format($total, 2) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Shipping:</span>
                        <strong>Free</strong>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <span><strong>Total:</strong></span>
                        <strong class="text-primary">${{ number_format($total, 2) }}</strong>
                    </div>
                    <a href="{{ route('checkout') }}" class="btn btn-primary w-100">
                        Proceed to Checkout
                    </a>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="alert alert-info text-center">
        <h4>Your cart is empty</h4>
        <p>Start shopping to add items to your cart.</p>
        <a href="{{ route('products.index') }}" class="btn btn-primary">Browse Products</a>
    </div>
@endif
@endsection

