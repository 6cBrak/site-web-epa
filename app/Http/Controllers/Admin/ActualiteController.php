<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Actualite;
use App\Services\ImageOptimizer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ActualiteController extends Controller
{
    public function index(): View
    {
        return view('admin.actualites.index', [
            'actualites' => Actualite::orderByDesc('published_at')->orderByDesc('created_at')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.actualites.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = ($data['slug'] ?? null) ?: Str::slug($data['title_fr']);

        if ($request->hasFile('image')) {
            $data['image'] = app(ImageOptimizer::class)->store($request->file('image'), 'actualites', maxWidth: 1600);
        }

        Actualite::create($data);

        return redirect()->route('admin.actualites.index')->with('status', 'Actualité créée.');
    }

    public function edit(Actualite $actualite): View
    {
        return view('admin.actualites.edit', ['actualite' => $actualite]);
    }

    public function update(Request $request, Actualite $actualite): RedirectResponse
    {
        $data = $this->validated($request, $actualite->id, $actualite);
        $data['slug'] = ($data['slug'] ?? null) ?: Str::slug($data['title_fr']);

        if ($request->hasFile('image')) {
            if ($actualite->image) {
                Storage::disk('public')->delete($actualite->image);
            }
            $data['image'] = app(ImageOptimizer::class)->store($request->file('image'), 'actualites', maxWidth: 1600);
        }

        $actualite->update($data);

        return redirect()->route('admin.actualites.index')->with('status', 'Actualité mise à jour.');
    }

    public function destroy(Actualite $actualite): RedirectResponse
    {
        if ($actualite->image) {
            Storage::disk('public')->delete($actualite->image);
        }

        $actualite->delete();

        return redirect()->route('admin.actualites.index')->with('status', 'Actualité supprimée.');
    }

    private function validated(Request $request, ?int $ignoreId = null, ?Actualite $actualite = null): array
    {
        $validated = $request->validate([
            'title_fr' => ['required', 'string', 'max:255'],
            'title_en' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'alpha_dash', 'unique:actualites,slug'.($ignoreId ? ",{$ignoreId}" : '')],
            'image' => ['nullable', 'image', 'max:4096'],
            'excerpt_fr' => ['nullable', 'string'],
            'excerpt_en' => ['nullable', 'string'],
            'content_fr' => ['nullable', 'string'],
            'content_en' => ['nullable', 'string'],
            'published' => ['sometimes', 'boolean'],
        ]);

        $validated['published_at'] = $request->boolean('published')
            ? ($actualite?->published_at ?? now())
            : null;
        unset($validated['published']);

        return $validated;
    }
}
