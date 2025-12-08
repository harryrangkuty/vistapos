<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Aset\AssetProfile;
use App\Models\Aset\AsetPenyusutan;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('aset_profil', function (Blueprint $table) {
            $table->double('nilai_penyusutan')->after('nilai_perolehan')->default(0);
        });

        //FIX
        $exist = AssetProfile::all();
        foreach ($exist as $ex) {
            $peny = AsetPenyusutan::getPenyusutanAwal($ex->kategori_id, $ex->nilai_perolehan, $ex->tgl_buku, $ex->tgl_perolehan);
            $ex->akumulasi_penyusutan = $peny->akum;
            $ex->nilai_buku = $ex->nilai_perolehan - $peny->akum;
            $ex->nilai_penyusutan = $peny->single;
            $ex->save();

            $awal = AsetPenyusutan::where('profil_id', $ex->id)->where('jenis_transaksi_id', 'S01')->first();
            $awal->nilai = $peny->akum;
            $awal->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('aset_profil', function (Blueprint $table) {
            $table->dropColumn('nilai_penyusutan');
        });
    }
};
