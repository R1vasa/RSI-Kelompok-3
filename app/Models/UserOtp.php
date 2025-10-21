<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class UserOtp extends Model
{
    protected $fillable = ['id_users', 'otp_code', 'purpose', 'attempt_count', 'resend_at', 'expires_at'];
    protected $casts = [
        'expires_at' => 'datetime',
        'resend_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_users');
    }

    // Cek apakah OTP masih valid
    public function isExpired(): bool
    {
        return Carbon::now()->gt($this->expires_at);
    }
}
