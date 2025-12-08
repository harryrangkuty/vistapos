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
        Schema::table('aset_distribusi', function (Blueprint $table) {
            $table->string('deskripsi')->after('profil_id');
            $table->unsignedInteger('satker_id')->after('deskripsi');
            $table->string('code')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('aset_distribusi', function (Blueprint $table) {
            $table->dropColumn('deskripsi');
            $table->dropColumn('satker_id');
            $table->dropColumn('code');
        });
    }
};
