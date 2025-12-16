@extends('layouts.app')

@section('title', 'Checkout - SoulSkin')

@section('content')
<div class="checkout-page-container">
    <h1 class="mb-5 checkout-title">Checkout</h1>

    <div class="row g-4">
        <div class="col-lg-9">
        <div class="card mb-4">
            <div class="card-header">
                <h5>Order Summary</h5>
            </div>
            <div class="card-body">
                <table class="table checkout-table-horizontal">
                    <thead>
                        <tr>
                            <th class="checkout-th-product">Product</th>
                            <th class="checkout-th-qty">Quantity</th>
                            <th class="checkout-th-price">Price</th>
                            <th class="checkout-th-subtotal">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cartItems as $item)
                            <tr>
                                <td class="checkout-td-product">
                                    <div class="d-flex align-items-center">
                                        @if($item->product->hasImage() && $item->product->image_url)
                                            <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="checkout-img">
                                        @else
                                            <div class="checkout-img-placeholder">
                                                <i class="bi bi-image"></i>
                                            </div>
                                        @endif
                                        <span class="checkout-product-text">{{ $item->product->name }}</span>
                                    </div>
                                </td>
                                <td class="checkout-td-qty">{{ $item->quantity }}</td>
                                <td class="checkout-td-price">₱{{ number_format($item->product->price, 2) }}</td>
                                <td class="checkout-td-subtotal">₱{{ number_format($item->quantity * $item->product->price, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="checkout-total-label-cell">Total:</td>
                            <td class="checkout-total-amount-cell">₱{{ number_format($total, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5>Shipping Information</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('orders.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="shipping_address" class="form-label">Shipping Address *</label>
                        <textarea class="form-control @error('shipping_address') is-invalid @enderror" id="shipping_address" name="shipping_address" rows="3" required>{{ old('shipping_address') }}</textarea>
                        @error('shipping_address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Order Notes</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-check-circle"></i> Place Order
                        </button>
                        <a href="{{ route('cart.index') }}" class="btn btn-secondary btn-lg">Back to Cart</a>
                    </div>
                </form>
            </div>
        </div>
        </div>
        <div class="col-lg-3">
            <div class="card checkout-total-card">
                <div class="card-header">
                    <h5>Order Total</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <span class="checkout-summary-label">Subtotal:</span>
                        <strong class="checkout-summary-value">₱{{ number_format($total, 2) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="checkout-summary-label">Shipping:</span>
                        <strong class="checkout-summary-value text-success">Free</strong>
                    </div>
                    <hr class="my-3">
                    <div class="d-flex justify-content-between">
                        <span class="checkout-total-label-text"><strong>Total:</strong></span>
                        <strong class="checkout-total-value">₱{{ number_format($total, 2) }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

