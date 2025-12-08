<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Aset\AssetAcquisition;
use App\Models\Aset\AssetProfile;
use App\Models\Aset\AssetTransaction;
use App\Models\JenisTransaksi;
use App\Models\MasterData\Category;
use App\Models\Satker;
use App\Models\User;

class MonitoringController extends AuthController
{
    public function __invoke()
    {
        $constant = [
            'SATKER' => Satker::all(),
            'JENIS_TRANSAKSI_OPT' => JenisTransaksi::AssetAcquisition()->get(),
        ];

        $user = Auth::user();

        $vue = "<monitoring-page :constant='" . json_encode($constant) . "' />";

        return view('layouts.antd', compact('vue'));
    }

    public function read(Request $request)
    {
        if ($request->req == 'total_perolehan') {

            $data = AssetAcquisition::with(['jenisTransaksi'])
                ->where(function ($q) use ($request) {
                    if ($request->satker_id) {
                        $q->filterSatker($request);
                    }
                })
                ->where(function ($q) use ($request) {
                    if ($request->year) {
                        $q->whereYear('updated_at',  $request->year);
                    }
                })
                ->whereNull('deleted_at')
                ->get();

            $dataGrup = collect($data)->groupBy(function ($item) {
                return "{$item->jenis_transaksi_id} - {$item->jenisTransaksi->uraian}";
            })->map(function ($m, $a) {
                return $m->groupBy('status')->map(function ($n, $b) use ($a) {
                    return (object)[
                        'total_nilai' => $n->sum('total'),
                        'total_perolehan' => $n->count(),
                        'total_item' => $n->sum('total_item')
                    ];
                });
            });

            $has_not_registered = $data->reject(function ($item) {
                return $item->status == 'FINISH';
            });

            $transaksi =  AssetTransaction::where('reference_type', 'ASET-PEROLEHAN')
                ->where(function ($q) use ($request) {
                    if ($request->satker_id) {
                        $q->filterSatker($request);
                    }
                })
                ->where(function ($q) use ($request) {
                    if ($request->year) {
                        $q->whereYear('created_at',  $request->year);
                    }
                })
                ->get();

            return response()->json([
                'models' => $dataGrup,
                'total_nilai_registered' => $transaksi->sum('nilai'),
                'total_perolehan_registered' => $transaksi->groupBy('reference_id')->count(),
                'total_item_registered' => $transaksi->count(),
                'total_nilai_unregistered' => $has_not_registered->sum('total'),
                'total_perolehan_unregistered' => $has_not_registered->count(),
                'total_item_unregistered' => $has_not_registered->sum('total_item'),
            ]);
        } elseif ($request->req == 'aset_teregister') {

            $kategori = Kategori::selectRaw('LEFT(kd_brg, 1)  as kode, uraian')
                ->whereNull('kd_bid')
                ->where('kd_brg', 'NOT LIKE', '1%')
                ->get()
                ->keyBy('kode');

            $profil = AssetTransaction::selectRaw('LEFT(kategori_id, 1) as category_prefix, SUM(nilai) as total, COUNT(*) as count')
                ->groupBy('category_prefix')
                ->where(function ($q) use ($request) {
                    if ($request->satker_id) {
                        $q->filterSatker($request);
                    }
                })
                ->where(function ($q) use ($request) {
                    if ($request->year) {
                        $q->whereYear('created_at', '<=',  $request->year);
                    } else {
                        $q->whereYear('created_at', '<=',  now()->year);
                    }
                })
                ->get()
                ->map(function ($item) use ($kategori) {
                    $namaKategori = data_get($kategori, $item->category_prefix . '.uraian', '-');
                    $namaKategori = ucwords(strtolower($namaKategori));

                    return ([
                        'nama_kategori' => $namaKategori,
                        'total_nilai' => $item->total,
                        'count' => $item->count,
                    ]);
                });

            return response()->json(['models' => $profil]);
        } elseif ($request->req == 'aset_kondisi') {

            $kondisi = ['BAIK', 'RUSAK RINGAN', 'RUSAK BERAT'];

            $profil = AssetProfile::selectRaw('kondisi, ROUND(SUM(nilai_buku), 2) as total_nilai, COUNT(*) as total_item')
                ->where(function ($q) use ($request) {
                    if ($request->satker_id) {
                        $q->filterSatker($request);
                    }
                })
                ->where(function ($q) use ($request) {
                    if ($request->year) {
                        $q->whereYear('tgl_perolehan', '<=',  $request->year);
                    } else {
                        $q->whereYear('tgl_perolehan', '<=',  now()->year);
                    }
                })
                ->groupBy('kondisi')
                ->get()
                ->mapWithKeys(function ($m) use ($kondisi) {
                    return [$kondisi[$m['kondisi'] - 1] => $m];
                });

            return response()->json(['models' => $profil]);
        } elseif ($request->req == 'aset_user') {
            $operator = User::whereRaw("JSON_CONTAINS(satkers_id, ?)", ['[' . $request->satker_id . ']'])
                ->get();

            $reviewer = User::whereRaw("JSON_CONTAINS(reviews_id, ?)", ['[' . $request->satker_id . ']'])
                ->get();

            return response()->json(['operator' => $operator, 'reviewer' => $reviewer]);
        } elseif ($request->req == 'rekap_per_satker') {

            User::removeAllAppends();

            $data = AssetAcquisition::with(['detail.kategori:kd_brg,uraian', 'editor:id,name'])
                ->where(function ($q) use ($request) {
                    if ($request->status_perolehan)
                        $q->where('status', $request->status_perolehan);
                    })
                ->where(function ($q) use ($request) {
                    if ($request->jenis_transaksi_id)
                        $q->where('jenis_transaksi_id', $request->jenis_transaksi_id);
                })
                ->where(function ($q) use ($request) {
                    if ($request->year)
                        $q->whereYear('tgl_perolehan', $request->year);
                })
                ->with([
                    'jenisTransaksi:kode,uraian',
                    'satker:id,nama'
                ])
                ->filterSatker($request)
                ->withCount('detail')
                ->get()
                ->map(function($item){
                    $directoryPath = storage_path('app/documents/' . $item->kode . '/');
                    $files = [];

                    if(is_dir($directoryPath)){
                        $scanned = array_diff(scandir($directoryPath), ['.', '..']);
                        foreach($scanned as $idx => $x){
                            $files[] = [
                                'no' => $idx - 1,
                                'code' => $item->kode,
                                'name' => $x,
                            ];
                        }
                    } 
                    $item->files = $files;
                    return $item;
                });

            return response()->json(['models' => $data]);
        } elseif ($request->req == 'preview_file') {

            $extension = strtolower(pathinfo($request->filename, PATHINFO_EXTENSION));
            $path = storage_path('app/documents/' . $request->code . '/' . $request->filename);

            if (!file_exists($path)) {
                return response()->json(['message' => 'File tidak ditemukan'], 404);
            }

            if ($extension === 'pdf') {

                return response()->file($path);
            } elseif (in_array($extension, ['jpg', 'jpeg', 'png'])) {

                $arrContextOptions = array(
                    "ssl" => array(
                        "verify_peer" => false,
                        "verify_peer_name" => false,
                    ),
                );

                $base64Image = base64_encode(file_get_contents($path, false, stream_context_create($arrContextOptions)));

                $data = 'data:image/png;base64,' . $base64Image;

                $pdf = PDF::loadView('print.convert_image', compact('data'))->output();

                return new Response($pdf, 200, array(
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'inline; filename="preview.pdf"',
                ));
            }
        }
    }
}
