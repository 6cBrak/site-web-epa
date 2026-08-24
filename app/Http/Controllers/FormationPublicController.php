<?php

namespace App\Http\Controllers;

use App\Models\Antenne;
use App\Models\Formation;
use App\Models\Programme;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FormationPublicController extends Controller
{
    public function index(Request $request): View
    {
        $query = Formation::with('programme')
            ->where('published', true)
            ->orderBy('order');

        if ($programmeSlug = $request->query('programme')) {
            $query->whereHas('programme', fn ($q) => $q->where('slug', $programmeSlug));
        }

        if ($antenneSlug = $request->query('antenne')) {
            $query->whereHas('antennes', fn ($q) => $q->where('slug', $antenneSlug));
        }

        return view('public.formations.index', [
            'formations' => $query->get(),
            'programmes' => Programme::where('active', true)->orderBy('order')->get(),
            'antennes' => Antenne::where('active', true)->orderBy('name')->get(),
            'selectedProgramme' => $programmeSlug,
            'selectedAntenne' => $antenneSlug,
        ]);
    }

    public function show(Formation $formation, Request $request): View
    {
        abort_unless($formation->published, 404);

        $this->recordView($formation, $request);

        $formation->load([
            'programme',
            'antennes',
            'sessions' => fn ($q) => $q->where('status', '!=', 'cloturee')
                ->where('start_date', '>=', now()->subDay())
                ->orderBy('start_date')
                ->with('antenne'),
        ]);

        return view('public.formations.show', [
            'formation' => $formation,
        ]);
    }

    /**
     * Incrémente le compteur de vues, en évitant de compter plusieurs fois
     * la même session sur une courte période (rechargements successifs).
     */
    protected function recordView(Formation $formation, Request $request): void
    {
        $sessionKey = 'viewed_formations';
        $viewed = $request->session()->get($sessionKey, []);
        $debounceUntil = $viewed[$formation->id] ?? null;

        if ($debounceUntil && now()->lt($debounceUntil)) {
            return;
        }

        $formation->increment('views_count');

        $viewed[$formation->id] = now()->addHours(12);
        $request->session()->put($sessionKey, $viewed);
    }
}
