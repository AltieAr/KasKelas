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
        Schema::create('class_assets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama_barang', 150);
            $table->integer('jumlah');
            $table->enum('kondisi', ['baik', 'rusak', 'hilang'])->default('baik');
            $table->date('tanggal_perolehan');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_assets');
    }
};
