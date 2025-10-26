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
        Schema::create('forum_organisasi', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('id_users')->constrained('users')->onDelete('cascade');
            $table->string('forum', 100);
            $table->text('deskripsi')->nullable();
            $table->string('link_akses', 150)->unique();
            $table->string('gambar_forum')->nullable()->default('default_forum.png');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forum_organisasi');
    }
};
