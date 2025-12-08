<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //
        Schema::table('kdp_profil', function (Blueprint $table) {
            $table->dropColumn('tgl_buku');
            $table->dropColumn('nilai_perolehan_awal');
            $table->dropColumn('nilai_perkiraan_akhir');
            $table->dropColumn('akumulasi_penyusutan');
            $table->dropColumn('henti_guna');
            $table->unsignedInteger('aset_profil_id')->nullable();
            $table->double('nilai_kontrak');
            $table->string('no_kontrak')->nullable();
            $table->string('nama_kontrak')->nullable();
            $table->string('alamat_kontrak')->nullable();
            $table->unsignedInteger('persen')->default(0);
        });

        Schema::table('kdp_transaksi', function (Blueprint $table) {
            $table->dropColumn('reference_id');
            $table->renameColumn('reference_type', 'type');
            $table->renameColumn('kontraktor', 'nama_kontrak');
            $table->string('alamat_kontrak')->nullable();
            $table->unsignedInteger('persen')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
