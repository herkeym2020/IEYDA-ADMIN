<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

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
        if ($slug === 'about') {
            return view('admin.pages.edit_about', compact('page'));
        }
        if ($slug === 'contact') {
            return view('admin.pages.edit_contact', compact('page'));
        }
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
        ]);
        $validated['metadata'] = isset($validated['metadata']) ? json_decode($validated['metadata'], true) : null;
        $page->update($validated);
        return redirect()->route('admin.pages.index')->with('success', 'Page updated successfully.');
    }
}
