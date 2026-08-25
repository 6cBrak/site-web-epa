<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Antenne;
use App\Services\GoogleMapsLinkResolver;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AntenneController extends Controller
{
    public function index(): View
    {
        return view('admin.antennes.index', [
            'antennes' => Antenne::orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.antennes.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = ($data['slug'] ?? null) ?: Str::slug($data['name']);
        $data = $this->resolveMapCoordinates($data);

        Antenne::create($data);

        return redirect()->route('admin.antennes.index')->with('status', 'Antenne créée.');
    }

    public function edit(Antenne $antenne): View
    {
        return view('admin.antennes.edit', ['antenne' => $antenne]);
    }

    public function update(Request $request, Antenne $antenne): RedirectResponse
    {
        $data = $this->validated($request, $antenne->id);
        $data['slug'] = ($data['slug'] ?? null) ?: Str::slug($data['name']);

        if (($data['map_url'] ?? null) !== $antenne->map_url) {
            $data = $this->resolveMapCoordinates($data);
        }

        $antenne->update($data);

        return redirect()->route('admin.antennes.index')->with('status', 'Antenne mise à jour.');
    }

    public function destroy(Antenne $antenne): RedirectResponse
    {
        $antenne->delete();

        return redirect()->route('admin.antennes.index')->with('status', 'Antenne supprimée.');
    }

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'alpha_dash', 'unique:antennes,slug'.($ignoreId ? ",{$ignoreId}" : '')],
            'address' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'map_url' => ['nullable', 'url', 'max:500'],
            'description_fr' => ['nullable', 'string'],
            'description_en' => ['nullable', 'string'],
            'active' => ['sometimes', 'boolean'],
        ]);

        $validated['active'] = $request->boolean('active');

        return $validated;
    }

    /**
     * Si un lien Google Maps est fourni, en extrait les coordonnées GPS
     * (latitude/longitude) pour une carte précise sur le site public.
     */
    private function resolveMapCoordinates(array $data): array
    {
        if (empty($data['map_url'])) {
            $data['latitude'] = null;
            $data['longitude'] = null;

            return $data;
        }

        $coordinates = app(GoogleMapsLinkResolver::class)->resolveCoordinates($data['map_url']);

        $data['latitude'] = $coordinates['lat'] ?? null;
        $data['longitude'] = $coordinates['lng'] ?? null;

        return $data;
    }
}
