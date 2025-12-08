<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;

class ProfilController extends Controller
{
    public function __invoke(Request $request)
    {
        $vue = "<profil-page />";

        return response()->view('layouts.antd', compact('vue'));
    }
    public function write(Request $request)
    {
        if ($request->req == 'switch_mode') {
            $user = User::find($request->id);
            $user->mode = $request->mode;

            if ($request->mode == 'aset') {
                //JIKA SWITCH MODE KE ASET MAKA ACTIVE ROLE AKAN DIARAHKAN KE ROLE ASET YANG DIMILIKI OLEH USER
                $role = $user->roles()
                    ->where('name', 'LIKE', '%Aset%')
                    ->orWhere('name', 'LIKE', '%KDP%')
                    ->first();
            } elseif ($request->mode == 'persediaan') {
                //JIKA SWITCH MODE KE PERSEDIAAN MAKA ACTIVE ROLE AKAN DIARAHKAN KE ROLE PERSEDIAAN YANG DIMILIKI OLEH USER
                $role = $user->roles()
                    ->where('name', 'LIKE', '%Persediaan%')
                    ->first();
            }

            if (isset($role)) {
                $user->active_role_id = $role->id;
            }

            $user->save();

            return response()->json(true);
        } elseif ($request->req == 'switch_role') {
            $user = User::find($request->id);
            $user->active_role_id = $request->role_id;

            $role = Role::find($request->role_id);
            if (str_contains($role->name, 'Aset') || str_contains($role->name, 'KDP')) {
                $user->mode = 'aset';
            } elseif (str_contains($role->name, 'Persediaan')) {
                $user->mode = 'persediaan';
            }

            $user->save();

            return response()->json(true);
        }
    }
}
