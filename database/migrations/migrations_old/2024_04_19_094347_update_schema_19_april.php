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
            $table->unsignedTinyInteger('masa_manfaat')->nullable()->change();
         });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('aset_profil', function (Blueprint $table) {
            $table->unsignedTinyInteger('masa_manfaat')->change();
        });
    }
};
