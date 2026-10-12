<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Antenne;
use App\Models\Evenement;
use App\Services\ImageOptimizer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class EvenementController extends Controller
{
    public function index(): View
    {
        return view('admin.evenements.index', [
            'evenements' => Evenement::with('antenne')
                ->withCount('inscriptions')
                ->orderByDesc('starts_at')
                ->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.evenements.create', ['antennes' => $this->antennes()]);
    }

    public function store(Request $request): RedirectResponse
    {
        [$data, $sessions] = $this->validated($request);
        $data['slug'] = $this->uniqueSlug($data['slug'] ?? null, $data['title_fr']);

        if ($request->hasFile('image')) {
            $data['image'] = app(ImageOptimizer::class)->store($request->file('image'), 'evenements', maxWidth: 1600);
        }

        DB::transaction(function () use ($data, $sessions) {
            $evenement = Evenement::create($data);
            $this->syncSessions($evenement, $sessions);
        });

        return redirect()->route('admin.evenements.index')->with('status', 'Événement créé.');
    }

    public function edit(Evenement $evenement): View
    {
        return view('admin.evenements.edit', [
            'evenement' => $evenement->load('sessions'),
            'antennes' => $this->antennes(),
        ]);
    }

    public function update(Request $request, Evenement $evenement): RedirectResponse
    {
        [$data, $sessions] = $this->validated($request, $evenement->id);
        $data['slug'] = $this->uniqueSlug($data['slug'] ?? null, $data['title_fr'], $evenement->id);

        if ($request->hasFile('image')) {
            if ($evenement->image) {
                Storage::disk('public')->delete($evenement->image);
            }
            $data['image'] = app(ImageOptimizer::class)->store($request->file('image'), 'evenements', maxWidth: 1600);
        }

        DB::transaction(function () use ($evenement, $data, $sessions) {
            $evenement->update($data);
            $this->syncSessions($evenement, $sessions);
        });

        return redirect()->route('admin.evenements.index')->with('status', 'Événement mis à jour.');
    }

    public function destroy(Evenement $evenement): RedirectResponse
    {
        if ($evenement->image) {
            Storage::disk('public')->delete($evenement->image);
        }

        $evenement->delete();

        return redirect()->route('admin.evenements.index')->with('status', 'Événement supprimé.');
    }

    private function antennes()
    {
        return Antenne::where('active', true)->orderBy('name')->get();
    }

    private function uniqueSlug(?string $slug, string $title, ?int $ignoreId = null): string
    {
        $base = $slug ?: Str::slug($title);
        $candidate = $base;
        $i = 2;

        while (Evenement::where('slug', $candidate)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $candidate = $base.'-'.$i++;
        }

        return $candidate;
    }

    /** @return array{0: array, 1: array} */
    private function validated(Request $request, ?int $ignoreId = null): array
    {
        $validated = $request->validate([
            'title_fr' => ['required', 'string', 'max:255'],
            'title_en' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'alpha_dash'],
            'type' => ['required', 'in:'.implode(',', array_keys(Evenement::TYPES))],
            'description_fr' => ['nullable', 'string'],
            'description_en' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:4096'],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'antenne_id' => ['nullable', 'exists:antennes,id'],
            'location' => ['nullable', 'string', 'max:255'],
            'online_url' => ['nullable', 'url', 'max:255'],
            'sessions' => ['nullable', 'array'],
            'sessions.*.id' => ['nullable', 'integer'],
            'sessions.*.title_fr' => ['required', 'string', 'max:255'],
            'sessions.*.title_en' => ['nullable', 'string', 'max:255'],
            'sessions.*.starts_at' => ['nullable', 'date'],
            'sessions.*.capacity' => ['nullable', 'integer', 'min:1'],
        ]);

        $sessions = $validated['sessions'] ?? [];
        unset($validated['sessions']);

        foreach (['registration_open', 'is_featured', 'published'] as $flag) {
            $validated[$flag] = $request->boolean($flag);
        }

        return [$validated, array_values($sessions)];
    }

    private function syncSessions(Evenement $evenement, array $sessions): void
    {
        $keptIds = [];

        foreach ($sessions as $position => $row) {
            $attributes = [
                'title_fr' => $row['title_fr'],
                'title_en' => ($row['title_en'] ?? null) ?: $row['title_fr'],
                'starts_at' => ($row['starts_at'] ?? null) ?: null,
                'capacity' => ($row['capacity'] ?? null) ?: null,
                'position' => $position,
            ];

            $session = ! empty($row['id'])
                ? $evenement->sessions()->whereKey($row['id'])->first()
                : null;

            if ($session) {
                $session->update($attributes);
            } else {
                $session = $evenement->sessions()->create($attributes);
            }

            $keptIds[] = $session->id;
        }

        $evenement->sessions()->whereNotIn('id', $keptIds)->delete();
    }
}
