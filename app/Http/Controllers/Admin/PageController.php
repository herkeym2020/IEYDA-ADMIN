<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::all();
        return view('admin.pages.index', compact('pages'));
    }

    public function edit($slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();
        if ($slug === 'about') return view('admin.pages.edit_about', compact('page'));
        if ($slug === 'contact') return view('admin.pages.edit_contact', compact('page'));
        return view('admin.pages.edit', compact('page'));
    }

    public function update(Request $request, $slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'mission' => 'nullable|string',
            'vision' => 'nullable|string',
            'values' => 'nullable|string',
            'history' => 'nullable|string',
            'metadata' => 'nullable|json',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'timeline_images' => 'nullable|array',
            'timeline_images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'person_images' => 'nullable|array',
            'person_images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        if ($slug === 'ilorin-history' && isset($validated['metadata'])) {
            $metadata = json_decode($validated['metadata'], true) ?: [];
            $this->applyUploadedImages($request, $metadata, 'gallery', 'gallery_images');
            $this->applyUploadedImages($request, $metadata, 'timeline', 'timeline_images');
            $this->applyUploadedImages($request, $metadata, 'people', 'person_images');
            $validated['metadata'] = $metadata;
        } else {
            $validated['metadata'] = isset($validated['metadata']) ? json_decode($validated['metadata'], true) : null;
        }

        unset($validated['gallery_images'], $validated['timeline_images'], $validated['person_images']);
        $page->update($validated);
        return redirect()->route('admin.pages.index')->with('success', 'Page updated successfully.');
    }

    private function applyUploadedImages(Request $request, array &$metadata, string $collection, string $inputName): void
    {
        foreach ($request->file($inputName, []) as $index => $file) {
            if (!$file || !$file->isValid()) continue;
            $path = $file->store('history', 'public');
            if (isset($metadata[$collection][$index]) && is_array($metadata[$collection][$index])) {
                $metadata[$collection][$index]['image'] = Storage::url($path);
            }
        }
    }
}
