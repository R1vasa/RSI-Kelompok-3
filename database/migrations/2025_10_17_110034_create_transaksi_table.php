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
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('id_users')->constrained('users')->onDelete('cascade');
            $table->foreignId('id_kategori')->constrained('kategori');
            $table->string('judul_transaksi', 100);
            $table->decimal('jumlah_transaksi', 12, 2);
            $table->date('tgl_transaksi');
            $table->timestamps();
            $table->enum('jenis_transaksi', ['pemasukan', 'pengeluaran']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
