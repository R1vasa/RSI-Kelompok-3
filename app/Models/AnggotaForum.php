<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnggotaForum extends Model
{
    protected $table = 'anggota_forum';
    protected $primaryKey = 'id';
    protected $fillable = ['id_forum', 'id_users', 'role'];

    public function forum()
    {
        return $this->belongsTo(ForumOrganisasi::class, 'id_forum');
    }

    public function pengguna()
    {
        return $this->belongsTo(User::class, 'id_users');
    }
}
