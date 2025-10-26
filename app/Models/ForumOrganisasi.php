<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\AnggotaForum;
use App\Models\KasOrganisasi;
use App\Models\TransaksiOrganisasi;
use App\Models\Laporan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ForumOrganisasi extends Model
{
    protected $table = 'forum_organisasi';
    protected $primaryKey = 'id';
    protected $fillable = ['id_users', 'forum', 'slug', 'deskripsi', 'gambar_forum', 'link_akses'];

    public function pembuat()
    {
        return $this->belongsTo(User::class, 'id_users');
    }

    public function anggota()
    {
        return $this->hasMany(AnggotaForum::class, 'id_forum');
    }

    public function kas()
    {
        return $this->hasMany(KasOrganisasi::class, 'id');
    }

    public function transaksi()
    {
        return $this->hasMany(TransaksiOrganisasi::class, 'id');
    }

    public function laporan()
    {
        return $this->hasMany(Laporan::class, 'id');
    }

    protected static function booted()
    {
        static::creating(function ($forum) {
            $forum->slug = Str::slug($forum->forum);

            // Cegah slug duplikat
            $originalSlug = $forum->slug;
            $count = 1;
            while (self::where('slug', $forum->slug)->exists()) {
                $forum->slug = $originalSlug . '-' . $count++;
            }
        });

        static::deleting(function ($forum) {
            // Cek apakah gambar bukan default
            if ($forum->gambar_forum && $forum->gambar_forum !== 'default_forum.png') {
                // Hapus dari storage/public
                Storage::disk('public')->delete($forum->gambar_forum);
            }
        });
    }
}
