<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kuis extends Model
{
    protected $table = 'kuis';

    protected $fillable = [
        'materi_id',
        'materi_bab_id',
        'judul',
        'deskripsi',
        'status_aktif',
        'dibuat_oleh',
    ];

    protected $casts = [
        'status_aktif' => 'boolean',
    ];

    public function materi()
    {
        return $this->belongsTo(Materi::class, 'materi_id');
    }

    public function pertanyaan()
    {
        return $this->hasMany(KuisPertanyaan::class, 'kuis_id')->orderBy('urutan');
    }

    public function materiBab()
    {
        return $this->belongsTo(MateriBab::class, 'materi_bab_id');
    }

    public function hasil()
    {
        return $this->hasMany(KuisHasil::class, 'kuis_id');
    }
}
