<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use App\Models\Aset\AsetRuang;
use App\Models\Aset\AsetPerubahan;
use App\Models\Aset\AssetProfile;
use App\Models\Satker;
use App\Models\CodeRegister;
use DB;
use PDF;

class AsetRuangController extends AuthController
{
    public function __invoke(Request $request)
    {
        $constant = [
            'SATKER' => Satker::getSatkers(),
            'ALLSATKER' => Satker::all(),
            'RUANG' => AsetRuang::all(),
        ];

        if($request->req == 'print_list'){
            $ruang = AsetRuang::with('profil')->find($request->id);

            // Grouping berdasarkan perolehan_id, kategori_id dan juga deskripsi dan juga tgl_perolehan
            $groupedData = $ruang->profil->groupBy(function ($x) {
                return $x->perolehan_id . '-' . $x->kategori_id . '-' . $x->deskripsi . '-' . $x->tgl_perolehan ;
            });

            $data = $groupedData->map(function ($items){
                $itemPertama = $items->first();

                $groupByKondisi = $items->groupBy('kondisi');
                
                $dataKondisi = $groupByKondisi->map(function ($n) {
                    return $n->count();
                })->toArray();

                return [
                    'perolehan_id' => $itemPertama->perolehan_id,
                    'deskripsi' => $itemPertama->deskripsi,
                    'kategori_id' => $itemPertama->kategori_id,
                    'kategori_nama' => $itemPertama->kategori_nama,
                    'deskripsi' => $itemPertama->deskripsi,
                    'tgl_perolehan' => $itemPertama->tgl_perolehan,
                    'jumlah' => $items->count(),
                    'kondisi' => $dataKondisi,

                ];
            })->toArray();

            return PDF::loadView('print.print_list', compact('ruang', 'data'))
                ->setPaper('f4', 'landscape')->stream();

        }else{
            $vue = "<aset-ruang-page :constant='" . json_encode($constant) . "' />";
            return response()->view('layouts.antd', compact('vue'));
        }
    }

    public function read(Request $request)
    {
        if($request->req == 'table' || $request->req == 'print_data_ruang'){

            $satkerName = null;
                if ($request->satker_id) {
                    $satker = Satker::find($request->satker_id);
                    $satkerName = $satker ? strtoupper($satker->nama) : null;
                }

            $data = AsetRuang::with('satker')
                ->where(function ($q) use ($request) {
                    if($request->search)
                        $q->where('aset_ruang.nama', 'like', "%{$request->search}%")
                        ->orWhere('aset_ruang.kode', 'like', "%{$request->search}%");
                })
                ->withCount('profil')
                ->filterSatker($request);

                if ($request->req == 'table') {
                    $data = $data->paginate($this->per_page());
                    return response()->json(['models' => $data]);
                } elseif ($request->req == 'print_data_ruang') {
                    $data = $data->get();

                    return PDF::loadView('print.print_data_ruang', compact('data', 'satkerName'))
                    ->setPaper('f4', 'landscape')->stream();
                }

        }
    }

    public function write(Request $request)
    {
        if($request->req == 'write'){
            $this->validate($request, [
                'kode' => 'required',
                'nama' => 'required',
                'satker_id' => 'required',
                'is_lab' => 'required',
            ]);

            $data = AsetRuang::find($request->id);
            
            if(!$data){
                $data = new AsetRuang();
            }

            $data->kode = $request->kode;
            $data->nama = $request->nama;
            $data->is_lab = $request->is_lab;
            $data->keterangan = $request->keterangan;
            $data->penanggung_jawab = $request->penanggung_jawab;
            $data->satker_id = $request->satker_id;
            $data->editor_id = request()->user()->id;
            // $data->default = 0;
            $data->created_at = now(); 
            // $data->updated_at = now();

            return response()->json($data->save());
        }
        elseif($request->req == 'cek_kode_ruang'){
            $data = AsetRuang::where('satker_id', $request->satker_id)
                ->where('kode', $request->ruang_kode)
                ->first();

            if ($data) {
                return response()->json([
                        'message' => 'Kode Ruang sudah digunakan di Satker ini.', 
                        'ruang_nama' => $data->nama
                    ], 400);
            } else{
                return response()->json(['message' => 'Kode Ruang tersedia.']);
            }
        }
        elseif($request->req == 'delete'){
            $data = AsetRuang::find($request->id);
            if(!$data)
                return response()->json('Data Not Found', 403);
            
            $data->editor_id = $this->user()->id;
            $data->save();
            return response()->json($data->delete());
        }
        elseif ($request->req == 'distribute') {
            $ruangId = $request->ruang_id;
            $newRuangId = $request->new_ruang_id;
    
            $profiles = AssetProfile::where('ruang_id', $ruangId)->get();
    
            $dataToInsert = [];

            foreach ($profiles as $profile) {
                $ruang = AsetRuang::find($newRuangId);
                $profile->ruang_id = $newRuangId;
                $profile->ruang_nama = ($ruang->kode ?? '') . ' - ' . ($ruang->nama ?? '');
                $profile->editor_id = request()->user()->id;
                $profile->save();

                // insert ke table aset_perubahan
                $dataToInsert[] = [
                    'profil_id' => $profile->id,
                    'satker_id' => $profile->satker_id,
                    'type' => 'distribusi-ruang',
                    'kondisi' => $profile->kondisi,
                    'code' => CodeRegister::next('aset_perubahan'),
                    'deskripsi' => $profile->deskripsi,
                    'tipe_ruang' => $profile->tipe_ruang,
                    'ruang_id' => $newRuangId,
                    'ruang_nama' => ($ruang->kode ?? '') . ' - ' . ($ruang->nama ?? ''),
                    'keterangan_lokasi' => $profile->keterangan_lokasi,
                    'catatan' => $profile->catatan,
                    'pemanfaatan_id' => $profile->pemanfaatan_id,
                    'pemanfaatan_nama' => $profile->pemanfaatan_nama,
                    'pemanfaatan_catatan' => $profile->pemanfaatan_catatan,
                    'editor_id' => request()->user()->id,
                    'created_at' => now(),
                ];
            }
    
            DB::transaction(function () use ($dataToInsert) {
                if (AsetPerubahan::insert($dataToInsert))
                    CodeRegister::update_number('aset_perubahan');
            });

            return response()->json(['message' => 'Items successfully distributed'], 200);
        }
        elseif ($request->req == 'change_unit') {

            $data = AsetRuang::find($request->ruang_id);
            
            if (!$data) {
                return response()->json(['error' => 'Data tidak ditemukan'], 404);
            }
            $data->satker_id = $request->new_satker_id;
            $data->editor_id = request()->user()->id;
            $data->updated_at = now();
            $data->save();
    
            $profiles = AssetProfile::where('ruang_id', $request->ruang_id)->get();

            foreach ($profiles as $profile) {
                $profile->satker_id = $request->new_satker_id;
                $profile->save();
            }

            return response()->json(true);
        }
    }

    public function list_dbr(Request $request)
    {
        try {
            $decryptRuangId = decrypt($request->ruang_id);

            $data = AsetRuang::with('profil')->find($decryptRuangId);
            return view('layouts.aset_ruang_data', compact('data'));
        } catch (DecryptException $e) {
            abort(404);
        }
    }
}
