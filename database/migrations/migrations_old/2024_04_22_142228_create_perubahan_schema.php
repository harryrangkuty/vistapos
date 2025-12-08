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
        Schema::rename('aset_distribusi', 'aset_perubahan');

        Schema::table('aset_profil', function (Blueprint $table) {
            $table->json('etc')->nullable();
        });

        Schema::table('aset_perubahan', function (Blueprint $table) {
            $table->string('type');
            $table->tinyInteger('kondisi')->nullable();
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
