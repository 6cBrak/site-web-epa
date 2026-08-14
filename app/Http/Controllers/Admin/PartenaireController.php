<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partenaire;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PartenaireController extends Controller
{
    public function index(): View
    {
        return view('admin.partenaires.index', [
            'partenaires' => Partenaire::orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.partenaires.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('partenaires', 'public');
        }

        Partenaire::create($data);

        return redirect()->route('admin.partenaires.index')->with('status', 'Partenaire créé.');
    }

    public function edit(Partenaire $partenaire): View
    {
        return view('admin.partenaires.edit', ['partenaire' => $partenaire]);
    }

    public function update(Request $request, Partenaire $partenaire): RedirectResponse
    {
        $data = $this->validated($request);

        if ($request->hasFile('logo')) {
            if ($partenaire->logo) {
                Storage::disk('public')->delete($partenaire->logo);
            }
            $data['logo'] = $request->file('logo')->store('partenaires', 'public');
        }

        $partenaire->update($data);

        return redirect()->route('admin.partenaires.index')->with('status', 'Partenaire mis à jour.');
    }

    public function destroy(Partenaire $partenaire): RedirectResponse
    {
        if ($partenaire->logo) {
            Storage::disk('public')->delete($partenaire->logo);
        }

        $partenaire->delete();

        return redirect()->route('admin.partenaires.index')->with('status', 'Partenaire supprimé.');
    }

    private function validated(Request $request): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'in:etat,ong,association,entreprise,ambassade'],
            'logo' => ['nullable', 'image', 'max:4096'],
            'website' => ['nullable', 'url', 'max:255'],
            'active' => ['sometimes', 'boolean'],
        ]);

        $validated['active'] = $request->boolean('active');

        return $validated;
    }
}
