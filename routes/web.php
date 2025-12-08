<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SetTahunKerjaController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\LookUpController;

use App\Http\Controllers\Administrator\UserController;
use App\Http\Controllers\Administrator\PermissionController;
use App\Http\Controllers\Administrator\RoleController;
use App\Http\Controllers\Administrator\ConfigurationController;

use App\Http\Controllers\MasterData\BranchController;
use App\Http\Controllers\MasterData\WarehouseController;
use App\Http\Controllers\MasterData\SupplierController;
use App\Http\Controllers\MasterData\TransactionTypeController;
use App\Http\Controllers\MasterData\CategoryController;
use App\Http\Controllers\MasterData\DepreciationGroupController;
use App\Http\Controllers\MasterData\StockCodeController;
use App\Http\Controllers\MasterData\UomController;
use App\Http\Controllers\MasterData\ItemController;

use App\Http\Controllers\Procurement\ProcurementController;
use App\Http\Controllers\Procurement\ProcurementRequestController;

use App\Http\Controllers\Asset\AssetProfileController;
use App\Http\Controllers\Asset\AsetPenghapusanController;
use App\Http\Controllers\Asset\AsetRuangController;
use App\Http\Controllers\Asset\AsetCariController;

use App\Http\Controllers\Inventory\GudangController;
use App\Http\Controllers\Inventory\BarangController;
use App\Http\Controllers\Inventory\PersediaanOrderMasukController;
use App\Http\Controllers\Inventory\PersediaanMasukController;
use App\Http\Controllers\Inventory\PersediaanOrderMutasiController;
use App\Http\Controllers\Inventory\PersediaanMutasiController;
use App\Http\Controllers\Inventory\PersediaanKeluarController;

use App\Http\Controllers\Report\BmnOldController;
use App\Http\Controllers\Report\BmnController;
use App\Http\Controllers\Report\TransaksiController;
use App\Http\Controllers\Report\HistoryController;
use App\Http\Controllers\Report\PenyusutanController;
use App\Http\Controllers\Report\NeracaController;
use App\Http\Controllers\Report\KdpReportController;
use App\Http\Controllers\Report\KdpReportTransaksiController;
use App\Http\Controllers\Report\KinerjaController;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/api/check-ip', [ApiController::class, 'check_ip'])->name('api.check_ip');

Route::get('/profil/access', [AssetProfileController::class, 'profil'])->name('access.profil');
Route::get('/profil/access', [AssetProfileController::class, 'profil'])->name('access.profil');
Route::get('/list-dbr', [AsetRuangController::class, 'list_dbr'])->name('list-dbr');
Route::get('/test', [TestController::class, '__invoke'])->name('test');

// GUEST
Route::middleware(['guest'])->group(function () {
    Route::view('/', 'layouts.landing')->name('landing');
    // Login
    Route::post('/login', [AuthController::class, 'authenticate'])->name('auth.authenticate');
});

// AUTHENTICATED USER
Route::middleware(['auth', 'check-permissions'])->group(function () {
    //DEFAULT MENU
    Route::get('/dashboard', [HomeController::class, '__invoke'])->name('dashboard');
    Route::get('/dashboard/read', [HomeController::class, 'read'])->name('dashboard.read');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/profile', [ProfilController::class, '__invoke'])->name('profile');
    Route::post('/profile/write', [ProfilController::class, 'write'])->name('profil.write');

    // SET TAHUN BERJALAN SETELAH LOGIN
    Route::post('/set-tahun-kerja/write', [SetTahunKerjaController::class, 'write'])->name('set-tahun-kerja.write');

    // LOOKUP/SEARCH_OPTION/ETC
    Route::prefix('lookups')->group(function () {
        Route::get('/users', [LookupController::class, 'users'])->name('lookups.users');
        Route::get('/suppliers', [LookupController::class, 'suppliers'])->name('lookups.suppliers');
        Route::get('/items', [LookupController::class, 'items'])->name('lookups.items');
    });

    // MASTER DATA
    Route::get('/branches', [BranchController::class, '__invoke'])->name('branches');
    Route::get('/branches/read', [BranchController::class, 'read'])->name('branches.read');
    Route::post('/branches/write', [BranchController::class, 'write'])->name('branches.write');

    Route::get('/warehouses', [WarehouseController::class, '__invoke'])->name('warehouses');
    Route::get('/warehouses/read', [WarehouseController::class, 'read'])->name('warehouses.read');
    Route::post('/warehouses/write', [WarehouseController::class, 'write'])->name('warehouses.write');

    Route::get('/suppliers', [SupplierController::class, '__invoke'])->name('suppliers');
    Route::get('/suppliers/read', [SupplierController::class, 'read'])->name('suppliers.read');
    Route::post('/suppliers/write', [SupplierController::class, 'write'])->name('suppliers.write');

    Route::get('/transaction-types', [TransactionTypeController::class, '__invoke'])->name('transaction_types');
    Route::get('/transaction-types/read', [TransactionTypeController::class, 'read'])->name('transaction_types.read');
    Route::post('/transaction-types/write', [TransactionTypeController::class, 'write'])->name('transaction_types.write');

    Route::get('/stock-codes', [StockCodeController::class, '__invoke'])->name('stock_codes');
    Route::get('/stock-codes/read', [StockCodeController::class, 'read'])->name('stock_codes.read');
    Route::post('/stock-codes/write', [StockCodeController::class, 'write'])->name('stock_codes.write');

    Route::get('/categories', [CategoryController::class, '__invoke'])->name('categories');
    Route::get('/categories/read', [CategoryController::class, 'read'])->name('categories.read');
    Route::post('/categories/write', [CategoryController::class, 'write'])->name('categories.write');

    Route::get('/depreciation-groups', [DepreciationGroupController::class, '__invoke'])->name('depreciation_groups');
    Route::get('/depreciation-groups/read', [DepreciationGroupController::class, 'read'])->name('depreciation_groups.read');
    Route::post('/depreciation-groups/write', [DepreciationGroupController::class, 'write'])->name('depreciation_groups.write');

    Route::get('/uoms', [UomController::class, '__invoke'])->name('uoms');
    Route::get('/uoms/read', [UomController::class, 'read'])->name('uoms.read');
    Route::post('/uoms/write', [UomController::class, 'write'])->name('uoms.write');


    Route::get('/items', [ItemController::class, '__invoke'])->name('items');
    Route::get('/items/read', [ItemController::class, 'read'])->name('items.read');
    Route::post('/items/write', [ItemController::class, 'write'])->name('items.write');


    //MENU ADMINISTRATOR
    Route::get('/users', [UserController::class, '__invoke'])->name('users');
    Route::get('/users/read', [UserController::class, 'read'])->name('users.read');
    Route::post('/users/write', [UserController::class, 'write'])->name('users.write');

    Route::get('/permissions', [PermissionController::class, '__invoke'])->name('permissions');
    Route::get('/permissions/read', [PermissionController::class, 'read'])->name('permissions.read');
    Route::post('/permissions/write', [PermissionController::class, 'write'])->name('permissions.write');

    Route::get('/roles', [RoleController::class, '__invoke'])->name('roles');
    Route::get('/roles/read', [RoleController::class, 'read'])->name('roles.read');
    Route::post('/roles/write', [RoleController::class, 'write'])->name('roles.write');

    Route::get('/configurations', [ConfigurationController::class, '__invoke'])->name('configurations');
    Route::get('/configurations/read', [ConfigurationController::class, 'read'])->name('configurations.read');
    Route::post('/configurations/write', [ConfigurationController::class, 'write'])->name('configurations.write');

    //KATEGORI ASET
    Route::withoutMiddleware('check-permissions')->group(function () {
        Route::get('/kategori/list-kategori', [CategoryController::class, 'list_kategori'])->name('list-kategori');
        Route::get('/kategori/search-kategori', [CategoryController::class, 'search_kategori'])->name('search-kategori');
        Route::get('/kategori/jenjang-kategori', [CategoryController::class, 'jenjang_kategori'])->name('jenjang_kategori');
    });

    //MODUL PENGADAAN
    Route::prefix('procurements')->group(function () {
        Route::get('/', [ProcurementController::class, '__invoke'])->name('procurements');
        Route::get('/read', [ProcurementController::class, 'read'])->name('procurements.read');
        Route::post('/write', [ProcurementController::class, 'write'])->name('procurements.write');

        Route::get('/request', [ProcurementRequestController::class, '__invoke'])->name('procurements.request');
        Route::get('/request/read', [ProcurementRequestController::class, 'read'])->name('procurements.request.read');
        Route::post('/request/write', [ProcurementRequestController::class, 'write'])->name('procurements.request.write');
    });

    //MODUL ASET
    Route::prefix('asset')->middleware('check-mode')->group(function () {
        Route::get('/profil', [AssetProfileController::class, '__invoke'])->name('aset.profil');
        Route::get('/profil/read', [AssetProfileController::class, 'read'])->name('aset.profil.read');
        Route::post('/profil/write', [AssetProfileController::class, 'write'])->name('aset.profil.write');

        Route::get('/penghapusan', [AsetPenghapusanController::class, '__invoke'])->name('aset.penghapusan');
        Route::get('/penghapusan/read', [AsetPenghapusanController::class, 'read'])->name('aset.penghapusan.read');
        Route::post('/penghapusan/write', [AsetPenghapusanController::class, 'write'])->name('aset.penghapusan.write');

        Route::get('/ruang', [AsetRuangController::class, '__invoke'])->name('aset.ruang');
        Route::get('/ruang/read', [AsetRuangController::class, 'read'])->name('aset.ruang.read');
        Route::post('/ruang/write', [AsetRuangController::class, 'write'])->name('aset.ruang.write');

        Route::get('/cari', [AsetCariController::class, '__invoke'])->name('aset.cari');
        Route::get('/cari/read', [AsetCariController::class, 'read'])->name('aset.cari.read');
    });

    //MENU PERSEDIAAN
    Route::prefix('persediaan')->middleware('check-mode')->group(function () {
        Route::get('/gudang', [GudangController::class, '__invoke'])->name('persediaan.gudang');
        Route::get('/gudang/read', [GudangController::class, 'read'])->name('persediaan.gudang.read');
        Route::post('/gudang/write', [GudangController::class, 'write'])->name('persediaan.gudang.write');

        Route::get('/barang', [BarangController::class, '__invoke'])->name('persediaan.barang');
        Route::get('/barang/read', [BarangController::class, 'read'])->name('persediaan.barang.read');
        Route::post('/barang/write', [BarangController::class, 'write'])->name('persediaan.barang.write');

        Route::get('/order-masuk', [PersediaanOrderMasukController::class, '__invoke'])->name('persediaan.order-masuk');
        Route::get('/order-masuk/read', [PersediaanOrderMasukController::class, 'read'])->name('persediaan.order-masuk.read');
        Route::post('/order-masuk/write', [PersediaanOrderMasukController::class, 'write'])->name('persediaan.order-masuk.write');

        Route::get('/masuk', [PersediaanMasukController::class, '__invoke'])->name('persediaan.masuk');
        Route::get('/masuk/read', [PersediaanMasukController::class, 'read'])->name('persediaan.masuk.read');
        Route::post('/masuk/write', [PersediaanMasukController::class, 'write'])->name('persediaan.masuk.write');

        Route::get('/order-mutasi', [PersediaanOrderMutasiController::class, '__invoke'])->name('persediaan.order-mutasi');
        Route::get('/order-mutasi/read', [PersediaanOrderMutasiController::class, 'read'])->name('persediaan.order-mutasi.read');
        Route::post('/order-mutasi/write', [PersediaanOrderMutasiController::class, 'write'])->name('persediaan.order-mutasi.write');

        Route::get('/mutasi', [PersediaanMutasiController::class, '__invoke'])->name('persediaan.mutasi');
        Route::get('/mutasi/read', [PersediaanMutasiController::class, 'read'])->name('persediaan.mutasi.read');
        Route::post('/mutasi/write', [PersediaanMutasiController::class, 'write'])->name('persediaan.mutasi.write');

        Route::get('/keluar', [PersediaanKeluarController::class, '__invoke'])->name('persediaan.keluar');
        Route::get('/keluar/read', [PersediaanKeluarController::class, 'read'])->name('persediaan.keluar.read');
        Route::post('/keluar/write', [PersediaanKeluarController::class, 'write'])->name('persediaan.keluar.write');

        Route::get('/koreksi', [PersediaanKoreksiController::class, '__invoke'])->name('persediaan.koreksi');
        Route::get('/koreksi/read', [PersediaanKoreksiController::class, 'read'])->name('persediaan.koreksi.read');
        Route::post('/koreksi/write', [PersediaanKoreksiController::class, 'write'])->name('persediaan.koreksi.write');
    });


    //MENU MONITORING
    Route::get('/monitoring', [MonitoringController::class, '__invoke'])->name('monitoring');
    Route::get('/monitoring/read', [MonitoringController::class, 'read'])->name('monitoring.read');

    //MENU LAPORAN
    Route::prefix('laporan')->middleware('check-mode')->group(function () {

        // LAPORAN ASET
        Route::get('/bmn', [BmnController::class, '__invoke'])->name('laporan.bmn');
        Route::get('/bmn/report', [BmnController::class, 'report'])->name('laporan.bmn.report');

        Route::get('/transaksi', [TransaksiController::class, '__invoke'])->name('laporan.transaksi');
        Route::get('/transaksi/read', [TransaksiController::class, 'read'])->name('laporan.transaksi.read');
        Route::get('/transaksi/report', [TransaksiController::class, 'report'])->name('laporan.transaksi.report');

        // LAPORAN  KDP
        Route::get('/kdp', [KdpReportController::class, '__invoke'])->name('laporan.kdp');
        Route::get('/kdp/report', [KdpReportController::class, 'report'])->name('laporan.kdp.report');

        Route::get('/kdp/transaksi', [KdpReportTransaksiController::class, '__invoke'])->name('laporan.kdp.transaksi');
        Route::get('/kdp/transaksi/read', [KdpReportTransaksiController::class, 'read'])->name('laporan.kdp.transaksi.read');
        Route::get('/kdp/transaksi/report', [KdpReportTransaksiController::class, 'report'])->name('laporan.kdp.transaksi.report');

        // OLD
        Route::get('/history', [HistoryController::class, '__invoke'])->name('laporan.history');
        Route::get('/history/report', [HistoryController::class, 'report'])->name('laporan.history.report');

        Route::get('/bmn-old', [BmnOldController::class, '__invoke'])->name('laporan.bmn.old');
        Route::get('/bmn-old/report', [BmnOldController::class, 'report'])->name('laporan.bmn.old.report');

        Route::get('/penyusutan', [PenyusutanController::class, '__invoke'])->name('laporan.penyusutan');
        Route::get('/penyusutan/report', [PenyusutanController::class, 'report'])->name('laporan.penyusutan.report');

        Route::get('/neraca', [NeracaController::class, '__invoke'])->name('laporan.neraca');
        Route::get('/neraca/report', [NeracaController::class, 'report'])->name('laporan.neraca.report');
        Route::get('/neraca/generate', [NeracaController::class, 'generate'])->name('laporan.neraca.generate');

        // LAPORAN KINERJA
        Route::get('/kinerja', [KinerjaController::class, '__invoke'])->name('laporan.kinerja');
        Route::get('/kinerja/report', [KinerjaController::class, 'report'])->name('laporan.kinerja.report');
    });

    // CHANGELOG
    Route::prefix('panduan')->group(function () {
        Route::get('/', [ChangeLogController::class, '__invoke'])->name('panduan');
        Route::get('/read', [ChangeLogController::class, 'read'])->name('panduan.read');
        Route::post('/write', [ChangeLogController::class, 'write'])->name('panduan.write');
    });

    Route::fallback(function () {
        $vue = "<not-found/>";
        return response()->view('layouts.antd', compact('vue'), 404);
    });
});
