<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $table = 'siswa';

    protected $fillable = [
        'pengguna_id',
        'nama_sekolah',
        'jenjang',
        'level_id',
        'catatan',
    ];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'pengguna_id');
    }

    public function level()
    {
        return $this->belongsTo(Level::class, 'level_id');
    }
}
