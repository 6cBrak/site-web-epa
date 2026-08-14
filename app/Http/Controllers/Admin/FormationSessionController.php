<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Antenne;
use App\Models\Formation;
use App\Models\FormationSession;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FormationSessionController extends Controller
{
    public function index(): View
    {
        return view('admin.formation-sessions.index', [
            'sessions' => FormationSession::with(['formation', 'antenne'])
                ->orderBy('start_date')
                ->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.formation-sessions.create', [
            'formations' => Formation::orderBy('title_fr')->get(),
            'antennes' => Antenne::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        FormationSession::create($this->validated($request));

        return redirect()->route('admin.formation-sessions.index')->with('status', 'Session créée.');
    }

    public function edit(FormationSession $formationSession): View
    {
        return view('admin.formation-sessions.edit', [
            'session' => $formationSession,
            'formations' => Formation::orderBy('title_fr')->get(),
            'antennes' => Antenne::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, FormationSession $formationSession): RedirectResponse
    {
        $formationSession->update($this->validated($request));

        return redirect()->route('admin.formation-sessions.index')->with('status', 'Session mise à jour.');
    }

    public function destroy(FormationSession $formationSession): RedirectResponse
    {
        $formationSession->delete();

        return redirect()->route('admin.formation-sessions.index')->with('status', 'Session supprimée.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'formation_id' => ['required', 'exists:formations,id'],
            'antenne_id' => ['required', 'exists:antennes,id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'modality' => ['required', 'in:en_ligne,presentiel_jour,presentiel_soir'],
            'capacity' => ['nullable', 'integer', 'min:0'],
            'seats_taken' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', 'in:ouverte,complete,cloturee'],
        ]);
    }
}
