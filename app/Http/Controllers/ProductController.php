<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index() {
        $products = Product::with('category')->latest()->get();
        return view('dashboard.produk.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = ProductCategory::all();
        return view('dashboard.produk.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'product_category_id' => 'required|exists:product_categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $slug = Str::slug($request->name);

        //check if slug already exists
        $count = Product::where('slug', $slug)->count();
        if ($count > 0) {
            return redirect()->route('dashboard.produk.index')->with('error', 'Produk dengan nama tersebut sudah ada.');
        }
        
        //simpan gambar yang di crop
        $croppedImageData = $request->input('cropped_image');
        $croppedImageData = str_replace('data:image/png;base64,', '', $croppedImageData);
        $croppedImageData = str_replace('data:image/jpeg;base64,', '', $croppedImageData);
        $croppedImageData = str_replace('data:image/jpg;base64,', '', $croppedImageData);
        $croppedImageData = str_replace('data:image/gif;base64,', '', $croppedImageData);
        $croppedImageData = base64_decode($croppedImageData);
        $imagePath = 'products/' . $slug . '.jpg';
        Storage::disk('public')->put($imagePath, $croppedImageData);

        $product = Product::create([
            'name' => $request->name,
            'slug' => $slug,
            'product_category_id' => $request->product_category_id,
            'price' => $request->price,
            'stock' => $request->stock,
            'description' => $request->description,
            'image' => $imagePath,
        ]);
        return redirect()->route('dashboard.produk.index')->with('success', 'Produk berhasil ditambahkan.');
        
    }

    private function SaveImage($image) {
        if($image == null) return null;

        $croppedImageData = str_replace('data:image/png;base64,', '', $image);
        $croppedImageData = str_replace('data:image/jpeg;base64,', '', $croppedImageData);
        $croppedImageData = str_replace('data:image/jpg;base64,', '', $croppedImageData);
        $croppedImageData = str_replace('data:image/gif;base64,', '', $croppedImageData);
        $croppedImageData = base64_decode($croppedImageData);
        $imagePath = 'products/' . $slug . '.jpg';
        Storage::disk('public')->put($imagePath, $croppedImageData);

        return $imagePath;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $product = Product::with('category')->where('slug', $slug)->firstOrFail();
        $related_products = Product::where('product_category_id', $product->product_category_id)->where('slug', '!=', $slug)->take(4)->get();
        return view('dashboard.produk.show', compact('product', 'related_products'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $produk = Product::findOrFail($id);
        $categories = ProductCategory::all();
        return view('dashboard.produk.edit', compact('produk', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'product_category_id' => 'required|exists:product_categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $product = Product::findOrFail($id);
        $product->name = $request->name;
        $product->slug = Str::slug($request->name);
        $product->description = $request->description;
        $product->price = $request->price;
        $product->product_category_id = $request->product_category_id;
        
        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $product->image = $request->file('image')->store('products', 'public');
        }

        $product->stock = $request->stock;
        $product->save();

        return redirect()->route('dashboard.produk.index')->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $produk = Product::findOrFail($id);
        
        if ($produk->image) {
            Storage::disk('public')->delete($produk->image);
        }
        
        $produk->delete();

        return redirect()->route('dashboard.produk.index')->with('success', 'Produk berhasil dihapus.');
    }
}
