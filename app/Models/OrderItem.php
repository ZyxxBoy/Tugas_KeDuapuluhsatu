<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'product_id', 'price', 'quantity'];

    /**
     * Hubungan ke model Order (Belongs To)
     * Item ini merupakan bagian dari sebuah data Pesanan.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Hubungan ke model Product (Belongs To)
     * Item ini merujuk ke data Produk tertentu.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}