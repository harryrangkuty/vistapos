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
        Schema::create('persediaan_mutasi', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kode', 20);
            $table->unsignedInteger('gudang_asal_id');
            $table->unsignedInteger('gudang_tujuan_id');
            $table->string('nomor_invoice', 32);
            $table->char('status')->default("O"); // [O] Open | [F] Finished
            $table->unsignedInteger('editor_id');
            $table->string('notes')->nullable();
            $table->timestamps(); // created_at adalah tanggal mutasi
            $table->softDeletes();
            $table->string('delete_message')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('persediaan_mutasi');
    }
};
