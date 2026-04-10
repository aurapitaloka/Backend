<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogAksesMateri extends Model
{
    protected $table = 'log_akses_materi';
    public $timestamps = false;

    protected $fillable = [
        'pengguna_id',
        'materi_id',
        'waktu_akses',
        'aksi',
    ];

    protected $casts = [
        'waktu_akses' => 'datetime',
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
