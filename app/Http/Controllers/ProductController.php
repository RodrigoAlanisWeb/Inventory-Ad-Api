<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'category' => 'required',
            'count' => 'required',
            'image' => 'required|file'
        ]);

        try {
            $path = $request->file('image')->store('images');
            Product::create([
                'name' => $request->get('name'),
                'description' => $request->get('description'),
                'category_id' => $request->get('category'),
                'image' => $path,
                'count' => $request->get('count')
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'res' => false,
                'msg' => 'Error At Creating The Product'
            ]);
        }

        return response()->json([
            'res' => true,
            'auth'=> true,
            'msg' => 'Product Created Successfully'
        ]);
    }

    public function get(Product $product)
    {
        return response()->json([
            'res' => true,
            'auth'=> true,
            'inventories' => $product,
        ]);
    }

    public function getAll(Category $category)
    {
        return response()->json([
            'res' => true,
            'auth'=> true,
            'products' => $category->products,
        ]);
    }

    public function delete(Product $product)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'res' => false,
                'auth' => false,
                'msg' => 'The Token Provided Are Invalid'
            ]);
        }

        $product->delete();

        return response()->json([
            'res' => true,
            'auth'=> true,
            'msg' => 'Product Deleted Successfully'
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'category' => 'required|integer',
            'count' => 'required|integer',
        ]);

        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'res' => false,
                'auth' => false,
                'msg' => 'The Token Provided Are Invalid'
            ]);
        }

        $product->name = $request->get('name');
        $product->description = $request->get('description');
        $product->category_id = $request->get('category');
        $product->count = $request->get('count');
        if ($request->file('image') !== null) {
            $path = $request->file('image')->store('images');
            $product->image = $path;
        } 
        $product->save();

        return response()->json([
            'res' => true,
            'auth'=> true,
            'msg' => 'Product Updated Successfully'
        ]);
    }

    public function add(Product $product, $count)
    {
        $product->count += $count;
        $product->save();

        return response()->json([
            'res' => true,
            'auth'=> true,
            'msg' => 'Count Added Successfully'
        ]);
    }

    public function remove(Product $product, $count)
    {
        $product->count -= $count;
        $product->save();

        return response()->json([
            'res' => true,
            'auth'=> true,
            'msg' => 'Count Removed Successfully'
        ]);
    }
}
