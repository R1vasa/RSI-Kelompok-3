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
        Schema::create('user_otps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_users')->constrained('users')->onDelete('cascade');
            $table->string('otp_code', 225);
            $table->string('purpose')->default('register'); // register / reset_password
            $table->integer('attempt_count')->default(0);
            $table->timestamp('expires_at');
            $table->timestamp('resend_at')->nullable();
            $table->timestamps();

            $table->unique(['id_users', 'purpose']); // satu OTP aktif per tujuan
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_otps');
    }
};
