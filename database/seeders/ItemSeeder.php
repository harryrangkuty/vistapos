<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\MasterData\Item;
use Illuminate\Support\Facades\Log;
use App\Models\MasterData\Uom;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('data/master_items.xlsx');
        $data = Excel::toArray([], $path);

        // Gunakan sheet pertama secara eksplisit
        $rows = $data[0];
        array_shift($rows); // hapus header

        $count = 0;

        foreach ($rows as $index => $row) {

            if (!isset($row[0]) || trim((string) $row[0]) === '') continue;

            try {
                $uomCode = strtoupper(trim((string)($row[2] ?? ''))); // ubah ke uppercase
                $uomExists = Uom::where('code', $uomCode)->exists(); // cek apakah ada di DB

                Item::updateOrCreate(
                    ['code' => trim((string)$row[0])],
                    [
                        'name'          => trim((string)$row[1]),
                        'uom_code' => $uomExists ? $uomCode : null,
                        'stock_code'    => trim((string)$row[3]),
                        'category_code' => trim((string)$row[4]),
                        'editor_id'     => 1,
                        'is_active'     => true,
                    ]
                );
                $count++;
            } catch (\Throwable $th) {
                Log::error("❌ Gagal import baris ke-" . ($index + 1) . ": " . $th->getMessage());
            }
        }
    }
}
