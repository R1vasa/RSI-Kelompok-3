<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiOrganisasi extends Model
{
    protected $table = 'transaksi_organisasi';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_forum',
        'nama',
        'jenis',
        'nominal',
        'deskripsi',
        'tgl_transaksi'
    ];

    public function forum()
    {
        return $this->belongsTo(ForumOrganisasi::class, 'id_forum');
    }
}
