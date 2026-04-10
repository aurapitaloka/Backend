<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RakBuku extends Model
{
    protected $table = 'rak_buku';

    protected $fillable = [
        'pengguna_id',
        'materi_id',
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
