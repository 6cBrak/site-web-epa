<?php

namespace App\Http\Controllers;

use App\Models\Actualite;
use App\Models\Antenne;
use App\Models\KeyStat;
use App\Models\Partenaire;
use App\Models\Programme;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class PageController extends Controller
{
    public function home(): View
    {
        return view('public.home', [
            'programmes' => Programme::where('active', true)->orderBy('order')->withCount('formations')->get(),
            'keyStats' => KeyStat::orderBy('order')->get(),
            'antennes' => Antenne::where('active', true)->orderBy('name')->get(),
            'actualites' => Actualite::whereNotNull('published_at')
                ->where('published_at', '<=', now())
                ->orderByDesc('published_at')
                ->take(3)
                ->get(),
            'partenaires' => Partenaire::where('active', true)->get(),
        ]);
    }

    public function about(): View
    {
        return view('public.about', [
            'antennes' => Antenne::where('active', true)->orderBy('name')->get(),
        ]);
    }

    public function contact(): View
    {
        return view('public.contact', [
            'antennes' => Antenne::where('active', true)->orderBy('name')->get(),
        ]);
    }

    public function contactStore(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
        ]);

        $recipient = Antenne::where('active', true)->whereNotNull('email')->value('email');

        if ($recipient) {
            Mail::raw(
                "Nom: {$data['name']}\nEmail: {$data['email']}\nSujet: {$data['subject']}\n\n{$data['message']}",
                function ($message) use ($recipient, $data) {
                    $message->to($recipient)
                        ->replyTo($data['email'], $data['name'])
                        ->subject('[Contact site web] '.$data['subject']);
                }
            );
        }

        return back()->with('status', 'Votre message a bien été envoyé. Nous vous répondrons rapidement.');
    }
}
