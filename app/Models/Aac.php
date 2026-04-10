<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aac extends Model
{
    protected $table = 'aac';

    protected $fillable = [
        'judul',
        'kategori',
        'deskripsi',
        'gambar_path',
        'urutan',
        'status_aktif',
        'dibuat_oleh',
    ];

    protected $casts = [
        'status_aktif' => 'boolean',
        'urutan' => 'integer',
    ];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'dibuat_oleh');
    }
}
