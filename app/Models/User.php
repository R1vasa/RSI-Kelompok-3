<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Transaksi;
use App\Models\Goals;
use App\Models\ForumOrganisasi;
use App\Models\AnggotaForum;
use App\Models\AnggaranBulanan;
use App\Models\Laporan;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nama',
        'email',
        'password',
        'otp'
    ];

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'id');
    }

    public function goals()
    {
        return $this->hasMany(Goals::class, 'id');
    }

    public function forumDibuat()
    {
        return $this->hasMany(ForumOrganisasi::class, 'id');
    }

    public function anggotaForum()
    {
        return $this->hasMany(AnggotaForum::class, 'id');
    }

    public function anggaranBulanan()
    {
        return $this->hasMany(AnggaranBulanan::class, 'id');
    }

    public function laporan()
    {
        return $this->hasMany(Laporan::class, 'id');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
