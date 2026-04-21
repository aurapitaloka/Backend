<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandingItem extends Model
{
    protected $table = 'landing_items';

    public const SECTIONS = [
        'branding' => 'Branding Aplikasi',
        'hero' => 'Hero',
        'feature-header' => 'Header Fitur',
        'feature' => 'Fitur',
        'flow-header' => 'Header Alur',
        'step' => 'Langkah Alur',
        'collection-header' => 'Header Koleksi',
        'book' => 'Item Koleksi',
        'app' => 'CTA Aplikasi',
        'footer' => 'Footer',
    ];

    protected $fillable = [
        'section',
        'title',
        'subtitle',
        'description',
        'badge',
        'button_label',
        'button_url',
        'image_path',
        'meta_one',
        'meta_two',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeSection($query, string $section)
    {
        return $query->where('section', $section);
    }

    public static function sectionLabel(string $section): string
    {
        return self::SECTIONS[$section] ?? $section;
    }
}
