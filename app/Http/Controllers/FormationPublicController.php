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

    public function show(Formation $formation): View
    {
        abort_unless($formation->published, 404);

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
}
