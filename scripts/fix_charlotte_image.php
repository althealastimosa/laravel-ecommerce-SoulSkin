<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;

$product = Product::where('name', 'LIKE', '%Charlotte%')->first();

if ($product) {
    echo "Found product: {$product->name}\n";
    echo "Current image path: " . ($product->image ?? 'NULL') . "\n";
    
    // The actual file that exists
    $correctPath = '/storage/products/1765649800_charlotte.jpg';
    $publicPath = public_path(ltrim($correctPath, '/'));
    
    if (file_exists($publicPath)) {
        $product->image = $correctPath;
        $product->save();
        echo "Updated image path to: {$correctPath}\n";
        echo "File exists: YES\n";
    } else {
        echo "Error: File does not exist at {$publicPath}\n";
    }
} else {
    echo "Product not found\n";
}
