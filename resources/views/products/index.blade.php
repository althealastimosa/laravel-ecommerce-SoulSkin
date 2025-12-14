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
            <div class="card h-100">
                @php
                    $imgPath = $product->image;
                    $publicImg = $imgPath ? public_path(ltrim($imgPath, '/')) : null;

                    // If the DB path doesn't exist on disk, try to find a matching file in public/storage/products
                    if (!($imgPath && $publicImg && file_exists($publicImg))) {
                        $candidates = [];
                        $dir = public_path('storage/products');
                        if (is_dir($dir)) {
                            foreach (scandir($dir) as $f) {
                                if (in_array($f, ['.', '..'])) continue;
                                $candidates[] = $f;
                            }
                        }

                        $nameSlug = strtolower(preg_replace('/[^a-z0-9]+/i', '_', $product->name));
                        foreach ($candidates as $c) {
                            if (stripos($c, $nameSlug) !== false || stripos($c, strtolower($product->name)) !== false) {
                                $imgPath = '/storage/products/' . $c;
                                $publicImg = public_path(ltrim($imgPath, '/'));
                                break;
                            }
                        }
                    }
                @endphp

                @if($imgPath && $publicImg && file_exists($publicImg))
                    <img src="{{ asset($imgPath) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                @else
                    <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height: 200px;">
                        <i class="bi bi-image text-white" style="font-size: 3rem;"></i>
                    </div>
                @endif
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ $product->name }}</h5>
                    <p class="card-text text-muted">{{ Str::limit($product->description, 100) }}</p>
                    <div class="mt-auto">
                        <p class="card-text">
                            <strong class="text-primary">₱{{ number_format($product->price, 2) }}</strong>
                            <span class="text-muted ms-2">Stock: {{ $product->stock }}</span>
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

