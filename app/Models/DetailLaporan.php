<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailLaporan extends Model
{
    protected $table = 'detail_laporan';
    protected $primaryKey = 'id';
    protected $fillable = ['id_laporan', 'id_kategori', 'total_per_kategori'];

    public function laporan()
    {
        return $this->belongsTo(Laporan::class, 'id_laporan');
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori');
    }
}
