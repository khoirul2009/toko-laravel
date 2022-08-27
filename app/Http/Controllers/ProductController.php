<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use \Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;


class ProductController extends Controller
{

    public function index()
    {
        return view('produk.index', [
            'title' => 'Home',
            'categories' => Category::all()
        ]);
    }
    public function fetch_data(Request $request)
    {
        if ($request->ajax()) {
            $products = Product::with(['category'])->latest()->filter(request(['search', 'category']))->paginate(6)->withQueryString();
            return view('produk.produk', compact('products'))->render();
        }
        abort(404);
    }

    public function show(Request $request, Product $product)
    {
        $product = Product::with(['category'])->where('id', $product->id)->first();
        if ($request->ajax()) {
            return view('produk.detail', [
                'product' => $product
            ])->render();
        }
        abort(404);
    }


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama'  => 'required|max:255|unique:products',
            'slug'  => 'required|unique:products|max:255',
            'id_kategori' => 'required',
            'harga'    => 'required',
            'berat'    => 'required',
            'stok'  => 'required',
            'gambar'   => 'image|file|max:2048|required',
            'deskripsi' => 'required'
        ]);

        if ($request->file('gambar')) {
            $validatedData['gambar'] = $request->file('gambar')->store('products-images');
        }

        Product::create($validatedData);

        return ['success' => true, 'message' => 'Produk berhasil ditambahkan'];
    }

    public function destroy(Product $product)
    {
        if ($product->gambar) {
            Storage::delete($product->gambar);
        }
        Product::destroy($product->id);

        return ['success' => true, 'message' => 'Produk berhasil dihapus'];
    }

    public function update(Request $request, Product $product)
    {
        $rules = [
            'nama'  => 'required|max:255',
            'id_kategori' => 'required',
            'harga'    => 'required',
            'berat'    => 'required',
            'stok'  => 'required',
            'gambar'   => 'image|file|max:2048',
            'deskripsi' => 'required'

        ];
        if ($request->slug != $product->slug) {
            $rules['slug'] = 'required|unique:products|max:255';
        }

        $validatedData = $request->validate($rules);

        if ($request->file('gambar')) {
            if ($request->gambarLama) {
                Storage::delete($request->gambarLama);
            }
            $validatedData['gambar'] = $request->file('gambar')->store('post-images');
        }
        Product::where('id', $product->id)
            ->update($validatedData);
        return ['success' => true, 'message' => 'Produk berhasil diupdate'];
    }

    public function getSlug(Request $request)
    {
        if ($request->nama != "") {
            $slug = SlugService::createSlug(Product::class, 'slug', $request->nama);
            return response()->json(['slug' => $slug]);
        }
        return response()->json(['slug' => ""]);
    }
}
