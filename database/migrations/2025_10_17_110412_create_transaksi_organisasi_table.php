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
        Schema::create('transaksi_organisasi', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('id_forum')->constrained('forum_organisasi')->onDelete('cascade');
            $table->enum('jenis', ['pemasukan', 'pengeluaran']);
            $table->string('sumber', 100)->nullable();
            $table->string('kategori', 100);
            $table->decimal('nominal', 12, 2);
            $table->text('deskripsi')->nullable();
            $table->date('tgl_transaksi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_organisasi');
    }
};
