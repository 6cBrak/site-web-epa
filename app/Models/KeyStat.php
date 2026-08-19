<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeyStat extends Model
{
    use HasFactory, HasLocalizedFields;

    protected $fillable = [
        'label_fr', 'label_en', 'value', 'suffix', 'icon', 'order',
    ];

    public function getLabelAttribute(): ?string
    {
        return $this->localized('label');
    }
}
