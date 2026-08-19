<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedFields;
use Illuminate\Database\Eloquent\Model;

class HeroSlide extends Model
{
    use HasLocalizedFields;

    protected $fillable = [
        'image', 'caption_fr', 'caption_en', 'order', 'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function getCaptionAttribute(): ?string
    {
        return $this->localized('caption');
    }
}
