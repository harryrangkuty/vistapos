<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Asset\AssetProfile;
use Illuminate\Support\Facades\DB;

class ImportOldAssetsSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('data/inventaris_iis.xlsx');
        $data = Excel::toArray([], $path)[0];

        $procurementId = DB::table('procurements')->insert([
            'branch_id' => 1,
            'code' => 'IMPORT-2024',
            'transaction_type_code' => 'S00',
            'status' => 'registered',
            'book_date' => now(),
            'purchasing_officer_id' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        foreach ($data as $row) {
            for ($i = 0; $i < $row['STOCK_AWAL']; $i++) {
                AssetProfile::create([
                    'item_code' => $row['KODE_BRG'],
                    'description' => $row['NAMA'],
                    'procurement_id' => $procurementId,
                    'transaction_type_id' => 1, // purchase/import
                    'registered_by' => 1,
                    'book_date' => now(),
                    'asset_number' => auto_nup_generator(),
                    'condition' => 1,
                    'acquisition_cost' => $row['HRG_BELI'],
                    'book_value' => $row['HRG_BELI'],
                    'accumulated_depreciation' => 0,
                ]);
            }
        }
    }
}
