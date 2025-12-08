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

        Schema::create('kdp_profil', function (Blueprint $table) {
            $table->increments('id');
            $table->string('deskripsi');
            $table->string('alamat');
            $table->unsignedInteger('tanah_id')->nullable();
            $table->unsignedInteger('jenis_perolehan_id');
            $table->dateTime('tgl_perolehan');
            $table->dateTime('tgl_buku');
            $table->date('tgl_surat')->nullable(); # SURAT
            $table->string('no_surat')->nullable(); # SURAT
            $table->char('status')->default("O"); // [O] Open | [F] Finished
            $table->unsignedInteger('satker_id');
            $table->string('kategori_id', 10);
            $table->string('kategori_nama');
            $table->unsignedBigInteger('nup')->nullable();
            $table->tinyInteger('kondisi');
            $table->double('nilai_perolehan_awal');
            $table->double('nilai_perkiraan_akhir');
            $table->double('nilai_buku');
            $table->double('akumulasi_penyusutan');
            $table->boolean('henti_guna')->default(0);
            $table->unsignedInteger('editor_id');
            $table->unsignedInteger('jenis_penghapusan_id')->nullable();
            $table->dateTime('tgl_penghapusan')->nullable();
            $table->string('notes')->nullable();
            $table->json('etc')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('kdp_transaksi', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('profil_id');
            $table->unsignedInteger('editor_id');
            $table->unsignedInteger('satker_id');
            $table->string('satker_nama');
            $table->string('jenis_transaksi_id');
            $table->string('jenis_transaksi_nama');
            $table->string('kategori_id', 10);
            $table->string('kategori_nama');
            $table->unsignedBigInteger('nup');
            $table->tinyInteger('kondisi')->nullable();
            $table->string('catatan')->nullable();
            $table->double('nilai');
            $table->unsignedInteger('reference_id');
            $table->string('reference_type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kdp_profil');
        Schema::dropIfExists('kdp_transaksi');
    }
};
