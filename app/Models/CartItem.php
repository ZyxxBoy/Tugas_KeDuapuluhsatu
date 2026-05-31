<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'product_id', 'quantity'];

    /**
     * Hubungan ke model User (Belongs To)
     * Item keranjang ini milik pengguna tertentu.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Hubungan ke model Product (Belongs To)
     * Item keranjang ini berisi produk tertentu.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}