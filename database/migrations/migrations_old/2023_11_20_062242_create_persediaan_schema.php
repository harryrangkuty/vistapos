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
        Schema::create('persediaan_gudang', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama');
            $table->string('kode');
            $table->boolean('status_keluar')->default(true);
            $table->boolean('status_masuk')->default(true);
            $table->json('users_id')->nullable();
            $table->unsignedInteger('editor_id');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('persediaan_suplier', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama');
            $table->string('alamat');
            $table->string('telp', 16)->nullable();
            $table->string('email')->nullable();
            $table->string('kontak_nama')->nullable();
            $table->string('kontak_telp', 16)->nullable();
            $table->unsignedInteger('editor_id');
            $table->timestamps();
            $table->softDeletes(); 
        });

        Schema::create('persediaan_barang', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kategori_id', 10);
            $table->string('kategori_nama');
            $table->string('nama');
            $table->string('satuan_id')->nullable();
            $table->string('satuan_nama')->nullable();
            $table->string('notes')->nullable();
            $table->double('stok_minimum')->default(0);
            $table->double('stok_maksimum')->nullable();
            $table->boolean('status_keluar')->default(true);
            $table->boolean('status_masuk')->default(true);
            $table->json('etc')->nullable();
            $table->unsignedInteger('editor_id');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('persediaan_masuk', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kode', 20); // LP
            $table->string('jenis_transaksi_id');
            $table->unsignedInteger('order_id')->nullable();
            $table->unsignedInteger('suplier_id')->nullable();
            $table->string('nomor_invoice', 32);
            $table->date('tanggal_invoice');
            $table->unsignedInteger('gudang_id')->nullable();
            $table->char('status')->default("O"); // [O] Open | [F] Finished
            $table->unsignedInteger('editor_id');
            $table->string('notes')->nullable();
            $table->timestamps(); // created_at adalah tanggal penerimaan
            $table->softDeletes();
            $table->string('delete_message')->nullable();
        });

        Schema::create('persediaan_transaksi', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('reference_id');
            $table->string('reference_type');
            $table->unsignedInteger('barang_id');
            $table->string('barang_nama');
            $table->string('kategori_id');
            $table->string('kategori_nama');
            $table->string('jenis_transaksi_id');
            $table->string('jenis_transaksi_nama');
            $table->unsignedInteger('gudang_id');
            $table->string('gudang_nama');
            $table->double('kuantitas');
            $table->double('harga')->default(0);
            $table->double('stock_before')->nullable();
            $table->boolean('is_completed')->default(0);
            $table->boolean('habis')->default(0);
            $table->unsignedInteger('reference_in')->nullable();
            $table->timestamps();
        });

        Schema::create('persediaan_keluar', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kode', 20); // LP
            $table->string('jenis_transaksi_id');
            $table->unsignedInteger('gudang_id')->nullable();
            $table->string('konsumen')->nullable();
            $table->char('status')->default("O"); // [O] Open | [F] Finished
            $table->unsignedInteger('editor_id');
            $table->string('notes')->nullable();
            $table->timestamps(); // created_at adalah tanggal penerimaan
            $table->softDeletes();
            $table->string('delete_message')->nullable();
        });

        // Schema::create('persediaan_masuk_order', function (Blueprint $table) {
            
        // });

        // Schema::create('persediaan_masuk_order_detail', function (Blueprint $table) {
        //     $table->bigIncrements('id');
        //     $table->unsignedInteger('order_id');
        //     $table->unsignedInteger('inventory_id');
        //     $table->string('inventory_name');
        //     $table->double('qty');
        //     $table->double('price')->default(0);
        //     $table->unsignedInteger('container_id');
        //     $table->string('container_name');
        //     $table->timestamps();

        //     $table->index('order_id');
        //     $table->index('inventory_id');
        // });

        // Schema::create('persediaan_mutasi', function (Blueprint $table) {
            
        // });

        // Schema::create('persediaan_mutasi_detail', function (Blueprint $table) {
            
        // });

        // Schema::create('persediaan_koreksi', function (Blueprint $table) {
            
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('persediaan_gudang');
        Schema::dropIfExists('persediaan_suplier');
        Schema::dropIfExists('persediaan_barang');
        Schema::dropIfExists('persediaan_masuk');
        Schema::dropIfExists('persediaan_transaksi');
    }
};
