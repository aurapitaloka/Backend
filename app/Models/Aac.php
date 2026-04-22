<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;

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

    protected $appends = [
        'gambar_url',
    ];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'dibuat_oleh');
    }

    public function getGambarUrlAttribute(): ?string
    {
        if (!$this->gambar_path) {
            return null;
        }

        return URL::route('media.public.show', ['path' => $this->gambar_path], true);
    }
}
