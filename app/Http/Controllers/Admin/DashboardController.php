<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Antenne;
use App\Models\Candidature;
use App\Models\Formation;
use App\Models\Programme;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'antennesCount' => Antenne::count(),
            'programmesCount' => Programme::count(),
            'formationsCount' => Formation::count(),
            'candidaturesCount' => Candidature::count(),
            'candidaturesNouvelles' => Candidature::where('status', 'nouvelle')->count(),
        ]);
    }
}
