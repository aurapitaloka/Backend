<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Materi extends Model
{
    protected $table = 'materi';

    protected $fillable = [
        'judul',
        'deskripsi',
        'mata_pelajaran_id',
        'level_id',
        'tipe_konten',
        'konten_teks',
        'file_path',
        'cover_path',
        'jumlah_halaman',
        'status_aktif',
        'dibuat_oleh',
    ];

    protected $casts = [
        'status_aktif' => 'boolean',
        'jumlah_halaman' => 'integer',
    ];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'dibuat_oleh');
    }

    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, 'mata_pelajaran_id');
    }

    public function level()
    {
        return $this->belongsTo(Level::class, 'level_id');
    }

    public function kuis()
    {
        return $this->hasMany(Kuis::class, 'materi_id');
    }
}
