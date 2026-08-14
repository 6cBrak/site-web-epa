<?php

namespace App\Http\Controllers;

use App\Models\Actualite;
use Illuminate\View\View;

class ActualitePublicController extends Controller
{
    public function index(): View
    {
        return view('public.actualites.index', [
            'actualites' => Actualite::whereNotNull('published_at')
                ->where('published_at', '<=', now())
                ->orderByDesc('published_at')
                ->paginate(9),
        ]);
    }

    public function show(Actualite $actualite): View
    {
        abort_unless($actualite->published_at && $actualite->published_at <= now(), 404);

        return view('public.actualites.show', ['actualite' => $actualite]);
    }
}
