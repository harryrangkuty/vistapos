<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;

class RoleController extends AuthController
{
    public function __invoke(Request $request)
    {
        $constant = [
            'PERMISSION' => Permission::where('name', '<>', 'sudo')->get()
        ];

        $title = 'Manajemen Role';
        $vue = "<role-page :title='" . json_encode($title) . "' :constant='" . json_encode($constant) . "' />";
        return response()->view('layouts.antd', compact('vue', 'title'));
    }
    public function read(Request $request)
    {
        if ($request->req == 'table') {
            $data = Role::with(['permissions' => fn($q) => $q->where('name', '<>', 'sudo')])
                        ->where(function ($q) use ($request) {
                            if($request->search)
                            $q->where('name', 'like', "%{$request->search}%");
                        })
                        ->paginate($this->per_page());

            return response()->json(['models' => $data]);
        } elseif ($request->req == 'permission_data') {
            $role = Role::find($request->id);

            if (!$role) {
                return response()->json(['message' => 'Data tidak Ditemukan'], 404);
            }

            $availablePermissions = Permission::select('id', 'name')
                                                ->whereNotIn('id', $role->permissions->pluck('id'))
                                                ->where('name', '<>', 'sudo')
                                                ->get();
        
            $permissions = $role->permissions()->get(); 
        
            return response()->json(['models' => $permissions, 'available_permissions' => $availablePermissions]);
        } 
    }

    public function write(Request $request)
    {
        if ($request->req == 'write') {
            
            $data = Role::find($request->id) ?? new Role();

            $permissions = $request->permissions;

            if($data->name === 'Administrator'){
                $sudo = Permission::where('name', 'sudo')->first();
                array_push($permissions, $sudo->id);
            }

            $data->name = $request->name;
            $data->display_name = $request->display_name;
            $data->description = $request->description;
            $data->save();
            $data->syncPermissions($permissions);
            return response()->json(true);
        } elseif ($request->req == 'attach_permission') {
            
            $data = Role::find($request->id);

            $data->permissions()->attach($request->permissions);

            return response()->json(true);
        } elseif ($request->req == 'detach_permission') {
            
            $data = Role::find($request->id);

            $data->permissions()->detach($request->permissions);

            return response()->json(true);
        }
    }

}
