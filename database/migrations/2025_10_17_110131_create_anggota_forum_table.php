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
        Schema::create('anggota_forum', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('id_forum')->constrained('forum_organisasi')->onDelete('cascade');
            $table->foreignId('id_users')->constrained('users')->onDelete('cascade');
            $table->enum('role', ['bendahara', 'anggota'])->default('anggota');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anggota_forum');
    }
};
