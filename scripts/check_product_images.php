<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;

$products = Product::all();

foreach ($products as $product) {
    echo "Product: {$product->name}\n";
    echo "  Image in DB: " . ($product->image ?? 'NULL') . "\n";
    
    if ($product->image) {
        // Check if file exists using the path from DB
        $publicPath = public_path(ltrim($product->image, '/'));
        $exists = file_exists($publicPath);
        echo "  File exists: " . ($exists ? 'YES' : 'NO') . "\n";
        echo "  Expected path: {$publicPath}\n";
    }
    
    // Check storage/app/public/products
    $storagePath = storage_path('app/public/products');
    if (is_dir($storagePath)) {
        $files = array_diff(scandir($storagePath), ['.', '..']);
        echo "  Files in storage/app/public/products: " . count($files) . "\n";
    }
    
    echo "\n";
}
