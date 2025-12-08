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
        Schema::create('aset_penghapusan', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('tgl_penghapusan')->nullable();
            $table->unsignedInteger('satker_id');
            $table->char('status')->default("OPEN");
            $table->string('notes')->nullable();
            $table->json('etc')->nullable();
            $table->unsignedInteger('editor_id');
            $table->timestamps();
            $table->softDeletes();
            $table->string('delete_message')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aset_penghapusan');
    }
};
