<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Programme extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_fr', 'name_en', 'slug',
        'description_fr', 'description_en',
        'icon', 'color', 'order', 'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function formations(): HasMany
    {
        return $this->hasMany(Formation::class);
    }
}
