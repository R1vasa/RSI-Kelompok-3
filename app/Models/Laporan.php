<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    protected $table = 'laporan';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_users',
        'id_forum',
        'jenis_laporan',
        'periode_awal',
        'periode_akhir',
        'total_pemasukan',
        'total_pengeluaran',
        'saldo_akhir',
        'file_laporan'
    ];

    public function pengguna()
    {
        return $this->belongsTo(User::class, 'id_users');
    }

    public function forum()
    {
        return $this->belongsTo(ForumOrganisasi::class, 'id_forum');
    }

    public function detail()
    {
        return $this->hasMany(DetailLaporan::class, 'id_laporan');
    }
}
