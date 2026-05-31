<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'order_number', 
        'name', 
        'address', 
        'phone', 
        'total_price', 
        'status'
    ];

    /**
     * Hubungan ke model User (Belongs To)
     * Pesanan ini dibuat oleh seorang User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Hubungan ke model OrderItem (One to Many)
     * Satu pesanan memiliki banyak rincian item produk.
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }
}