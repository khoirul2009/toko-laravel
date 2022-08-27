<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Cviebrock\EloquentSluggable\Services\SlugService;

class CategoryController extends Controller
{

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama'  => 'required|max:255',
            'slug'  => 'required|max:255|unique:categories',

        ]);

        Category::create($validatedData);

        return ['success'  => true, 'message' => 'Kategori telah ditambahkan'];
    }


    public function update(Request $request, Category $category)
    {
        $rules = [
            'nama'  => 'required|max:255',
        ];
        if ($request->slug != $category->slug) {
            $rules['slug']  = 'required|unique:categories|max:255';
        }
        $validatedData = $request->validate($rules);

        Category::where('id', $category->id)
            ->update($validatedData);
        return ['success'  => true, 'message' => 'Kategori telah diedit'];
    }


    public function destroy(Category $category)
    {
        Category::destroy($category->id);
        return ['success' => true, 'message' => 'Category berhasil dihapus'];
    }
    public function getSlug(Request $request)
    {
        if ($request->nama != "") {
            $slug = SlugService::createSlug(Category::class, 'slug', $request->nama);
            return response()->json(['slug' => $slug]);
        }
        return response()->json(['slug' => ""]);
    }
}
