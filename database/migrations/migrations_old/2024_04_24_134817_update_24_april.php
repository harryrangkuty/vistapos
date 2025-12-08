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
        Schema::table('aset_perubahan', function (Blueprint $table) {
            $table->string('pemeliharaan_catatan')->nullable()->after('pemanfaatan_catatan');
            $table->double('pemeliharaan_biaya')->nullable()->after('pemeliharaan_catatan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('aset_perubahan', function (Blueprint $table) {
            $table->dropColumn('pemeliharaan_catatan');
            $table->dropColumn('pemeliharaan_biaya');
         });
    }
};
