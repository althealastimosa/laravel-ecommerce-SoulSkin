@extends('layouts.app')

@section('title', 'Order Details - SoulSkin')

@section('content')
<div class="order-details-page-container">
    <div class="row g-4">
        <div class="col-lg-9">
            <div class="card mb-4">
                <div class="card-header">
                    <h3>Order Details - {{ $order->order_number }}</h3>
                </div>
                <div class="card-body">
                    <table class="table order-details-table-horizontal">
                        <thead>
                            <tr>
                                <th class="order-details-th-product">Product</th>
                                <th class="order-details-th-qty">Quantity</th>
                                <th class="order-details-th-price">Price</th>
                                <th class="order-details-th-subtotal">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->orderItems as $item)
                                <tr>
                                    <td class="order-details-td-product">
                                        <div class="d-flex align-items-center">
                                            @if($item->product->hasImage() && $item->product->image_url)
                                                <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="order-details-img">
                                            @else
                                                <div class="order-details-img-placeholder">
                                                    <i class="bi bi-image"></i>
                                                </div>
                                            @endif
                                            <span class="order-details-product-text">{{ $item->product->name }}</span>
                                        </div>
                                    </td>
                                    <td class="order-details-td-qty">{{ $item->quantity }}</td>
                                    <td class="order-details-td-price">₱{{ number_format($item->price, 2) }}</td>
                                    <td class="order-details-td-subtotal">₱{{ number_format($item->subtotal, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="order-details-total-label-cell">Total:</td>
                                <td class="order-details-total-amount-cell">₱{{ number_format($order->total_amount, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
        <div class="card mb-4">
            <div class="card-header">
                <h5>Order Information</h5>
            </div>
            <div class="card-body">
                <p><strong>Order Number:</strong><br>{{ $order->order_number }}</p>
                <p><strong>Order Date:</strong><br>{{ $order->created_at->format('F d, Y h:i A') }}</p>
                <p><strong>Status:</strong><br>
                    <span class="badge 
                        @if($order->status == 'pending') bg-warning
                        @elseif($order->status == 'processing') bg-info
                        @elseif($order->status == 'shipped') bg-primary
                        @elseif($order->status == 'delivered') bg-success
                        @else bg-danger
                        @endif">
                        {{ ucfirst($order->status) }}
                    </span>
                </p>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5>Shipping Information</h5>
            </div>
            <div class="card-body">
                <p><strong>Address:</strong><br>{{ $order->shipping_address }}</p>
                @if($order->phone)
                    <p><strong>Phone:</strong><br>{{ $order->phone }}</p>
                @endif
                @if($order->notes)
                    <p><strong>Notes:</strong><br>{{ $order->notes }}</p>
                @endif
            </div>
        </div>
        </div>
    </div>
</div>

<div class="mt-3 order-details-page-container">
    <a href="{{ route('orders.index') }}" class="btn btn-secondary">Back to Orders</a>
</div>
@endsection

