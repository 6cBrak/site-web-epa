<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Formation extends Model
{
    use HasFactory, HasLocalizedFields;

    protected $fillable = [
        'programme_id', 'title_fr', 'title_en', 'slug', 'image',
        'description_fr', 'description_en',
        'objectives_fr', 'objectives_en',
        'modules_fr', 'modules_en',
        'prerequisites_fr', 'prerequisites_en',
        'duration', 'price', 'published', 'order',
    ];

    protected $casts = [
        'published' => 'boolean',
        'price' => 'decimal:2',
    ];

    public function programme(): BelongsTo
    {
        return $this->belongsTo(Programme::class);
    }

    public function antennes(): BelongsToMany
    {
        return $this->belongsToMany(Antenne::class, 'formation_antenne');
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(FormationSession::class);
    }

    public function candidatures(): HasMany
    {
        return $this->hasMany(Candidature::class);
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }

    public function getTitleAttribute(): ?string
    {
        return $this->localized('title');
    }

    public function getDescriptionAttribute(): ?string
    {
        return $this->localized('description');
    }

    public function getObjectivesAttribute(): ?string
    {
        return $this->localized('objectives');
    }

    public function getModulesAttribute(): ?string
    {
        return $this->localized('modules');
    }

    public function getPrerequisitesAttribute(): ?string
    {
        return $this->localized('prerequisites');
    }
}
