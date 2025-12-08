<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use App\Models\MasterData\Warehouse;
use App\Models\MasterData\StockCode;

class WarehouseController extends AuthController
{
    public function __invoke(Request $request)
    {
        $constant = [
            'STOCK_CODE_OPTIONS' => StockCode::all()
        ];

        $title = 'Manajemen Gudang';

        $vue = "<warehouse-page :title='" . json_encode($title) . "' :constant='" . json_encode($constant) . "' />";
        return response()->view('layouts.antd', compact('vue', 'title'));
    }

    public function read(Request $request)
    {
        if ($request->req == 'table') {
            $data = Warehouse::withTrashed()
                ->with(['personInCharge:id,identifier,name', 'stockCodes', 'branch:id,name'])
                ->where(function ($q) use ($request) {
                    $q->where('name', 'like', "%{$request->search}%");
                })
                ->where(function ($q) use ($request) {
                    if ($request->status == 'aktif') {
                        $q->whereNull('deleted_at');
                    } else {
                        $q->whereNotNull('deleted_at');
                    }
                })
                ->paginate($this->per_page());

            return response()->json(['models' => $data]);
        }
    }

    public function write(Request $request)
    {
        if ($request->req == 'write') {
            $this->validate(
                $request,
                [
                    'code' => 'required|unique:warehouses,code,' . $request->id,
                    'name' => 'required|unique:warehouses,name,' . $request->id,
                    'address' => 'nullable|string|max:255',
                    'can_receive' => 'required|boolean',
                    'can_dispatch' => 'required|boolean',
                    'person_in_charge_id' => 'required',
                    'stock_code_id' => 'required',
                ],
                [
                    'code.required' => 'Kode gudang wajib diisi.',
                    'code.unique' => 'Kode gudang sudah terdaftar.',
                    'name.required' => 'Nama gudang wajib diisi.',
                    'name.unique' => 'Nama gudang sudah terdaftar.',
                    'address.max' => 'Lokasi gudang tidak boleh lebih dari 255 karakter.',
                    'can_receive.required' => 'Status penerimaan barang wajib dipilih.',
                    'can_dispatch.required' => 'Status pengeluaran barang wajib dipilih.',
                    'person_in_charge_id.required' => 'PJ Gudang wajib dipilih.',
                    'stock_code_id.required' => 'Jenis Stock wajib dipilih.',
                ]
            );

            $data = Warehouse::find($request->id) ?? new Warehouse();

            $data->code = $request->code;
            $data->name = $request->name;
            $data->stock_code_id = $request->stock_code_id ?? null;
            $data->address = $request->address;
            $data->description = $request->description;
            $data->can_receive = $request->can_receive;
            $data->can_dispatch = $request->can_dispatch;
            $data->person_in_charge_id = $request->person_in_charge_id ?? null;
            $data->editor_id = $this->user()->id;

            return response()->json($data->save());
        } elseif ($request->req === 'delete') {
            $data = Warehouse::find($request->id);

            if (!$data) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data tidak ditemukan',
                ], 404);
            }

            $data->editor_id = $this->user()->id;
            $data->delete();

            return response()->json([
                'status' => 'success',
                'action' => 'delete',
                'data' => $data,
            ]);
        } elseif ($request->req === 'restore') {
            $data = Warehouse::onlyTrashed()->find($request->id);

            if (!$data) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data tidak ditemukan atau belum terhapus',
                ], 404);
            }

            $data->editor_id = $this->user()->id;
            $data->restore();

            return response()->json([
                'status' => 'success',
                'action' => 'restore',
                'data' => $data,
            ]);
        }
    }
}
