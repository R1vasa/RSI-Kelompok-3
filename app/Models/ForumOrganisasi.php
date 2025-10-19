<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ForumOrganisasi extends Model
{
    protected $table = 'forum_organisasi';
    protected $primaryKey = 'id';
    protected $fillable = ['id_users', 'forum', 'deskripsi', 'link_akses'];

    public function pembuat()
    {
        return $this->belongsTo(User::class, 'id_users');
    }

    public function anggota()
    {
        return $this->hasMany(AnggotaForum::class, 'id');
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
}
