<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedFields;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class EvenementSession extends Model
{
    use HasLocalizedFields;

    protected $fillable = ['evenement_id', 'title_fr', 'title_en', 'starts_at', 'capacity', 'position'];

    protected $casts = [
        'starts_at' => 'datetime',
    ];

    public function getTitleAttribute(): ?string
    {
        return $this->localized('title');
    }

    public function evenement(): BelongsTo
    {
        return $this->belongsTo(Evenement::class);
    }

    public function inscriptions(): BelongsToMany
    {
        return $this->belongsToMany(EvenementInscription::class, 'evenement_inscription_session');
    }

    public function remainingPlaces(): ?int
    {
        if ($this->capacity === null) {
            return null;
        }

        return max(0, $this->capacity - $this->inscriptions()->count());
    }

    public function isFull(): bool
    {
        return $this->remainingPlaces() === 0;
    }
}
