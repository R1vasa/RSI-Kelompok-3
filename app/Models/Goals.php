<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Goals extends Model
{
    protected $table = 'goals';
    protected $primaryKey = 'id';
    protected $fillable = ['id_users', 'judul_goals', 'jumlah_target', 'current_amount', 'tgl_target','gambar'];

    public function pengguna()
    {
        return $this->belongsTo(User::class, 'id_users');
    }

    public function tabungan()
    {
        return $this->hasMany(Tabungan::class, 'id');
    }
}
