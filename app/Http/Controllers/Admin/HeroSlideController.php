<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSlide;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class HeroSlideController extends Controller
{
    public function index(): View
    {
        return view('admin.hero-slides.index', [
            'heroSlides' => HeroSlide::orderBy('order')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.hero-slides.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request, true);

        $data['image'] = $request->file('image')->store('hero-slides', 'public');

        HeroSlide::create($data);

        return redirect()->route('admin.hero-slides.index')->with('status', 'Image ajoutée.');
    }

    public function edit(HeroSlide $heroSlide): View
    {
        return view('admin.hero-slides.edit', ['heroSlide' => $heroSlide]);
    }

    public function update(Request $request, HeroSlide $heroSlide): RedirectResponse
    {
        $data = $this->validated($request, false);

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($heroSlide->image);
            $data['image'] = $request->file('image')->store('hero-slides', 'public');
        }

        $heroSlide->update($data);

        return redirect()->route('admin.hero-slides.index')->with('status', 'Image mise à jour.');
    }

    public function destroy(HeroSlide $heroSlide): RedirectResponse
    {
        Storage::disk('public')->delete($heroSlide->image);
        $heroSlide->delete();

        return redirect()->route('admin.hero-slides.index')->with('status', 'Image supprimée.');
    }

    private function validated(Request $request, bool $imageRequired): array
    {
        $validated = $request->validate([
            'image' => [$imageRequired ? 'required' : 'nullable', 'image', 'max:4096'],
            'caption_fr' => ['nullable', 'string', 'max:255'],
            'caption_en' => ['nullable', 'string', 'max:255'],
            'order' => ['nullable', 'integer', 'min:0'],
            'active' => ['sometimes', 'boolean'],
        ]);

        $validated['order'] = $validated['order'] ?? 0;
        $validated['active'] = $request->boolean('active');

        return $validated;
    }
}
