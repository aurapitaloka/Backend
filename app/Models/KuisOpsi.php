<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KuisOpsi extends Model
{
    protected $table = 'kuis_opsi';

    protected $fillable = [
        'pertanyaan_id',
        'label',
        'teks',
        'benar',
    ];

    protected $casts = [
        'benar' => 'boolean',
    ];

    public function pertanyaan()
    {
        return $this->belongsTo(KuisPertanyaan::class, 'pertanyaan_id');
    }
}
