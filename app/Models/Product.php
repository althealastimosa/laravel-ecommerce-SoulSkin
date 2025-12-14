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

    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return null;
        }

        $imagePath = $this->image;
        if (!str_starts_with($imagePath, '/storage/')) {
            $imagePath = '/storage/' . ltrim($imagePath, '/');
        }

        $storagePath = str_replace('/storage/', '', $imagePath);
        $storagePath = ltrim($storagePath, '/');
        
        if (Storage::disk('public')->exists($storagePath)) {
            return asset($imagePath);
        }

        $publicPath = public_path(ltrim($imagePath, '/'));
        if (file_exists($publicPath)) {
            return asset($imagePath);
        }

        return null;
    }

    public function hasImage()
    {
        if (!$this->image) {
            return false;
        }

        $storagePath = str_replace('/storage/', '', $this->image);
        if (Storage::disk('public')->exists($storagePath)) {
            return true;
        }

        $publicPath = public_path(ltrim($this->image, '/'));
        return file_exists($publicPath);
    }
}
