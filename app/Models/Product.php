<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'shop_id',
        'name',
        'slug',
        'description',
        'short_description',
        'long_description',
        'price',
        'discount_price',
        'stock',
        'image',
        'gallery_images',
        'material',
        'volume',
        'color',
        'product_code',
        'weight',
        'sizes',
        'tags',
        'is_new',
        'is_promo',
        'is_best_seller',
    ];

    protected $casts = [
        'gallery_images' => 'array',
        'sizes' => 'array',
        'tags' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
