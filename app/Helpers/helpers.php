<?php

use Illuminate\Support\Str;

if (!function_exists('check_user_permissions')) {

    function check_user_permissions($user, $path, $exclude = [], $request = null)
    {
        list($path) = explode('/read', $path);

        if ($user && $user?->activeRole?->hasPermission('sudo')) {
            return true;
        }

        if ($user && $user?->activeRole?->hasPermission($path)) {
            return true;
        }

        if (Str::startsWith($path, $exclude)) {
            return true;
        }

        return false;
    }
}

if (!function_exists('menu_list')) {
    function menu_list($user = null)
    {
        $menu = [
            // ["key" => null, "icon" => null, "title" => "Monitoring", "url" => null],
            // ["key" => "monitoring", "icon" => "line-md:speed-loop", "title" => "Monitoring", "url" => "monitoring"],
            ["key" => null, "icon" => null, "title" => "Pengadaan Barang", "url" => null],
            ["key" => "procurements/request", "icon" => "line-md:downloading-loop", "title" => "Request Pengadaan", "url" => "procurements/request"],
            ["key" => "procurements", "icon" => "line-md:clipboard-plus", "title" => "Pengadaan Barang", "url" => "procurements"],
            ["key" => null, "icon" => null, "title" => "Aset Tetap", "url" => null],
            ["key" => "asset/profile", "icon" => "line-md:text-box", "title" => "Daftar Aset", "url" => "asset/profile"],
            ["key" => "asset/search", "icon" => "line-md:search", "title" => "Cari Aset", "url" => "asset/search"],
            ["key" => "asset/distribution", "icon" => "line-md:turn-sharp-left", "title" => "Distribusi Aset", "url" => "asset/distribution"],
            ["key" => "asset/disposal", "icon" => "line-md:clipboard-minus", "title" => "Penghapusan Aset", "url" => "asset/disposal"],
            [
                "key" => "report/asset",
                "icon" => "line-md:text-box-multiple",
                "title" => "Laporan Aset",
                "url" => null,
                "submenu" => [
                    ["key" => "report/asset/transaction", "icon" => "line-md:text-box", "title" => "Laporan Transaksi Aset", "url" => "report/asset/transaction"],
                ],
            ],
            ["key" => null, "icon" => null, "title" => "Persediaan Habis Pakai", "url" => null],
            ["key" => "inventory/data", "icon" => "streamline-ultimate:warehouse-cart-package-ribbon-bold", "title" => "Daftar Stock Persediaan", "url" => "inventory/data"],
            ["key" => "inventory/out", "icon" => "line-md:log-out", "title" => "Persediaan Keluar", "url" => "inventory/keluar"],
            ["key" => "inventory/mutation", "icon" => "line-md:arrows-horizontal", "title" => "Persediaan Mutasi", "url" => "inventory/mutation"],
            [
                "key" => "report/inventory",
                "icon" => "line-md:text-box-multiple",
                "title" => "Laporan Persediaan",
                "url" => null,
                "submenu" => [
                    ["key" => "report/inventoy/transaction", "icon" => "line-md:text-box", "title" => "Laporan Transaksi Persediaan", "url" => "report/inventoy/transaction"],
                ],
            ],
            ["key" => null, "icon" => null, "title" => "IIS Data", "url" => null],
            ["key" => "iis/daftar-inventaris", "icon" => "icon-park-outline:adjacent-item", "title" => "Daftar Inventaris", "url" => "iis/daftar-inventaris"],
            ["key" => "iis/daftar-kategori", "icon" => "line-md:iconify2-static", "title" => "Daftar Kategori", "url" => "iis/daftar-kategori"],
            ["key" => null, "icon" => null, "title" => "Master Data", "url" => null],
            ["key" => "branches", "icon" => "icon-park-outline:branch-one", "title" => "Manajemen Branch", "url" => "branches"],
            ["key" => "warehouses", "icon" => "mdi:warehouse", "title" => "Manajemen Gudang", "url" => "warehouses"],
            ["key" => "transaction-types", "icon" => "tabler:transaction-dollar", "title" => "Manajemen Jenis Transaksi", "url" => "transaction-types"],
            ["key" => "stock-codes", "icon" => "line-md:clipboard-list", "title" => "Manajemen Kode Stok", "url" => "stock-codes"],
            ["key" => "categories", "icon" => "line-md:grid-3-twotone", "title" => "Manajemen Kategori", "url" => "categories"],
            ["key" => "depreciation-groups", "icon" => "line-md:speed-loop", "title" => "Kelompok Penyusutan", "url" => "depreciation-groups"],
            ["key" => "uoms", "icon" => "line-md:brake-abs-twotone", "title" => "Manajemen Satuan", "url" => "uoms"],
            ["key" => "items", "icon" => "line-md:list-3-filled", "title" => "Manajemen Master Item", "url" => "items"],
            ["key" => "suppliers", "icon" => "line-md:phone-call-loop", "title" => "Manajemen Supplier", "url" => "suppliers"],
            ["key" => "rooms", "icon" => "fluent:conference-room-20-regular", "title" => "Manajemen Ruang", "url" => "rooms"],
            ["key" => null, "icon" => null, "title" => "Administrator", "url" => null],
            ["key" => "users", "icon" => "line-md:account", "title" => "Manajemen User", "url" => "users"],
            ["key" => "roles", "icon" => "line-md:file-document-cancel", "title" => "Manajemen Role", "url" => "roles"],
            ["key" => "permissions", "icon" => "line-md:watch", "title" => "Manajemen Permission", "url" => "permissions"],
            ["key" => "configurations", "icon" => "line-md:cog", "title" => "Konfigurasi Sistem", "url" => "configurations"],
            [
                "key" => "laporan",
                "icon" => "line-md:text-box-multiple",
                "title" => "Laporan",
                "url" => null,
                "submenu" => [
                    ["key" => "laporan/neraca", "icon" => "line-md:text-box", "title" => "Laporan Neraca", "url" => "laporan/neraca"],
                    ["key" => "laporan/kinerja", "icon" => "line-md:text-box", "title" => "Laporan Kinerja", "url" => "laporan/kinerja"],
                ],
            ],
        ];

        // filter menu berdasarkan permission user
        if ($user) {
            $menu = collect($menu)->map(function ($item) use ($user) {
                // SUBMENU
                if (isset($item['submenu'])) {
                    $item['submenu'] = collect($item['submenu'])->filter(fn($child) => check_user_permissions($user, $child['url']))->values()->all();
                    return count($item['submenu']) ? $item : null;
                }
                // MENU
                if (isset($item['url']) && $item['url']) {
                    return check_user_permissions($user, $item['url']) ? $item : null;
                }

                return $item;
            })->filter()->values();

            // hapus header yg tidak ada item
            $menu = $menu->filter(function ($item, $i) use ($menu) {
                if (is_null($item['key'])) {
                    // kalau setelahnya ga ada item non-header, buang
                    $next = $menu->slice($i + 1)->firstWhere('key', '!=', null);
                    return $next !== null;
                }
                return true;
            })->values()->all();
        }

        return $menu;
    }
}

if (!function_exists('setting')) {
    function setting($key, $default = null)
    {

        if (isset($value)) {
            return $value;
        }

        if ($default)
            return $default;

        return config('setting.' . $key);
    }
}

if (!function_exists('stringToArray')) {
    function stringToArray($string)
    {
        // Remove whole spaces
        $string = str_replace(' ', '', $string);

        // Split string by comma (,)
        $array_by_comma = explode(',', $string);

        // Set $array as the return array
        $array = $array_by_comma;

        // Each elements in array $array
        foreach ($array as $key => $value) {

            // Check if contains minus (-)
            if (str_contains($value, '-')) {

                // Remove elements by $value from array $array
                $array = array_diff($array, [$value]);

                // Split string from $value by minus (-)
                $array_by_minus = explode('-', $value);

                // If first element greater than second element then return
                if ($array_by_minus[0] > $array_by_minus[1]) {
                    abort(403, "$value (Parameter kedua lebih kecil dari parameter pertama)");
                }

                // Create array with elements from $array_by_minus[0] to $array_by_minus[1]
                $range = range($array_by_minus[0], $array_by_minus[1]);

                // Merge array in $range to $array_by_comma
                $array = array_merge($array, $range);
            }
        }

        // Convert elements in $array from string to integer
        $array = array_map('intval', $array);

        // Return converted string to array
        return $array;
    }
}

if (!function_exists('checkDiff')) {
    function checkDiff($array_id, $response)
    {
        // Array add to collection
        $array_id = collect($array_id);

        // Keys of collection $response become value of ids
        $response_id = $response->keyBy('id')->keys();

        // Elements from $array_id those not inside $response_id
        $diff = $array_id->diff($response_id);

        // If elements from $array_id those not inside $response_id exist
        if ($diff->isNotEmpty()) {

            // Convert collection to array then to string
            $diff = implode(', ', $diff->toArray());
            abort(403, "$diff tidak terdaftar");
        } else {

            // Return $response if all ids exist
            return response()->json(response()->json($response));
        }
    }
}

if (!function_exists('idCurrency')) {
    function idCurrency($number)
    {
        if (!is_numeric($number))
            return $number;

        $number = str_replace('.', '#', $number);
        $number = preg_replace('/(\d)(?=(\d{3})+(?!\d))/', '$1.', $number);
        $number = str_replace('#', ',', $number);
        return $number;
    }
}

if (!function_exists('auto_nup_generator')) {
    function auto_nup_generator()
    {
        $last = \App\Models\Asset\AssetProfile::orderBy('asset_number', 'DESC')->first();

        if (!$last || !$last->asset_number) {
            return '0001';
        }

        // ambil angka dari asset_number
        $num = intval($last->asset_number) + 1;

        // selalu jadi 4 digit
        return str_pad($num, 4, '0', STR_PAD_LEFT);
    }
}
