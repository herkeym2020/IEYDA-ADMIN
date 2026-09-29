<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProgramController extends Controller
{
    public function index()
    {
        $programs = Program::orderBy('order')->paginate(15);
        return view('admin.programs.index', compact('programs'));
    }

    public function create()
    {
        return view('admin.programs.create');
    }

    public function store(Request $request)
    {
        $mode = $request->input('draft_action', 'publish');

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'nullable|string|max:100',
            'image' => ($mode === 'publish') ? 'required|image|mimes:jpeg,png,jpg,webp|max:2048' : 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'category' => 'required|string|max:100',
            'duration' => 'nullable|string|max:100',
            'beneficiaries' => 'nullable|string|max:255',
            'budget' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'objectives' => 'nullable|array',
            'objectives.*' => 'nullable|string|max:255',
            'achievements' => 'nullable|array',
            'achievements.*' => 'nullable|string|max:255',
            'partners' => 'nullable|array',
            'partners.*' => 'nullable|string|max:255',
            'locations' => 'nullable|array',
            'locations.*' => 'nullable|string|max:255',
            'progress' => 'nullable|integer|min:0|max:100',
            'coordinator' => 'nullable|string|max:255',
            'order' => 'nullable|integer|min:0',
            'status' => 'required|in:active,inactive,upcoming',
            'scheduled_at' => ($mode === 'schedule') ? 'required|date' : 'nullable|date',
            'published_at' => 'nullable|date',
        ]);

        // Generate slug
        $validated['slug'] = Str::slug($validated['title']);
        
        // Ensure unique slug
        $count = 1;
        $originalSlug = $validated['slug'];
        while (Program::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $originalSlug . '-' . $count++;
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('programs', 'public');
        }

        // Filter out empty values from arrays
        $validated['objectives'] = array_values(array_filter($validated['objectives'] ?? [], fn($v) => !empty($v)));
        $validated['achievements'] = array_values(array_filter($validated['achievements'] ?? [], fn($v) => !empty($v)));
        $validated['partners'] = array_values(array_filter($validated['partners'] ?? [], fn($v) => !empty($v)));
        $validated['locations'] = array_values(array_filter($validated['locations'] ?? [], fn($v) => !empty($v)));
        $validated['is_active'] = $request->has('is_active');
        // Set order if not provided
        if (!isset($validated['order'])) {
            $validated['order'] = Program::max('order') + 1;
        }

        switch ($mode) {
            case 'schedule':
                $validated['publish_status'] = 'scheduled';
                $validated['scheduled_at'] = $validated['scheduled_at'];
                $validated['published_at'] = null;
                break;
            case 'draft':
            case 'save_draft':
                $validated['publish_status'] = 'draft';
                $validated['scheduled_at'] = null;
                $validated['published_at'] = null;
                break;
            default:
                $validated['publish_status'] = 'published';
                $validated['scheduled_at'] = null;
                $validated['published_at'] = $validated['published_at'] ?? now();
                break;
        }

        Program::create($validated);

        return redirect()->route('admin.programs.index')
            ->with('success', 'Program created successfully.');
    }

    public function edit(Program $program)
    {
        return view('admin.programs.edit', compact('program'));
    }

    public function update(Request $request, Program $program)
    {
        $mode = $request->input('draft_action', 'publish');

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'nullable|string|max:100',
            'image' => ($mode === 'publish' && !$program->image) ? 'required|image|mimes:jpeg,png,jpg,webp|max:2048' : 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'category' => 'required|string|max:100',
            'duration' => 'nullable|string|max:100',
            'beneficiaries' => 'nullable|string|max:255',
            'budget' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'objectives' => 'nullable|array',
            'objectives.*' => 'nullable|string|max:255',
            'achievements' => 'nullable|array',
            'achievements.*' => 'nullable|string|max:255',
            'partners' => 'nullable|array',
            'partners.*' => 'nullable|string|max:255',
            'locations' => 'nullable|array',
            'locations.*' => 'nullable|string|max:255',
            'progress' => 'nullable|integer|min:0|max:100',
            'coordinator' => 'nullable|string|max:255',
            'order' => 'nullable|integer|min:0',
            'status' => 'required|in:active,inactive,upcoming',
            'scheduled_at' => ($mode === 'schedule') ? 'required|date' : 'nullable|date',
            'published_at' => 'nullable|date',
        ]);

        // Update slug if title changed
        if ($validated['title'] !== $program->title) {
            $validated['slug'] = Str::slug($validated['title']);
            
            // Ensure unique slug
            $count = 1;
            $originalSlug = $validated['slug'];
            while (Program::where('slug', $validated['slug'])->where('id', '!=', $program->id)->exists()) {
                $validated['slug'] = $originalSlug . '-' . $count++;
            }
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($program->image) {
                Storage::disk('public')->delete($program->image);
            }
            $validated['image'] = $request->file('image')->store('programs', 'public');
        }

    // Filter out empty values from arrays
        $validated['objectives'] = array_values(array_filter($validated['objectives'] ?? [], fn($v) => !empty($v)));
        $validated['achievements'] = array_values(array_filter($validated['achievements'] ?? [], fn($v) => !empty($v)));
        $validated['partners'] = array_values(array_filter($validated['partners'] ?? [], fn($v) => !empty($v)));
        $validated['locations'] = array_values(array_filter($validated['locations'] ?? [], fn($v) => !empty($v)));
        $validated['is_active'] = $request->has('is_active');

        switch ($mode) {
            case 'schedule':
                $validated['publish_status'] = 'scheduled';
                $validated['scheduled_at'] = $validated['scheduled_at'];
                $validated['published_at'] = null;
                break;
            case 'draft':
            case 'save_draft':
                $validated['publish_status'] = 'draft';
                $validated['scheduled_at'] = null;
                $validated['published_at'] = null;
                break;
            default:
                $validated['publish_status'] = 'published';
                $validated['scheduled_at'] = null;
                $validated['published_at'] = $validated['published_at'] ?? now();
                break;
        }

        $program->update($validated);

        return redirect()->route('admin.programs.index')
            ->with('success', $mode === 'schedule' ? 'Program scheduled.' : ($mode === 'publish' ? 'Program updated and published.' : 'Program saved as draft.'));
    }

    public function destroy(Program $program)
    {
        // Delete associated image
        if ($program->image) {
            Storage::disk('public')->delete($program->image);
        }

        $program->delete();

        return redirect()->route('admin.programs.index')
            ->with('success', 'Program deleted successfully.');
    }

    public function bulkDelete(Request $request)
    {
        $ids = explode(',', $request->input('ids'));
        $programs = Program::whereIn('id', $ids)->get();
        foreach ($programs as $program) {
            if ($program->image) {
                Storage::disk('public')->delete($program->image);
            }
            $program->delete();
        }
        return redirect()->route('admin.programs.index')
            ->with('success', 'Selected programs deleted successfully.');
    }
}
