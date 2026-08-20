<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Actualite extends Model
{
    use HasFactory, HasLocalizedFields;

    protected $fillable = [
        'title_fr', 'title_en', 'slug', 'image',
        'excerpt_fr', 'excerpt_en', 'content_fr', 'content_en',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function getTitleAttribute(): ?string
    {
        return $this->localized('title');
    }

    public function getExcerptAttribute(): ?string
    {
        return $this->localized('excerpt');
    }

    public function getContentAttribute(): ?string
    {
        return $this->localized('content');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function approvedComments(): HasMany
    {
        return $this->comments()->where('approved', true)->latest();
    }
}
