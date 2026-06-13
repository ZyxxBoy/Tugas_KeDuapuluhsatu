<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $products = \App\Models\Product::with('category')->latest()->take(4)->get();
        return view('home', compact('products'));
    }

    public function showPublic(string $slug)
    {
        $product = \App\Models\Product::with('category')->where('slug', $slug)->firstOrFail();
        $related_products = \App\Models\Product::where('product_category_id', $product->product_category_id)
            ->where('slug', '!=', $slug)
            ->take(4)
            ->get();
            
        return view('produk-detail', compact('product', 'related_products'));
    }

    public function produk(Request $request)
    {
        $query = \App\Models\Product::with('category');

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->sort == 'termurah') {
            $query->orderBy('price', 'asc');
        } elseif ($request->sort == 'termahal') {
            $query->orderBy('price', 'desc');
        } else {
            $query->latest();
        }

        $products = $query->paginate(6)->withQueryString();
        $categories = \App\Models\ProductCategory::all();

        return view('produk', compact('products', 'categories'));
    }
}
