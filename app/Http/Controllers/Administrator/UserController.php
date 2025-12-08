<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class UserController extends AuthController
{
    public function __invoke(Request $request)
    {
        if ($request->req == 'open') {
            $data = User::with(['roles'])->findOrFail($request->id);

            $title = $data->name;
            $vue = "<user-page-detail :title='" . json_encode($title) . "' :data='" . json_encode($data) . "'/>";
        } else {
            $constant = [
                'ROLE' => Role::select('id', 'name', 'display_name')->get()
            ];

            $title = 'Manajemen Akun Pengguna';
            $vue = "<user-page :title='" . json_encode($title) . "' :constant='" . json_encode($constant) . "' />";
        }
        return response()->view('layouts.antd', compact('vue', 'title'));
    }

    public function read(Request $request)
    {
        if ($request->req == 'table') {
            $data = User::with(['roles' => function ($q) use ($request) {
                if ($request->roles) {
                    $q->whereIn('id', $request->roles);
                }
            }])
                ->withTrashed()
                ->where(function ($q) use ($request) {
                    if ($request->search)
                        $q->where('users.name', 'like', "%{$request->search}%")
                            ->orWhere('identifier', 'like', "%$request->search%");
                })
                ->where(function ($q) use ($request) {
                    if ($request->status == 'aktif') {
                        $q->whereNull('deleted_at');
                    } else {
                        $q->whereNotNull('deleted_at');
                    }
                })
                ->when($request->roles, function ($q) use ($request) {
                    $q->whereHas('roles', function ($query) use ($request) {
                        $query->whereIn('id', $request->roles);
                    });
                })
                ->paginate($this->per_page());

            return response()->json(['models' => $data]);
        } elseif ($request->req == 'sync') {
            try {
                $karyawanList = DB::connection('sqlsrv-ANDROID')->table('data_karyawan')->get();

                foreach ($karyawanList as $row) {
                    $isInactive = in_array($row->STATUS_KARYAWAN, ['Berhenti', 'R.Resign']);

                    $existing = User::withTrashed()->where('identifier', $row->NIK)->first();

                    if ($existing) {
                        DB::table('users')
                            ->where('identifier', $row->NIK)
                            ->update([
                                'division'   => $row->DIVISI,
                                'department' => $row->DEPARTMENT,
                                'position'   => $row->JABATAN,
                                'deleted_at' => $isInactive ? Carbon::now() : null,
                                'updated_at' => now(),
                            ]);
                    } else {
                        $user = User::create([
                            'identifier' => $row->NIK,
                            'name'       => $row->NAMA,
                            'email'      => $row->NIK . '@noemail.com',
                            'division'   => $row->DIVISI,
                            'department' => $row->DEPARTMENT,
                            'position'   => $row->JABATAN,
                            'password'   => bcrypt($row->NIK),
                            'deleted_at' => $isInactive ? Carbon::now() : null,
                            'active_role_id' => 4,
                        ]);
                        $user->roles()->sync([4]);
                    }
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Sinkronisasi selesai. Total data: ' . count($karyawanList),
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal sinkronisasi: ' . $e->getMessage(),
                ], 500);
            }
        }
    }

    public function write(Request $request)
    {
        if ($request->req == 'write') {
            $this->validate(
                $request,
                [
                    'identifier' => 'required',
                    'sso_id' => 'required',
                    'type' => 'required',
                    'roles' => 'required',
                    'name' => 'required',
                    // 'email' => 'required',
                    'unit_id' => 'required',
                ],
                [
                    'sso_id.required' => 'Pilih pengguna dari daftar pegawai.',
                    'roles.required' => 'Wajib pilih Role User',
                ],
            );

            DB::transaction(function () use ($request) {
                $data = User::find($request->id) ?? new User();

                $data->identifier = $request->identifier;
                $data->sso_id = $request->sso_id;
                $data->type = $request->type;
                $data->name = $request->name;
                $data->email = $request->email;
                $data->photo = $request->photo;
                $data->unit_id = $request->unit_id;
                $data->active_role_id = collect($request->input('roles'))->last();
                $data->save();

                $data->syncRoles($request->roles);
            });

            return response()->json(true);
        } elseif ($request->req === 'delete') {
            $data = User::find($request->id);

            if (!$data) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data tidak ditemukan',
                ], 404);
            }

            $data->delete();

            return response()->json([
                'status' => 'success',
                'action' => 'delete',
                'data' => $data,
            ]);
        } elseif ($request->req === 'restore') {
            $data = User::onlyTrashed()->find($request->id);

            if (!$data) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data tidak ditemukan atau belum terhapus',
                ], 404);
            }

            $data->restore();

            return response()->json([
                'status' => 'success',
                'action' => 'restore',
                'data' => $data,
            ]);
        }
    }
}
