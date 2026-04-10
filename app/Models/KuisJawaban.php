<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KuisJawaban extends Model
{
    protected $table = 'kuis_jawaban';

    protected $fillable = [
        'kuis_hasil_id',
        'pertanyaan_id',
        'opsi_id',
        'benar',
        'jawaban_teks',
        'skor_auto',
        'status_koreksi',
    ];

    protected $casts = [
        'benar' => 'boolean',
        'skor_auto' => 'integer',
    ];

    public function hasil()
    {
        return $this->belongsTo(KuisHasil::class, 'kuis_hasil_id');
    }

    public function pertanyaan()
    {
        return $this->belongsTo(KuisPertanyaan::class, 'pertanyaan_id');
    }

    public function opsi()
    {
        return $this->belongsTo(KuisOpsi::class, 'opsi_id');
    }
}
