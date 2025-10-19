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
        Schema::create('anggaran_bulanan', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('id_users')->constrained('users')->onDelete('cascade');
            $table->foreignId('id_kategori')->constrained('kategori');
            $table->decimal('jmlh_anggaran', 12, 2);
            $table->string('periode', 20);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anggaran_bulanan');
    }
};
