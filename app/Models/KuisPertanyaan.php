<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KuisPertanyaan extends Model
{
    protected $table = 'kuis_pertanyaan';

    protected $fillable = [
        'kuis_id',
        'pertanyaan',
        'urutan',
        'tipe',
        'jawaban_teks',
        'keyword',
        'audio_path',
        'audio_text',
        'bahasa',
    ];

    protected $casts = [
        'urutan' => 'integer',
    ];

    public function kuis()
    {
        return $this->belongsTo(Kuis::class, 'kuis_id');
    }

    public function opsi()
    {
        return $this->hasMany(KuisOpsi::class, 'pertanyaan_id')->orderBy('label');
    }
}
