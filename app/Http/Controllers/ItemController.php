<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Exception;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function show(Item $item) {
        return response()->json($item,200);
    }

    public function search(Request $request) {
        $request->validate(['key'=>'string|required']);

        $items = Item::where('name','like',"%$request->key%")
            ->orWhere('description','like',"%$request->key%")->get();

        return response()->json($items, 200);
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'string|required',
            'description' => 'string|required',
            'price' => 'numeric|required',
            'quantity' => 'numeric|required',
            'condition' => 'string|required',
        ]);

        try {
            $item = Item::create($request->all());
            return response()->json($item, 202);
        }catch(Exception $ex) {
            return response()->json([
                'message' => $ex->getMessage()
            ],500);
        }

    }

    public function update(Request $request, Item $item) {
        try {
            $item->update($request->all());
            return response()->json($item, 202);
        }catch(Exception $ex) {
            return response()->json(['message'=>$ex->getMessage()], 500);
        }
    }

    public function destroy(Item $item) {
        $item->delete();
        return response()->json(['message'=>'Item deleted.'],202);
    }

    public function index() {
        $items = Item::orderBy('name')->get();
        return response()->json($items, 200);
    }
}
