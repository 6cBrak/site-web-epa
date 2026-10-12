<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Evenement;
use App\Models\EvenementInscription;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\View\View;

class EvenementInscriptionController extends Controller
{
    public function index(Evenement $evenement): View
    {
        $evenement->load('sessions');

        $inscriptions = $evenement->inscriptions()->with('sessions')->latest()->get();

        return view('admin.evenements.inscriptions', [
            'evenement' => $evenement,
            'inscriptions' => $inscriptions,
            'stats' => [
                'sessions' => $evenement->sessions->mapWithKeys(fn ($s) => [
                    $s->title_fr => $inscriptions->filter(fn ($i) => $i->sessions->contains('id', $s->id))->count(),
                ]),
                'cities' => $inscriptions->groupBy(fn ($i) => mb_convert_case(trim($i->city), MB_CASE_TITLE))->map->count()->sortDesc()->take(8),
                'sources' => $inscriptions->groupBy(fn ($i) => mb_strtolower(trim($i->how_heard)))->map->count()->sortDesc()->take(8),
            ],
        ]);
    }

    public function export(Evenement $evenement): Response
    {
        $evenement->load('sessions');
        $rows = $evenement->inscriptions()->with('sessions')->orderBy('created_at')->get();

        $escape = fn ($v) => '"'.str_replace('"', '""', (string) $v).'"';

        $csv = "\xEF\xBB\xBF".implode(';', ['Date', 'Nom et prénom', 'Email', 'Téléphone', 'Profession', 'Nationalité', 'Ville', 'Statut EPA', 'Participation', 'Source', 'Commentaire'])."\n";
        foreach ($rows as $i) {
            $csv .= implode(';', array_map($escape, [
                $i->created_at->format('d/m/Y H:i'),
                $i->full_name,
                $i->email,
                $i->phone,
                $i->profession,
                $i->nationality,
                $i->city,
                $i->epa_status_label,
                $i->sessions->pluck('title_fr')->implode(' + '),
                $i->how_heard,
                $i->comment,
            ]))."\n";
        }

        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="inscrits-'.$evenement->slug.'.csv"',
        ]);
    }

    public function destroy(EvenementInscription $inscription): RedirectResponse
    {
        $evenement = $inscription->evenement;
        $inscription->delete();

        return redirect()->route('admin.evenements.inscriptions', $evenement)->with('status', 'Inscription supprimée.');
    }
}
