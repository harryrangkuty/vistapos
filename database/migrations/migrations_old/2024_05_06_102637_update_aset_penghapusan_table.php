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
        Schema::table('aset_penghapusan', function (Blueprint $table) {
            $table->string('kode', 20)->after('id');
            $table->unsignedInteger('jenis_transaksi_id')->after('kode');
            $table->date('tgl_surat')->nullable()->after('satker_id');
            $table->string('no_surat')->nullable()->after('tgl_surat');
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
