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
        // DAFTAR KATEGORI IIS
        Schema::create('iis_categories_list', function (Blueprint $table) {
            $table->string('category_name')->primary();
            $table->unsignedInteger('category_number');
        });

        // DAFTAR INVENTARIS IIS
        Schema::create('iis_inventories_list', function (Blueprint $table) {
            $table->string('barcode_no', 50)->primary();
            $table->string('category_name');
            $table->string('description');
            $table->unsignedInteger('transaction_type_id');
            $table->unsignedInteger('registered_by');
            $table->dateTime('book_date');
            $table->unsignedBigInteger('asset_number'); //NUP
            $table->tinyInteger('condition');

            // Nilai keuangan
            $table->double('acquisition_cost')->default(0);
            $table->double('accumulated_depreciation')->default(0);
            $table->double('book_value')->default(0);

            // Distribusi Aset
            $table->string('room_type', 3)->nullable(); // DBR DBL
            $table->unsignedInteger('room_id')->nullable();
            $table->unsignedBigInteger('utilized_by')->nullable(); // user id
            $table->string('utilization_note')->nullable();        // keterangan lokasi/pemakai

            // Penghapusan aset
            $table->unsignedInteger('disposal_id')->nullable();
            $table->unsignedInteger('disposal_type_id')->nullable();
            $table->dateTime('disposal_date')->nullable();

            $table->boolean('is_deactivated')->default(0);
            $table->string('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('iis_inventories_list');
        Schema::dropIfExists('iis_categories_list');
    }
};
