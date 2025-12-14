<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$products = \App\Models\Product::all();

foreach ($products as $product) {
    echo "Product: {$product->name}\n";
    echo "  DB Image Path: " . ($product->image ?? 'NULL') . "\n";
    
    // Force refresh the accessor by unsetting the attribute
    $product->unsetRelation('image_url');
    $imageUrl = $product->image_url;
    echo "  Image URL: " . ($imageUrl ?? 'NULL') . "\n";
    echo "  Has Image: " . ($product->hasImage() ? 'Yes' : 'No') . "\n";
    
    if ($product->image) {
        $storagePath = str_replace('/storage/', '', $product->image);
        $storagePath = ltrim($storagePath, '/');
        $exists = \Illuminate\Support\Facades\Storage::disk('public')->exists($storagePath);
        echo "  Storage exists: " . ($exists ? 'Yes' : 'No') . " (path: $storagePath)\n";
        
        $publicPath = public_path('storage/' . $storagePath);
        echo "  Public path exists: " . (file_exists($publicPath) ? 'Yes' : 'No') . "\n";
        echo "  Public path: $publicPath\n";
        
        // Test asset() helper
        $assetUrl = asset($product->image);
        echo "  Asset URL: $assetUrl\n";
    }
    echo "\n";
}
