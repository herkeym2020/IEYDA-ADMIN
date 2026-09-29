<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSlide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HeroSlideController extends Controller
{
    public function index()
    {
        $heroSlides = HeroSlide::orderBy('order')->paginate(15);
        return view('admin.hero-slides.index', compact('heroSlides'));
    }

    public function create()
    {
        return view('admin.hero-slides.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'subtitle' => 'required|max:255',
            'description' => 'required',
            'badge' => 'required|max:255',
            'motto' => 'required|max:255',
            'yoruba_text' => 'required|max:255',
            'primary_image' => 'required|image|max:2048',
            'overlay_image' => 'nullable|image|max:2048',
            'ctas' => 'required|array|min:1',
            'ctas.*.text' => 'required|string|max:255',
            'ctas.*.link' => 'required|string|max:255',
            'order' => 'required|integer',
        ]);

        if ($request->hasFile('primary_image')) {
            $validated['primary_image'] = $request->file('primary_image')->store('hero-slides', 'public');
        }
        if ($request->hasFile('overlay_image')) {
            $validated['overlay_image'] = $request->file('overlay_image')->store('hero-slides', 'public');
        }
        $validated['is_active'] = $request->has('is_active');
        // Only keep ctas as array
        $validated['ctas'] = array_values(array_filter($validated['ctas'], function($cta) {
            return !empty($cta['text']) && !empty($cta['link']);
        }));
        HeroSlide::create($validated);

        return redirect()->route('admin.hero-slides.index')
            ->with('success', 'Hero slide created successfully.');
    }

    public function edit(HeroSlide $heroSlide)
    {
        return view('admin.hero-slides.edit', compact('heroSlide'));
    }

    public function update(Request $request, HeroSlide $heroSlide)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'subtitle' => 'required|max:255',
            'description' => 'required',
            'badge' => 'required|max:255',
            'motto' => 'required|max:255',
            'yoruba_text' => 'required|max:255',
            'primary_image' => 'nullable|image|max:2048',
            'overlay_image' => 'nullable|image|max:2048',
            'ctas' => 'required|array|min:1',
            'ctas.*.text' => 'required|string|max:255',
            'ctas.*.link' => 'required|string|max:255',
            'order' => 'required|integer',
        ]);

        if ($request->hasFile('primary_image')) {
            if ($heroSlide->primary_image) {
                Storage::disk('public')->delete($heroSlide->primary_image);
            }
            $validated['primary_image'] = $request->file('primary_image')->store('hero-slides', 'public');
        }
        if ($request->hasFile('overlay_image')) {
            if ($heroSlide->overlay_image) {
                Storage::disk('public')->delete($heroSlide->overlay_image);
            }
            $validated['overlay_image'] = $request->file('overlay_image')->store('hero-slides', 'public');
        }
        $validated['is_active'] = $request->has('is_active');
        $validated['ctas'] = array_values(array_filter($validated['ctas'], function($cta) {
            return !empty($cta['text']) && !empty($cta['link']);
        }));
        $heroSlide->update($validated);

        return redirect()->route('admin.hero-slides.index')
            ->with('success', 'Hero slide updated successfully.');
    }

    public function destroy(HeroSlide $heroSlide)
    {
        if ($heroSlide->primary_image) {
            Storage::disk('public')->delete($heroSlide->primary_image);
        }
        if ($heroSlide->overlay_image) {
            Storage::disk('public')->delete($heroSlide->overlay_image);
        }

        $heroSlide->delete();

        return redirect()->route('admin.hero-slides.index')
            ->with('success', 'Hero slide deleted successfully.');
    }
}
