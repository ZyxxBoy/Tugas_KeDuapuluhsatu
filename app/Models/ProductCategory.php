<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    /**
     * Hubungan ke model Product (One to Many)
     * Satu kategori memiliki banyak produk.
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'product_category_id');
    }
}