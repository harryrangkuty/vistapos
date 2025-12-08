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
        Schema::table('aset_perolehan', function (Blueprint $table) {
           $table->string('sumber_dana')->nullable()->change();
        });

        Schema::table('aset_perolehan_detail', function (Blueprint $table) {
            $table->unsignedTinyInteger('masa_manfaat')->nullable();
         });

         Schema::table('aset_profil', function (Blueprint $table) {
            $table->unsignedTinyInteger('masa_manfaat');
         });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('aset_perolehan', function (Blueprint $table) {
            $table->string('sumber_dana')->change();
        });

        Schema::table('aset_perolehan_detail', function (Blueprint $table) {
            $table->dropColumn('masa_manfaat');
         });

         Schema::table('aset_profil', function (Blueprint $table) {
            $table->dropColumn('masa_manfaat');
         });
    }
};
