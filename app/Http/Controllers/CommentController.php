<?php

namespace App\Http\Controllers;

use App\Models\Actualite;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Actualite $actualite): RedirectResponse
    {
        abort_unless($actualite->published_at && $actualite->published_at <= now(), 404);

        $data = $request->validate([
            'author_name' => ['required', 'string', 'max:255'],
            'author_email' => ['required', 'email', 'max:255'],
            'body' => ['required', 'string', 'max:2000'],
            'website' => ['prohibited'],
        ]);

        $actualite->comments()->create([
            'author_name' => $data['author_name'],
            'author_email' => $data['author_email'],
            'body' => $data['body'],
        ]);

        return back()->with('status', 'Merci, votre commentaire a été envoyé et sera visible après validation.');
    }
}
