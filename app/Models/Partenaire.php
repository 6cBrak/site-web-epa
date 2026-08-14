<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Partenaire extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'logo', 'category', 'website', 'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function promoCodes(): HasMany
    {
        return $this->hasMany(PromoCode::class);
    }
}
