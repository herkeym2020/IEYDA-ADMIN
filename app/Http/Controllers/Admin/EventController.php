<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::latest()->paginate(15);
        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(Request $request)
    {
        $mode = $request->input('draft_action', 'publish');

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'event_date' => 'required|date',
            'event_time' => 'nullable|string|max:50',
            'location' => 'required|string|max:255',
            'organizer' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'registration_deadline' => 'nullable|date',
            'registration_fee' => 'nullable|string|max:100',
            'category' => 'required|string|max:100',
            'image' => ($mode === 'publish') ? 'required|image|mimes:jpeg,png,jpg,webp|max:2048' : 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'contact_email' => 'required|email|max:255',
            'highlights' => 'nullable|array',
            'highlights.*' => 'nullable|string|max:255',
            'speakers' => 'nullable|array',
            'speakers.*' => 'nullable|string|max:255',
            'outcomes' => 'nullable|array',
            'outcomes.*' => 'nullable|string|max:255',
            'registration_link' => 'nullable|url|max:500',
            'status' => 'required|in:upcoming,completed,cancelled',
            'scheduled_at' => ($mode === 'schedule') ? 'required|date' : 'nullable|date',
            'published_at' => 'nullable|date',
        ]);

        // Generate slug
        $validated['slug'] = Str::slug($validated['title']);
        
        // Ensure unique slug
        $count = 1;
        $originalSlug = $validated['slug'];
        while (Event::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $originalSlug . '-' . $count++;
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('events', 'public');
        }

    // Filter out empty values from arrays and convert to JSON
        $validated['highlights'] = array_values(array_filter($validated['highlights'] ?? [], fn($v) => !empty($v)));
        $validated['speakers'] = array_values(array_filter($validated['speakers'] ?? [], fn($v) => !empty($v)));
        $validated['outcomes'] = array_values(array_filter($validated['outcomes'] ?? [], fn($v) => !empty($v)));
        $validated['is_featured'] = $request->has('is_featured');

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

        Event::create($validated);

        return redirect()->route('admin.events.index')
            ->with('success', $mode === 'schedule' ? 'Event scheduled.' : ($mode === 'publish' ? 'Event published successfully.' : 'Event saved as draft.'));
    }

    public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $mode = $request->input('draft_action', 'publish');

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'event_date' => 'required|date',
            'event_time' => 'nullable|string|max:50',
            'location' => 'required|string|max:255',
            'organizer' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'registration_deadline' => 'nullable|date',
            'registration_fee' => 'nullable|string|max:100',
            'category' => 'required|string|max:100',
            'image' => ($mode === 'publish' && !$event->image) ? 'required|image|mimes:jpeg,png,jpg,webp|max:2048' : 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'contact_email' => 'required|email|max:255',
            'highlights' => 'nullable|array',
            'highlights.*' => 'nullable|string|max:255',
            'speakers' => 'nullable|array',
            'speakers.*' => 'nullable|string|max:255',
            'outcomes' => 'nullable|array',
            'outcomes.*' => 'nullable|string|max:255',
            'registration_link' => 'nullable|url|max:500',
            'status' => 'required|in:upcoming,completed,cancelled',
            'scheduled_at' => ($mode === 'schedule') ? 'required|date' : 'nullable|date',
            'published_at' => 'nullable|date',
        ]);

        // Update slug if title changed
        if ($validated['title'] !== $event->title) {
            $validated['slug'] = Str::slug($validated['title']);
            
            // Ensure unique slug
            $count = 1;
            $originalSlug = $validated['slug'];
            while (Event::where('slug', $validated['slug'])->where('id', '!=', $event->id)->exists()) {
                $validated['slug'] = $originalSlug . '-' . $count++;
            }
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }
            $validated['image'] = $request->file('image')->store('events', 'public');
        }

    // Filter out empty values from arrays and convert to JSON
        $validated['highlights'] = array_values(array_filter($validated['highlights'] ?? [], fn($v) => !empty($v)));
        $validated['speakers'] = array_values(array_filter($validated['speakers'] ?? [], fn($v) => !empty($v)));
        $validated['outcomes'] = array_values(array_filter($validated['outcomes'] ?? [], fn($v) => !empty($v)));
        $validated['is_featured'] = $request->has('is_featured');

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

        $event->update($validated);

        return redirect()->route('admin.events.index')
            ->with('success', $mode === 'schedule' ? 'Event scheduled.' : ($mode === 'publish' ? 'Event updated and published.' : 'Event saved as draft.'));
    }

    public function destroy(Event $event)
    {
        // Delete associated image
        if ($event->image) {
            Storage::disk('public')->delete($event->image);
        }

        $event->delete();

        return redirect()->route('admin.events.index')
            ->with('success', 'Event deleted successfully.');
    }

    public function bulkDelete(Request $request)
    {
        $ids = explode(',', $request->input('ids'));
        $events = Event::whereIn('id', $ids)->get();
        foreach ($events as $event) {
            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }
            $event->delete();
        }
        return redirect()->route('admin.events.index')
            ->with('success', 'Selected events deleted successfully.');
    }
}
