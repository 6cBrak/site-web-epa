<?php

namespace App\Services;

use App\Models\Antenne;
use App\Models\Formation;
use App\Models\FormationSession;
use Illuminate\Support\Facades\Cache;

class AssistantKnowledgeService
{
    public function buildContext(): string
    {
        $locale = app()->getLocale();

        return Cache::remember("assistant.knowledge.{$locale}", now()->addHour(), function () {
            return $this->compileAntennes()."\n\n".$this->compileFormations();
        });
    }

    protected function compileAntennes(): string
    {
        $lines = ["ANTENNES EPA_BURKINA :"];

        Antenne::where('active', true)->orderBy('name')->get()->each(function (Antenne $antenne) use (&$lines) {
            $details = [$antenne->name];

            if ($antenne->address) {
                $details[] = "adresse : {$antenne->address}";
            }
            if ($antenne->phone) {
                $details[] = "téléphone/WhatsApp : {$antenne->phone}";
            }
            if ($antenne->email) {
                $details[] = "email : {$antenne->email}";
            }

            $lines[] = '- '.implode(', ', $details);
        });

        return implode("\n", $lines);
    }

    protected function compileFormations(): string
    {
        $formations = Formation::with([
            'programme',
            'antennes',
            'sessions' => fn ($q) => $q->where('status', '!=', 'cloturee')
                ->where('start_date', '>=', now())
                ->orderBy('start_date')
                ->with('antenne'),
        ])
            ->where('published', true)
            ->orderBy('order')
            ->get();

        if ($formations->isEmpty()) {
            return "FORMATIONS : aucune formation publiée pour le moment.";
        }

        $lines = ["FORMATIONS DISPONIBLES :"];

        foreach ($formations as $formation) {
            $details = [];

            $details[] = 'Titre : '.$formation->title;
            $details[] = 'Slug (pour lien d\'inscription) : '.$formation->slug;

            if ($formation->programme) {
                $details[] = 'Pôle : '.$formation->programme->name;
            }
            if ($formation->duration) {
                $details[] = 'Durée : '.$formation->duration;
            }
            $details[] = 'Prix : '.($formation->price !== null ? number_format((float) $formation->price, 0, ',', ' ').' FCFA' : 'non communiqué');

            $antennes = $formation->antennes->pluck('name')->filter()->implode(', ');
            if ($antennes) {
                $details[] = 'Antennes : '.$antennes;
            }

            if ($formation->prerequisites) {
                $details[] = 'Prérequis : '.$formation->prerequisites;
            }
            if ($formation->description) {
                $details[] = 'Description : '.$formation->description;
            }
            if ($formation->objectives) {
                $details[] = 'Objectifs/débouchés : '.$formation->objectives;
            }

            $sessions = $formation->sessions
                ->map(fn (FormationSession $session) => sprintf(
                    '%s à %s (%s)',
                    $session->start_date->format('d/m/Y'),
                    $session->antenne->name,
                    $session->seatsRemaining() !== null ? $session->seatsRemaining().' places restantes' : 'places disponibles'
                ))
                ->implode('; ');

            if ($sessions) {
                $details[] = 'Sessions à venir : '.$sessions;
            }

            $lines[] = "- ".implode(' | ', $details);
        }

        return implode("\n", $lines);
    }
}
