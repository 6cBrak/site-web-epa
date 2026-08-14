<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Antenne;
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
            'description_fr' => ['nullable', 'string'],
            'description_en' => ['nullable', 'string'],
            'active' => ['sometimes', 'boolean'],
        ]);

        $validated['active'] = $request->boolean('active');

        return $validated;
    }
}
