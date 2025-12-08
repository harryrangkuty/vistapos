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
        Schema::create('aset_distribusi', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('profil_id');
            $table->string('tipe_ruang', 3)->nullable(); //DBR DBL
            $table->unsignedInteger('ruang_id')->nullable();
            $table->string('ruang_nama')->nullable();
            $table->string('keterangan_lokasi')->nullable();
            $table->text('catatan')->nullable();
            $table->char('pemanfaatan_id', 2)->nullable();
            $table->string('pemanfaatan_nama')->nullable();
            $table->string('pemanfaatan_catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aset_distribusi');
    }
};
