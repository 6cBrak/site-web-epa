<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CommentController extends Controller
{
    public function index(Request $request): View
    {
        $query = Comment::with('actualite');

        if ($request->filled('status')) {
            $query->where('approved', $request->input('status') === 'approuve');
        }

        return view('admin.comments.index', [
            'comments' => $query->latest()->paginate(20)->withQueryString(),
        ]);
    }

    public function update(Request $request, Comment $comment): RedirectResponse
    {
        $comment->update(['approved' => true]);

        return back()->with('status', 'Commentaire approuvé.');
    }

    public function destroy(Comment $comment): RedirectResponse
    {
        $comment->delete();

        return back()->with('status', 'Commentaire supprimé.');
    }
}
