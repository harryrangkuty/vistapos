<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\MasterData\Supplier;
use App\Models\MasterData\Item;

class LookUpController extends Controller
{
    public function users(Request $request)
    {
        $query = User::query();

        if ($id = $request->get('id')) {
            return User::where('id', $id)->get(['id', 'identifier', 'name']);
        }

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('identifier', 'like', "%$search%");
            });
        }

        return $query->limit($request->get('limit', 10))
            ->get(['id', 'identifier', 'name']);
    }

    public function suppliers(Request $request)
    {
        $query = Supplier::query();

        if ($id = $request->get('id')) {
            return Supplier::where('id', $id)->get(['id', 'gl_code', 'name']);
        }

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('gl_code', 'like', "%$search%");
            });
        }

        return $query->limit($request->get('limit', 10))
            ->get(['id', 'gl_code', 'name']);
    }

    public function items(Request $request)
    {
        $query = Item::query();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('code', 'like', "%$search%");
            });
        }

        return $query->limit($request->get('limit', 10))
            ->get(['code', 'name']);
    }
}
