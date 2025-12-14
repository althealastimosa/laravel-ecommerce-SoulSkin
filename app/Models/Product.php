<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'image',
    ];

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the image URL for the product
     */
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return null;
        }

        // Normalize the path - ensure it starts with /storage/
        $imagePath = $this->image;
        if (!str_starts_with($imagePath, '/storage/')) {
            $imagePath = '/storage/' . ltrim($imagePath, '/');
        }

        // Check if file exists in storage
        $storagePath = str_replace('/storage/', '', $imagePath);
        $storagePath = ltrim($storagePath, '/');
        
        if (Storage::disk('public')->exists($storagePath)) {
            // Use asset() helper for relative URLs that work on any domain
            return asset($imagePath);
        }

        // Fallback: check in public path (for symlink)
        $publicPath = public_path(ltrim($imagePath, '/'));
        if (file_exists($publicPath)) {
            return asset($imagePath);
        }

        return null;
    }

    /**
     * Check if image exists
     */
    public function hasImage()
    {
        if (!$this->image) {
            return false;
        }

        // Check in storage
        $storagePath = str_replace('/storage/', '', $this->image);
        if (Storage::disk('public')->exists($storagePath)) {
            return true;
        }

        // Check in public path
        $publicPath = public_path(ltrim($this->image, '/'));
        return file_exists($publicPath);
    }
}
