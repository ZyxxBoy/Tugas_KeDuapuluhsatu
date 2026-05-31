<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_category_id', 
        'name', 
        'description', 
        'image', 
        'price', 
        'stock', 
        'slug'
    ];

    /**
     * Hubungan ke model ProductCategory (Belongs To)
     * Produk ini dimiliki oleh sebuah kategori.
     */
    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    /**
     * Hubungan ke model OrderItem (Has Many)
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Hubungan ke model CartItem (Has Many)
     */
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
}