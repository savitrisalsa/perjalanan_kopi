<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->category) {
            $query->where('category', $request->category);
        }

        $products = $query->latest()->paginate(5)->withQueryString();

        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'category' => 'required',
            'selling_price' => 'required|integer|min:0',
            'hpp' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
            'status' => 'required',
            'image' => 'nullable|image',
        ]);

        if ($data['stock'] <= 0) {
            $data['status'] = 'unavailable';
        }

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);

        return redirect('/products')->with('success', 'Menu berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => 'required',
            'category' => 'required',
            'selling_price' => 'required|integer|min:0',
            'hpp' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
            'status' => 'required',
            'image' => 'nullable|image',
        ]);

        if ($data['stock'] <= 0) {
            $data['status'] = 'unavailable';
        }

        if ($data['stock'] > 0 && $data['status'] == 'unavailable') {
            $data['status'] = 'available';
        }

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect('/products')->with('success', 'Menu berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect('/products')->with('success', 'Menu berhasil dihapus.');
    }
}