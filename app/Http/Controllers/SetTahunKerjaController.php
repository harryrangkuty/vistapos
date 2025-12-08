<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class SetTahunKerjaController extends Controller
{
    public function write(Request $request)
    {
        $this->validate($request, [
            'tahun_kerja' => 'required',
        ]);

        $data = User::find(request()->user()->id);
        $data->tahun_kerja = $request->tahun_kerja;
        $data->save();
        
        return response()->json(true);
    }
}