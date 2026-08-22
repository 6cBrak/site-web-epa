<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Antenne;
use App\Models\AssistantConversation;
use App\Models\AssistantLeadCapture;
use App\Models\Candidature;
use App\Models\Formation;
use App\Models\Programme;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $leadsCount = AssistantLeadCapture::count();
        $leadsThisWeek = AssistantLeadCapture::where('captured_at', '>=', now()->subDays(7))->count();
        $conversationsCount = AssistantConversation::count();
        $leadsConverted = AssistantLeadCapture::whereHas('candidature')->count();
        $hotLeadsPending = AssistantLeadCapture::where('priority', 'chaud')
            ->whereIn('status', ['nouveau', 'contacte'])
            ->count();

        $topFormations = AssistantLeadCapture::whereNotNull('formation_interest')
            ->selectRaw('formation_interest, count(*) as total')
            ->groupBy('formation_interest')
            ->orderByDesc('total')
            ->limit(3)
            ->get();

        return view('admin.dashboard', [
            'antennesCount' => Antenne::count(),
            'programmesCount' => Programme::count(),
            'formationsCount' => Formation::count(),
            'candidaturesCount' => Candidature::count(),
            'candidaturesNouvelles' => Candidature::where('status', 'nouvelle')->count(),
            'chatLeadsCount' => $leadsCount,
            'chatLeadsThisWeek' => $leadsThisWeek,
            'chatHotLeadsPending' => $hotLeadsPending,
            'chatConversationsCount' => $conversationsCount,
            'chatConversionRate' => $leadsCount > 0 ? round($leadsConverted / $leadsCount * 100) : null,
            'chatTopFormations' => $topFormations,
        ]);
    }
}
