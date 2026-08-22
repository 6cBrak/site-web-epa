<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AssistantConversation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AssistantConversationController extends Controller
{
    public function index(Request $request): View
    {
        $query = AssistantConversation::withCount('messages')
            ->with(['leadsCaptures' => fn ($q) => $q->latest('id')->limit(1)])
            ->with(['messages' => fn ($q) => $q->where('role', 'user')->oldest('id')->limit(1)]);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('messages', fn ($q) => $q->where('content', 'like', "%{$search}%"));
        }

        return view('admin.assistant-conversations.index', [
            'conversations' => $query->latest('updated_at')->paginate(20)->withQueryString(),
        ]);
    }

    public function show(AssistantConversation $assistantConversation): View
    {
        $assistantConversation->load('messages', 'leadsCaptures');

        return view('admin.assistant-conversations.show', ['conversation' => $assistantConversation]);
    }

    public function destroy(AssistantConversation $assistantConversation): RedirectResponse
    {
        $assistantConversation->delete();

        return redirect()->route('admin.assistant-conversations.index')->with('status', 'Conversation supprimée.');
    }
}
