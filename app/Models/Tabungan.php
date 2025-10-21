<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tabungan extends Model
{
    protected $table = 'tabungan';
    protected $primaryKey = 'id';
    protected $fillable = ['id_goals', 'jumlah_tabungan'];

    public function goals()
    {
        return $this->belongsTo(Goals::class, 'id_goals');
    }
}
