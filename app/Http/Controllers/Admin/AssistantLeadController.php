<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AssistantLeadCapture;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AssistantLeadController extends Controller
{
    public function index(Request $request): View
    {
        $query = AssistantLeadCapture::with('conversation');

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->input('priority'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('contact', 'like', "%{$search}%")
                    ->orWhere('formation_interest', 'like', "%{$search}%");
            });
        }

        return view('admin.assistant-leads.index', [
            'leads' => $query
                ->orderByRaw("FIELD(priority, 'chaud', 'tiede', 'froid')")
                ->latest('captured_at')
                ->paginate(20)
                ->withQueryString(),
        ]);
    }

    public function show(AssistantLeadCapture $assistantLead): View
    {
        $assistantLead->load('conversation.messages', 'candidature');

        return view('admin.assistant-leads.show', ['lead' => $assistantLead]);
    }

    public function update(Request $request, AssistantLeadCapture $assistantLead): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:nouveau,contacte,converti,perdu'],
            'priority' => ['required', 'in:chaud,tiede,froid'],
        ]);

        $assistantLead->update($data);

        return back()->with('status', 'Statut mis à jour.');
    }

    public function destroy(AssistantLeadCapture $assistantLead): RedirectResponse
    {
        $assistantLead->delete();

        return redirect()->route('admin.assistant-leads.index')->with('status', 'Prospect supprimé.');
    }
}
