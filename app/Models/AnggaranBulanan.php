<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnggaranBulanan extends Model
{
    protected $table = 'anggaran_bulanan';
    protected $primaryKey = 'id';
    protected $fillable = ['id_users', 'id_kategori', 'jmlh_anggaran', 'periode'];

    public function pengguna()
    {
        return $this->belongsTo(User::class, 'id_users');
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori');
    }
}
