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
        Schema::table('kdp_profil', function (Blueprint $table) {
            $table->tinyInteger('status_penyelesaian')->after('status')->nullable();
            $table->unsignedInteger('penyelesaian_id')->after('status_penyelesaian')->nullable();
        });

        Schema::table('kdp_transaksi', function (Blueprint $table) {
            $table->string('no_kontrak')->after('nilai')->nullable();
            $table->string('kontraktor')->after('no_kontrak')->nullable();
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
