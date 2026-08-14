<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'certificate_number', 'formation_id', 'antenne_id',
        'learner_name', 'issued_at', 'status',
    ];

    protected $casts = [
        'issued_at' => 'date',
    ];

    protected static function booted(): void
    {
        static::creating(function (Certificate $certificate) {
            $certificate->certificate_number ??= 'EPA-'.now()->format('Y').'-'.strtoupper(Str::random(8));
        });
    }

    public function formation(): BelongsTo
    {
        return $this->belongsTo(Formation::class);
    }

    public function antenne(): BelongsTo
    {
        return $this->belongsTo(Antenne::class);
    }
}
