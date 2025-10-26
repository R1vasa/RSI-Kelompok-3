<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KasOrganisasi extends Model
{
    protected $table = 'kas_organisasi';
    protected $primaryKey = 'id';
    protected $fillable = ['id_forum', 'nama_transaksi', 'jumlah', 'tgl_transaksi_org'];

    public function forum()
    {
        return $this->belongsTo(ForumOrganisasi::class, 'id_forum');
    }
}
