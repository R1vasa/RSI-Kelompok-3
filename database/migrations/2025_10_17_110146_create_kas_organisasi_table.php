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
        Schema::create('kas_organisasi', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('id_forum')->constrained('forum_organisasi')->onDelete('cascade');
            $table->enum('jenis_transaksi', ['pemasukan', 'pengeluaran']);
            $table->string('nama_transaksi', 100);
            $table->decimal('jumlah', 12, 2);
            $table->date('tgl_transaksi_org');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kas_organisasi');
    }
};
