<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SesiBaca extends Model
{
    protected $table = 'sesi_baca';
    public $timestamps = false;

    protected $fillable = [
        'pengguna_id',
        'materi_id',
        'mulai',
        'selesai',
        'durasi_detik',
        'halaman_terakhir',
        'progres_persen',
        'gunakan_gaze',
        'gunakan_suara',
    ];

    protected $casts = [
        'mulai' => 'datetime',
        'selesai' => 'datetime',
        'durasi_detik' => 'integer',
        'halaman_terakhir' => 'integer',
        'progres_persen' => 'integer',
        'gunakan_gaze' => 'boolean',
        'gunakan_suara' => 'boolean',
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
