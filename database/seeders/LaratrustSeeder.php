<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LaratrustSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('permission_role')->truncate();
        DB::table('permission_user')->truncate();
        DB::table('permissions')->truncate();
        DB::table('roles')->truncate();
        DB::table('role_user')->truncate();
        DB::table('users')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Roles & permissions
        $roles = [
            'administrator' => ['sudo'],
            'purchasing_officer' => [
                'asset/acquisition',
                'asset/acquisition/write',
            ],
            'finance_administrator' => [],
            'staff' => ['asset/request'],
        ];

        $displayNames = [
            'administrator' => 'Administrator',
            'purchasing_officer' => 'Pembelian',
            'finance_administrator' => 'Administrator Keuangan',
            'staff' => 'Staff',
        ];

        foreach ($roles as $name => $permissions) {
            $role = \App\Models\Role::firstOrCreate(
                ['name' => $name],
                ['display_name' => $displayNames[$name]]
            );

            foreach ($permissions as $perm) {
                $permission = \App\Models\Permission::firstOrCreate(
                    ['name' => $perm],
                    ['display_name' => implode('-', array_map(
                        fn($part) => ucwords(str_replace(['-', '_'], ' ', $part)),
                        explode('/', $perm)
                    ))]
                );

                // Attach permission
                $role->permissions()->syncWithoutDetaching([$permission->id]);
            }
        }

        //Master User
        $user = \App\Models\User::create([
            'identifier' => '250602',
            'name' => 'Harry Rahman Rangkuti',
            'email' => 'harryrahman2768@gmail.com',
            'division' => 'IT',
            'department' => 'Administrasi dan Umum',
            'position' => 'SIM RS',
            'password' => bcrypt('1234567890'),
            'photo' => null,
            'app_mode' => 'asset',
            'fiscal_year' => 2025,
            'active_role_id' => 1,
        ]);

        $user->addRole('staff');
        $user->addRole('administrator');
        $user->addRole('purchasing_officer');
        $user->addRole('finance_administrator');


        //Master Pembelian
        $purchaserList = [
            [
                'identifier' => '150101',
                'name' => 'FREDY WIJAYA',
                'email' => '150101@noemail.com',
                'division' => 'Keuangan',
                'department' => 'Bidang Keuangan dan Akuntansi',
                'position' => 'Pembelian',
                'password' => bcrypt('150101'),
            ],
            [
                'identifier' => '130724',
                'name' => 'MINARVI',
                'email' => '130724@noemail.com',
                'division' => 'Keuangan',
                'department' => 'Bidang Keuangan dan Akuntansi',
                'position' => 'Pembelian',
                'password' => bcrypt('130724'),
            ],
        ];

        foreach ($purchaserList as $purchaserData) {
            $l = \App\Models\User::create(array_merge($purchaserData, [
                'photo' => null,
                'app_mode' => 'asset',
                'fiscal_year' => 2025,
                'active_role_id' => 2,
            ]));

            $l->addRole('staff');
            $l->addRole('purchasing_officer');
        }
    }
}
