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
        Schema::create('detail_laporan', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('id_laporan')->constrained('laporan')->onDelete('cascade');
            $table->foreignId('id_kategori')->constrained('kategori');
            $table->decimal('total_per_kategori', 12, 2);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_laporan');
    }
};
