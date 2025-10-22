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
        Schema::create('kategori', function (Blueprint $table) {
            $table->id('id');
            $table->string('kategori', 50);
        });

        DB::table('kategori')->insert([
            ['kategori' => 'Jajan'],
            ['kategori' => 'Service'],
            ['kategori' => 'Makan'],
            ['kategori' => 'Transportasi'],
            ['kategori' => 'Lain-lain'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kategori');
    }
};
