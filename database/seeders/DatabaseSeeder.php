<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Persediaan\Uom;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            LaratrustSeeder::class,
            TransactionTypeSeeder::class,
            ConfigurationSeeder::class,
            SupplierSeeder::class,
            StockCodeSeeder::class,
            BranchSeeder::class,
            WarehouseSeeder::class,
            DepreciationGroupSeeder::class,
            CategorySeeder::class,
            UomSeeder::class,
            ItemSeeder::class,
        ]);
    }
}
