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
        Schema::table('transaksi_organisasi', function (Blueprint $table) {
            $table->dropColumn('sumber');
            $table->dropColumn('kategori');
            $table->string('nama', 100)->after('id_forum');
        });
        Schema::table('kas_organisasi', function (Blueprint $table) {
            $table->dropColumn('jenis_transaksi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trans_org', function (Blueprint $table) {
            //
        });
    }
};
