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
        Schema::table('aset_profil', function (Blueprint $table) {
            $table->double('nilai_perolehan')->after('kondisi');
        });

        Schema::create('aset_penyusutan', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('profil_id');
            $table->unsignedInteger('editor_id');
            $table->unsignedInteger('satker_id');
            $table->string('jenis_transaksi_id');
            $table->string('kategori_id', 10);
            $table->string('kategori_nama');
            $table->unsignedBigInteger('nup');
            $table->double('nilai');
            $table->string('komptabel');
            $table->dateTime('tgl_penyusutan');
            $table->unsignedTinyInteger('bulan');
            $table->year('tahun');
        });

        // Schema::create('penyusutan', function (Blueprint $table) {
        //     $table->increments('id');
        //     $table->string('kode_lokasi', 20);
        //     $table->string('kategori_id', 10);
        //     $table->string('kategori_nama');
        //     $table->string('no_sppa')->nullable();
        //     $table->string('jenis_transaksi', 3)->nullable();
        //     $table->bigInteger('nup');
        //     $table->string('tipe', 15);
        //     $table->double('nilai');
        //     $table->string('jenis_susut', 1);
        //     $table->string('flag_sap', 1);
        //     $table->string('flag_beban', 1)->nullable();
        //     $table->bigInteger('created_unix')->nullable();
        //     $table->timestamps(); # created_at = tanggal penyusutan
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('aset_profil', function (Blueprint $table) {
            $table->dropColumn('nilai_perolehan');
        });

        Schema::dropIfExists('aset_penyusutan');
    }
};
