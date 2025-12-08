<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterData\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            // Tidak disusutkan
            [
                'code' => '200',
                'name' => 'AKTIVA TETAP',
                'type' => 'asset',
                'depreciation_group_code' => null,
                'is_active' => true,
            ],
            [
                'code' => '200.01',
                'name' => 'TANAH',
                'type' => 'asset',
                'depreciation_group_code' => null,
                'is_active' => true,
            ],

            // Disusutkan
            [
                'code' => '200.02',
                'name' => 'BANGUNAN',
                'type' => 'asset',
                'depreciation_group_code' => 'K4',
                'is_active' => true,
            ],
            [
                'code' => '200.03',
                'name' => 'SARANA DAN PRASARANA',
                'type' => 'asset',
                'depreciation_group_code' => 'K3',
                'is_active' => true,
            ],
            [
                'code' => '200.04',
                'name' => 'MESIN DAN PERALATAN',
                'type' => 'asset',
                'depreciation_group_code' => 'K3',
                'is_active' => true,
            ],
            [
                'code' => '200.05',
                'name' => 'ALAT-ALAT MEDIS',
                'type' => 'asset',
                'depreciation_group_code' => 'K2',
                'is_active' => true,
            ],
            [
                'code' => '200.06',
                'name' => 'KENDARAAN',
                'type' => 'asset',
                'depreciation_group_code' => 'K2',
                'is_active' => true,
            ],
            [
                'code' => '200.07',
                'name' => 'INVENTARIS AC',
                'type' => 'asset',
                'depreciation_group_code' => 'K2',
                'is_active' => true,
            ],
            [
                'code' => '200.08',
                'name' => 'INVENTARIS KOMPUTER',
                'type' => 'asset',
                'depreciation_group_code' => 'K2',
                'is_active' => true,
            ],
            [
                'code' => '200.09',
                'name' => 'INVENTARIS PERABOTAN',
                'type' => 'asset',
                'depreciation_group_code' => 'K2',
                'is_active' => true,
            ],
            [
                'code' => '200.10',
                'name' => 'INVENTARIS PERLENGKAPAN KANTOR',
                'type' => 'asset',
                'depreciation_group_code' => 'K1',
                'is_active' => true,
            ],
            [
                'code' => '200.11',
                'name' => 'INVENTARIS PERLENGKAPAN DAPUR',
                'type' => 'asset',
                'depreciation_group_code' => 'K1',
                'is_active' => true,
            ],
            [
                'code' => '200.12',
                'name' => 'INVENTARIS LAUNDRY',
                'type' => 'asset',
                'depreciation_group_code' => 'K1',
                'is_active' => true,
            ],
            [
                'code' => '200.13',
                'name' => 'INVENTARIS GORDYN',
                'type' => 'asset',
                'depreciation_group_code' => 'K1',
                'is_active' => true,
            ],
            [
                'code' => '200.14',
                'name' => 'PERALATAN LAINNYA',
                'type' => 'asset',
                'depreciation_group_code' => 'K2',
                'is_active' => true,
            ],

            // Biaya (bukan aset tetap)
            [
                'code' => '800.022',
                'name' => 'BY. OKSIGEN,UDARA TRKAN DAN N2O',
                'type' => 'inventory',
                'depreciation_group_code' => null,
                'is_active' => true,
            ],
            [
                'code' => '800.031',
                'name' => 'BY. MAKAN PASIEN',
                'type' => 'inventory',
                'depreciation_group_code' => null,
                'is_active' => true,
            ],
            [
                'code' => '800.055',
                'name' => 'BY. SUPPLIES ADMINISTRASI',
                'type' => 'inventory',
                'depreciation_group_code' => null,
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['code' => $category['code']],
                $category
            );
        }
    }
}
