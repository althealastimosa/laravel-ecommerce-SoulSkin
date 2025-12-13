@extends('layouts.app')

@section('title', 'Home - SoulSkin')

@section('content')
<div class="text-center py-5">
    <h1 class="display-4 text-primary mb-4">Welcome to SoulSkin!</h1>
    <p class="lead mb-4">Hello, {{ session('customer_name') ?? 'Guest' }}</p>
    <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">Browse Products</a>
    
    @if (session('is_admin'))
        <a href="{{ route('admin.dashboard') }}" class="btn btn-success mt-3 me-2">Admin Dashboard</a>
    @endif
</div>
@endsection
