<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Candidature extends Model
{
    use HasFactory;

    protected $fillable = [
        'formation_id', 'formation_session_id', 'antenne_id', 'promo_code_id',
        'first_name', 'last_name', 'email', 'phone',
        'education_level', 'nationality', 'city_country',
        'profile_type', 'cv_path', 'start_preference', 'how_heard', 'comment',
        'status', 'tracking_token',
    ];

    protected static function booted(): void
    {
        static::creating(function (Candidature $candidature) {
            $candidature->tracking_token ??= (string) Str::uuid();
        });
    }

    public function formation(): BelongsTo
    {
        return $this->belongsTo(Formation::class);
    }

    public function formationSession(): BelongsTo
    {
        return $this->belongsTo(FormationSession::class);
    }

    public function antenne(): BelongsTo
    {
        return $this->belongsTo(Antenne::class);
    }

    public function promoCode(): BelongsTo
    {
        return $this->belongsTo(PromoCode::class);
    }
}
