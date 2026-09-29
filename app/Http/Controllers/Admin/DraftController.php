<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{News, Event, Program};
use Illuminate\Http\Request;

class DraftController extends Controller
{
    public function index()
    {
        // Show drafts across content types
        $newsDrafts = News::where('publish_status', 'draft')->orderBy('updated_at', 'desc')->get();
        $eventDrafts = Event::where('publish_status', 'draft')->orderBy('updated_at', 'desc')->get();
        $programDrafts = Program::where('publish_status', 'draft')->orderBy('updated_at', 'desc')->get();

        return view('admin.drafts.index', compact('newsDrafts', 'eventDrafts', 'programDrafts'));
    }

    public function scheduled()
    {
        // Show scheduled content across types
        $newsScheduled = News::where('publish_status', 'scheduled')->orderBy('scheduled_at', 'asc')->get();
        $eventScheduled = Event::where('publish_status', 'scheduled')->orderBy('scheduled_at', 'asc')->get();
        $programScheduled = Program::where('publish_status', 'scheduled')->orderBy('scheduled_at', 'asc')->get();

        return view('admin.drafts.scheduled', compact('newsScheduled', 'eventScheduled', 'programScheduled'));
    }

    public function publish(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:news,events,programs',
            'id' => 'required|integer',
        ]);

        $modelMap = [
            'news' => News::class,
            'events' => Event::class,
            'programs' => Program::class,
        ];

        $modelClass = $modelMap[$validated['type']];
        $item = $modelClass::findOrFail($validated['id']);

        $item->publish_status = 'published';
        $item->published_at = $item->scheduled_at ?? now();
        if ($item instanceof News) {
            $item->is_published = true;
        }
        $item->scheduled_at = null;
        $item->save();

        return back()->with('success', ucfirst($validated['type']).' published successfully');
    }
}

