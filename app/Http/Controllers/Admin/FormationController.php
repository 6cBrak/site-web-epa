<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Antenne;
use App\Models\Formation;
use App\Models\Programme;
use App\Services\ImageOptimizer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class FormationController extends Controller
{
    public function index(): View
    {
        return view('admin.formations.index', [
            'formations' => Formation::with('programme')->orderBy('order')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.formations.create', [
            'programmes' => Programme::orderBy('order')->get(),
            'antennes' => Antenne::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = ($data['slug'] ?? null) ?: Str::slug($data['title_fr']);

        if ($request->hasFile('image')) {
            $data['image'] = app(ImageOptimizer::class)->store($request->file('image'), 'formations', maxWidth: 1600);
        }

        $formation = Formation::create($data);
        $formation->antennes()->sync($request->input('antenne_ids', []));

        return redirect()->route('admin.formations.index')->with('status', 'Formation créée.');
    }

    public function edit(Formation $formation): View
    {
        return view('admin.formations.edit', [
            'formation' => $formation,
            'programmes' => Programme::orderBy('order')->get(),
            'antennes' => Antenne::orderBy('name')->get(),
            'selectedAntenneIds' => $formation->antennes()->pluck('antennes.id')->all(),
        ]);
    }

    public function update(Request $request, Formation $formation): RedirectResponse
    {
        $data = $this->validated($request, $formation->id);
        $data['slug'] = ($data['slug'] ?? null) ?: Str::slug($data['title_fr']);

        if ($request->hasFile('image')) {
            if ($formation->image) {
                Storage::disk('public')->delete($formation->image);
            }
            $data['image'] = app(ImageOptimizer::class)->store($request->file('image'), 'formations', maxWidth: 1600);
        }

        $formation->update($data);
        $formation->antennes()->sync($request->input('antenne_ids', []));

        return redirect()->route('admin.formations.index')->with('status', 'Formation mise à jour.');
    }

    public function destroy(Formation $formation): RedirectResponse
    {
        if ($formation->image) {
            Storage::disk('public')->delete($formation->image);
        }

        $formation->delete();

        return redirect()->route('admin.formations.index')->with('status', 'Formation supprimée.');
    }

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        $validated = $request->validate([
            'programme_id' => ['required', 'exists:programmes,id'],
            'title_fr' => ['required', 'string', 'max:255'],
            'title_en' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'alpha_dash', 'unique:formations,slug'.($ignoreId ? ",{$ignoreId}" : '')],
            'image' => ['nullable', 'image', 'max:4096'],
            'description_fr' => ['nullable', 'string'],
            'description_en' => ['nullable', 'string'],
            'objectives_fr' => ['nullable', 'string'],
            'objectives_en' => ['nullable', 'string'],
            'modules_fr' => ['nullable', 'string'],
            'modules_en' => ['nullable', 'string'],
            'prerequisites_fr' => ['nullable', 'string'],
            'prerequisites_en' => ['nullable', 'string'],
            'duration' => ['nullable', 'string', 'max:100'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'order' => ['nullable', 'integer', 'min:0'],
            'published' => ['sometimes', 'boolean'],
            'antenne_ids' => ['nullable', 'array'],
            'antenne_ids.*' => ['exists:antennes,id'],
        ]);

        $validated['published'] = $request->boolean('published');
        $validated['order'] = $validated['order'] ?? 0;

        unset($validated['antenne_ids']);

        return $validated;
    }
}
