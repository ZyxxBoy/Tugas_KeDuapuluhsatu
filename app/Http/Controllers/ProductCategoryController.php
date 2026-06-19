<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = ProductCategory::withCount('products')->get();
        return view('dashboard.kategori.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Not used, as we use modal in index
        return redirect()->route('dashboard.kategori.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:product_categories,name',
        ]);

        $slug = strtolower(str_replace(' ', '-', $request->name));
        
        //check if slug already exists
        if (ProductCategory::where('slug', $slug)->exists()) {
           return redirect()->route('dashboard.kategori.index')->withErrors(['name' => 'Kategori sudah ada.'])->withInput();
        }

        //simpan category baru
        $category = ProductCategory::create([
            'name' => $request->name,
            'slug' => $slug,
        ]);

        //cek apakah berhasil disimpan

        return redirect()->route('dashboard.kategori.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductCategory $kategori)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductCategory $kategori)
    {
        // Not used, as we use modal in index
        return redirect()->route('dashboard.kategori.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $kategori = ProductCategory::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:product_categories,name,' . $kategori->id,
        ]);

        $kategori->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()->route('dashboard.kategori.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kategori = ProductCategory::findOrFail($id);
        
        // Prevent deletion if category has products (optional but good practice)
        if ($kategori->products()->count() > 0) {
            return redirect()->route('dashboard.kategori.index')->with('error', 'Kategori tidak dapat dihapus karena masih memiliki produk.');
        }

        $kategori->delete();

        return redirect()->route('dashboard.kategori.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
