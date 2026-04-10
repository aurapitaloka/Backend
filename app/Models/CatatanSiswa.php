<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatatanSiswa extends Model
{
    protected $table = 'catatan_siswa';

    protected $fillable = [
        'pengguna_id',
        'materi_id',
        'isi',
    ];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'pengguna_id');
    }

    public function materi()
    {
        return $this->belongsTo(Materi::class, 'materi_id');
    }
}
