<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Programme;
use App\Services\ImageOptimizer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProgrammeController extends Controller
{
    public function index(): View
    {
        return view('admin.programmes.index', [
            'programmes' => Programme::withCount('formations')->orderBy('order')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.programmes.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = ($data['slug'] ?? null) ?: Str::slug($data['name_fr']);

        if ($request->hasFile('icon')) {
            $data['icon'] = app(ImageOptimizer::class)->store($request->file('icon'), 'programmes', maxWidth: 200);
        }

        Programme::create($data);

        return redirect()->route('admin.programmes.index')->with('status', 'Programme créé.');
    }

    public function edit(Programme $programme): View
    {
        return view('admin.programmes.edit', ['programme' => $programme]);
    }

    public function update(Request $request, Programme $programme): RedirectResponse
    {
        $data = $this->validated($request, $programme->id);
        $data['slug'] = ($data['slug'] ?? null) ?: Str::slug($data['name_fr']);

        if ($request->hasFile('icon')) {
            if ($programme->icon) {
                Storage::disk('public')->delete($programme->icon);
            }
            $data['icon'] = app(ImageOptimizer::class)->store($request->file('icon'), 'programmes', maxWidth: 200);
        }

        $programme->update($data);

        return redirect()->route('admin.programmes.index')->with('status', 'Programme mis à jour.');
    }

    public function destroy(Programme $programme): RedirectResponse
    {
        if ($programme->icon) {
            Storage::disk('public')->delete($programme->icon);
        }

        $programme->delete();

        return redirect()->route('admin.programmes.index')->with('status', 'Programme supprimé.');
    }

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        $validated = $request->validate([
            'name_fr' => ['required', 'string', 'max:255'],
            'name_en' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'alpha_dash', 'unique:programmes,slug'.($ignoreId ? ",{$ignoreId}" : '')],
            'description_fr' => ['nullable', 'string'],
            'description_en' => ['nullable', 'string'],
            'icon' => ['nullable', 'image', 'max:2048'],
            'color' => ['nullable', 'string', 'max:20'],
            'order' => ['nullable', 'integer', 'min:0'],
            'active' => ['sometimes', 'boolean'],
        ]);

        $validated['active'] = $request->boolean('active');
        $validated['order'] = $validated['order'] ?? 0;

        return $validated;
    }
}
