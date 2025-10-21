<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = 'kategori';
    protected $primaryKey = 'id';
    protected $fillable = ['kategori'];

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'id');
    }

    public function detailLaporan()
    {
        return $this->hasMany(DetailLaporan::class, 'id');
    }

    public function anggaran()
    {
        return $this->hasMany(AnggaranBulanan::class, 'id');
    }
}
