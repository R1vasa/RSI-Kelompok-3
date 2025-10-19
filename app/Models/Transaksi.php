<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_users',
        'id_kategori',
        'judul_transaksi',
        'jumlah_transaksi',
        'tgl_transaksi'
    ];

    public function pengguna()
    {
        return $this->belongsTo(User::class, 'id_users');
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori');
    }
}
