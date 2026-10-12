<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedFields;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Evenement extends Model
{
    use HasLocalizedFields;

    public const TYPES = [
        'porte_ouverte' => 'Journée portes ouvertes',
        'panel' => 'Panel / Conférence',
        'atelier' => 'Atelier / Séminaire',
        'ceremonie' => 'Cérémonie / Remise de diplômes',
        'webinaire' => 'Webinaire',
        'autre' => 'Autre',
    ];

    protected $fillable = [
        'title_fr', 'title_en', 'slug', 'type',
        'description_fr', 'description_en', 'image',
        'starts_at', 'ends_at', 'antenne_id', 'location', 'online_url',
        'registration_open', 'is_featured', 'published',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'registration_open' => 'boolean',
        'is_featured' => 'boolean',
        'published' => 'boolean',
    ];

    public function getTitleAttribute(): ?string
    {
        return $this->localized('title');
    }

    public function getDescriptionAttribute(): ?string
    {
        return $this->localized('description');
    }

    public function getTypeLabelAttribute(): string
    {
        return self::TYPES[$this->type] ?? self::TYPES['autre'];
    }

    public function antenne(): BelongsTo
    {
        return $this->belongsTo(Antenne::class);
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(EvenementSession::class)->orderBy('position')->orderBy('starts_at');
    }

    public function inscriptions(): HasMany
    {
        return $this->hasMany(EvenementInscription::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('published', true);
    }

    /** À venir ou en cours (sans fin renseignée : jusqu'à la fin de la journée de début). */
    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where(fn ($q) => $q
            ->where('ends_at', '>=', now())
            ->orWhere(fn ($q) => $q->whereNull('ends_at')->where('starts_at', '>=', now()->startOfDay())));
    }

    public function scopePast(Builder $query): Builder
    {
        return $query->where(fn ($q) => $q
            ->where('ends_at', '<', now())
            ->orWhere(fn ($q) => $q->whereNull('ends_at')->where('starts_at', '<', now()->startOfDay())));
    }

    /** Fin effective : ends_at, sinon fin de la journée de début. */
    public function getEffectiveEndAttribute()
    {
        return $this->ends_at ?? $this->starts_at->copy()->endOfDay();
    }

    public function isPast(): bool
    {
        return $this->effective_end->isPast();
    }

    public function canRegister(): bool
    {
        return $this->published && $this->registration_open && ! $this->isPast();
    }

    public function placeLabel(): ?string
    {
        $parts = array_filter([$this->location, $this->antenne?->name]);

        return $parts ? implode(' — ', $parts) : ($this->online_url ? 'En ligne' : null);
    }
}
