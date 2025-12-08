<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;

class ProfileController extends Controller
{
    public function __invoke(Request $request)
    {
        $vue = "<profil-page />";
        
        return response()->view('layouts.antd', compact('vue'));
    } 

    public function write(Request $request){
        if ($request->req == 'switch_role') {
            $user = User::find($request->id);
            $user->active_role_id = $request->role_id;
            $user->save();

            return response()->json(true);
        }
    }
}
