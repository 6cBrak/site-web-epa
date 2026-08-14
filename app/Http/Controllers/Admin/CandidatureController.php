<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Antenne;
use App\Models\Candidature;
use App\Models\Formation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class CandidatureController extends Controller
{
    public function index(Request $request): View
    {
        $query = Candidature::with(['formation', 'antenne']);

        if ($request->filled('formation_id')) {
            $query->where('formation_id', $request->input('formation_id'));
        }

        if ($request->filled('antenne_id')) {
            $query->where('antenne_id', $request->input('antenne_id'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        return view('admin.candidatures.index', [
            'candidatures' => $query->latest()->paginate(20)->withQueryString(),
            'formations' => Formation::orderBy('title_fr')->get(),
            'antennes' => Antenne::orderBy('name')->get(),
        ]);
    }

    public function show(Candidature $candidature): View
    {
        $candidature->load(['formation', 'antenne', 'formationSession', 'promoCode.partenaire']);

        return view('admin.candidatures.show', ['candidature' => $candidature]);
    }

    public function update(Request $request, Candidature $candidature): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:nouvelle,contactee,confirmee,refusee'],
        ]);

        $candidature->update($data);

        return back()->with('status', 'Statut mis à jour.');
    }

    public function destroy(Candidature $candidature): RedirectResponse
    {
        $candidature->delete();

        return redirect()->route('admin.candidatures.index')->with('status', 'Candidature supprimée.');
    }

    public function export(Request $request): Response
    {
        $query = Candidature::with(['formation', 'antenne']);

        if ($request->filled('formation_id')) {
            $query->where('formation_id', $request->input('formation_id'));
        }
        if ($request->filled('antenne_id')) {
            $query->where('antenne_id', $request->input('antenne_id'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $rows = $query->latest()->get();

        $csv = "Date;Nom;Prenom;Email;Telephone;Formation;Antenne;Profil;Statut\n";
        foreach ($rows as $c) {
            $csv .= implode(';', [
                $c->created_at->format('d/m/Y H:i'),
                $c->last_name,
                $c->first_name,
                $c->email,
                $c->phone,
                $c->formation->title_fr,
                $c->antenne->name,
                $c->profile_type,
                $c->status,
            ])."\n";
        }

        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="candidatures.csv"',
        ]);
    }
}
