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
        Schema::create('laporan', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('id_users')->nullable()->constrained('users');
            $table->foreignId('id_forum')->nullable()->constrained('forum_organisasi');
            $table->enum('jenis_laporan', ['pribadi', 'organisasi']);
            $table->date('periode_awal');
            $table->date('periode_akhir');
            $table->decimal('total_pemasukan', 12, 2)->default(0);
            $table->decimal('total_pengeluaran', 12, 2)->default(0);
            $table->decimal('saldo_akhir', 12, 2)->default(0);
            $table->string('file_laporan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan');
    }
};
