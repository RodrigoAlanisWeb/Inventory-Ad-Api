<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\In;

class InventoryController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
        ]);

        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'res' => false,
                'auth' => false,
                'msg' => 'The Token Provided Are Invalid'
            ]);
        }

        try {
            Inventory::create([
                'name' => $request->get('name'),
                'description' => $request->get('description'),
                'user_id' => $user->id,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'res' => false,
                'msg' => 'Error At Creating The Inventory'
            ]);
        }

        return response()->json([
            'res' => true,
            'auth'=> true,
            'msg' => 'Inventory Created Successfully'
        ]);
    }

    public function get()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'res' => false,
                'auth' => false,
                'msg' => 'The Token Provided Are Invalid'
            ]);
        }

        return response()->json([
            'res' => true,
            'auth'=> true,
            'inventories' => $user->inventories,
        ]);
    }

    public function delete(Inventory $inventory)
    {
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

        $inventory->delete();

        return response()->json([
            'res' => true,
            'auth'=> true,
            'msg' => 'Inventory Deleted Successfully'
        ]);
    }

    public function update(Request $request, Inventory $inventory)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
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

        $inventory->name = $request->get('name');
        $inventory->description = $request->get('description');
        $inventory->save();

        return response()->json([
            'res' => true,
            'auth'=> true,
            'msg' => 'Inventory Updated Successfully'
        ]);
    }
}
