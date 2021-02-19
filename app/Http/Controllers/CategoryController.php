<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function create(Request $request, Inventory $inventory)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'res' => false,
                'auth' => false,
                'msg' => 'The Token Provided Are Invalid'
            ]);
        }

        if ($inventory->user_id != $user->id) {
            return response()->json([
                'res' => false,
                'auth' => false,
                'msg' => 'You Are Not The Owner Of The Inventory'
            ]);
        }

        try {
            Category::create([
                'name' => $request->get('name'),
                'inventory_id' => $inventory->id,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'res' => false,
                'msg' => 'Error At Creating The Category'
            ]);
        }

        return response()->json([
            'res' => true,
            'auth'=> true,
            'msg' => 'Category Created Successfully'
        ]);
    }

    public function get(Inventory $inventory)
    {
        return response()->json([
            'res' => true,
            'auth'=> true,
            'categories' => $inventory->categories,
        ]);
    }

    public function delete(Category $category)
    {
        $category->delete();

        return response()->json([
            'res' => true,
            'auth'=> true,
            'msg' => 'Category Deleted Successfully'
        ]);
    }

    public function getOne(Category $category) {
        return response()->json([
            'res' => true,
            'auth'=> true,
            'category' => $category
        ]); 
    }

}
