<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use App\Models\MasterData\Item;

class ItemController extends AuthController
{
    public function __invoke(Request $request)
    {
        $constant = [];

        $title = 'Manajemen Master Item Barang';

        $vue = "<item-page :title='" . json_encode($title) . "' :constant='" . json_encode($constant) . "' />";

        return response()->view('layouts.antd', compact('vue', 'title'));
    }

    public function read(Request $request)
    {
        if ($request->req == 'table') {
            $data = Item::with(['stockCode', 'category', 'uom'])
                ->where(function ($q) use ($request) {
                    $q->where('name', 'like', "%{$request->search}%");
                })
                ->paginate($this->per_page());

            return response()->json(['models' => $data]);
        }
    }

    public function write(Request $request)
    {
        //
    }
}
