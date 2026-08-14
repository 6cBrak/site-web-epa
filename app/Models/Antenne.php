<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Antenne extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'address', 'phone', 'email',
        'description_fr', 'description_en',
        'latitude', 'longitude', 'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function formations(): BelongsToMany
    {
        return $this->belongsToMany(Formation::class, 'formation_antenne');
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(FormationSession::class);
    }

    public function candidatures(): HasMany
    {
        return $this->hasMany(Candidature::class);
    }
}
