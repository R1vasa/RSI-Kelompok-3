<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
            ['id' => 1, 'kategori' => 'Jajan'],
            ['id' => 2, 'kategori' => 'Service'],
            ['id' => 3, 'kategori' => 'Makan'],
            ['id' => 4, 'kategori' => 'Transportasi'],
            ['id' => 5, 'kategori' => 'Lain-lain'],
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
