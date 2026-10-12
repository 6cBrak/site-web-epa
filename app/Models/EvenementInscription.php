<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class EvenementInscription extends Model
{
    public const EPA_STATUSES = [
        'non' => 'Non',
        'oui' => 'Oui, déjà inscrit(e)',
        'ancien' => "Ancien étudiant(e) d'EPA",
        'autre' => 'Autre',
    ];

    protected $fillable = [
        'evenement_id', 'full_name', 'email', 'phone', 'profession', 'nationality',
        'city', 'epa_status', 'epa_status_other', 'how_heard', 'comment', 'token',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $inscription) {
            $inscription->token ??= Str::random(32);
        });
    }

    public function evenement(): BelongsTo
    {
        return $this->belongsTo(Evenement::class);
    }

    public function sessions(): BelongsToMany
    {
        return $this->belongsToMany(EvenementSession::class, 'evenement_inscription_session');
    }

    public function getEpaStatusLabelAttribute(): string
    {
        $label = self::EPA_STATUSES[$this->epa_status] ?? $this->epa_status;

        return $this->epa_status === 'autre' && $this->epa_status_other
            ? $label.' : '.$this->epa_status_other
            : $label;
    }

    /** Lien WhatsApp (indicatif +226 ajouté si le numéro est local à 8 chiffres). */
    public function whatsappUrl(?string $text = null): string
    {
        $digits = preg_replace('/\D+/', '', $this->phone);
        if (strlen($digits) === 8) {
            $digits = '226'.$digits;
        }

        return 'https://wa.me/'.$digits.($text ? '?text='.rawurlencode($text) : '');
    }
}
