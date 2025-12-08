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
        // CONFIGURATIONS
        Schema::create('configurations', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->string('label')->nullable();
            $table->text('value')->nullable();
            $table->text('message')->nullable();
            $table->timestamps();
        });

        //CODE REGISTER
        Schema::create('code_registers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->integer('last_number')->unsigned();
            $table->date('last_date');
        });

        //KODE STOCK
        Schema::create('stock_codes', function (Blueprint $table) {
            $table->string('code', 20)->primary(); // misal 160.04, 160.03
            $table->string('name');                  // misal 'Inventaris', 'Stock Supplier'
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // BRANCHES
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('name');
            $table->string('address')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        //WAREHOUSES
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->string('code', 20)->unique();;
            $table->string('name')->index();
            $table->string('address')->nullable();
            $table->text('description')->nullable();
            $table->boolean('can_receive')->default(true);
            $table->boolean('can_dispatch')->default(true);
            $table->unsignedInteger('person_in_charge_id');
            $table->unsignedInteger('editor_id');
            $table->timestamps();
            $table->softDeletes();
        });

        //PIVOT WAREHOUSE STOCK CODE
        Schema::create('warehouse_stock_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->constrained()->cascadeOnDelete();
            $table->string('stock_code', 20);
            $table->foreign('stock_code')->references('code')->on('stock_codes')->cascadeOnDelete();
            $table->timestamps();
        });

        // ROOMS
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name')->index();
            $table->text('description')->nullable();
            $table->unsignedInteger('person_in_charge_id')->nullable();
            $table->unsignedInteger('editor_id');
            $table->boolean('is_lab')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        //DEPRECIATION GROUPS
        Schema::create('depreciation_groups', function (Blueprint $table) {
            $table->string('code', 10)->primary();
            $table->string('name');
            $table->unsignedSmallInteger('lifespan_months');
            $table->enum('method', ['straight_line', 'declining_balance']);
        });

        // CATEGORIES
        Schema::create('categories', function (Blueprint $table) {
            $table->string('code', 10)->primary();
            $table->string('name')->index();
            $table->enum('type', ['asset', 'inventory'])->default('asset');
            $table->string('depreciation_group_code', 10)->nullable();
            $table->boolean("is_active")->default(true);
            $table->foreign('depreciation_group_code')
                ->references('code')->on('depreciation_groups')
                ->nullOnDelete();
        });

        // TRANSACTION TYPES
        Schema::create('transaction_types', function (Blueprint $table) {
            $table->string('code', 5)->primary();
            $table->string('name')->index();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
        });

        // SUPPLIERS
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('gl_code', 50)->unique();
            $table->string('name')->index();
            $table->string('address');
            $table->string('phone', 25)->nullable();
            $table->string('pic_name')->nullable();
            $table->boolean('is_ppn')->default(false);
            $table->text('notes')->nullable();
            $table->unsignedInteger('editor_id');
            $table->timestamps();
            $table->softDeletes();
        });

        // UOMS (Units of Measurement)
        Schema::create('uoms', function (Blueprint $table) {
            $table->string('code', 10)->primary(); // contoh: PCS, SET, UNIT
            $table->string('name');                // contoh: Pieces, Set, Unit
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ITEMS
        Schema::create('items', function (Blueprint $table) {
            $table->string('code', 20)->primary(); // jadikan primary key
            $table->string('name');
            $table->string('uom_code')->nullable();
            $table->string('stock_code', 20);
            $table->string('category_code', 10);
            $table->double('min_stock')->default(0);
            $table->double('max_stock')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('editor_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('category_code')->references('code')->on('categories')->cascadeOnDelete();
            $table->foreign('stock_code')->references('code')->on('stock_codes')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
        Schema::dropIfExists('uoms');
        Schema::dropIfExists('suppliers');
        Schema::dropIfExists('transaction_types');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('depreciation_groups');
        Schema::dropIfExists('rooms');
        Schema::dropIfExists('warehouse_stock_codes');
        Schema::dropIfExists('warehouses');
        Schema::dropIfExists('branches');
        Schema::dropIfExists('stock_codes');
        Schema::dropIfExists('code_registers');
        Schema::dropIfExists('configurations');
    }
};
