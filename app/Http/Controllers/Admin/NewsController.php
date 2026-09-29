<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::latest()->paginate(15);
        return view('admin.news.index', compact('news'));
    }

    public function create()
    {
        return view('admin.news.create');
    }

    public function store(Request $request)
    {
        $mode = $request->input('draft_action', 'publish');

        $rules = [
            'title' => 'required|max:255',
            'excerpt' => 'nullable',
            'content' => 'required',
            'category' => 'required|max:255',
            'image' => ($mode === 'publish') ? 'required|image|max:2048' : 'nullable|image|max:2048',
            'author' => 'nullable|max:255',
            'read_time' => 'nullable|max:255',
            'published_at' => 'nullable|date',
            'scheduled_at' => ($mode === 'schedule') ? 'required|date' : 'nullable|date',
        ];

        $validated = $request->validate($rules);

        // Prepare base payload
        $payload = [
            'title' => $validated['title'],
            'excerpt' => $validated['excerpt'] ?? '',
            'content' => $validated['content'],
            'category' => $validated['category'],
            'author' => $validated['author'] ?? Auth::user()->name,
            'read_time' => $validated['read_time'] ?? null,
            'is_featured' => $request->has('is_featured'),
            'is_published' => false,
            'published_at' => null,
            'scheduled_at' => null,
            'publish_status' => 'draft',
        ];

        // Handle image if provided
        if ($request->hasFile('image')) {
            $payload['image'] = $request->file('image')->store('news', 'public');
        }

        $payload['slug'] = Str::slug($payload['title']);

        switch ($mode) {
            case 'schedule':
                $payload['publish_status'] = 'scheduled';
                $payload['scheduled_at'] = $validated['scheduled_at'];
                break;
            case 'draft':
            case 'save_draft':
                $payload['publish_status'] = 'draft';
                break;
            default:
                $payload['publish_status'] = 'published';
                $payload['published_at'] = $validated['published_at'] ?? now();
                $payload['is_published'] = true;
                break;
        }

        News::create($payload);

        return redirect()->route('admin.news.index')
            ->with('success', $mode === 'schedule' ? 'News scheduled successfully.' : ($mode === 'publish' ? 'News article published successfully.' : 'News saved as draft.'));
    }

    public function edit(News $news)
    {
        return view('admin.news.edit', compact('news'));
    }

    public function update(Request $request, News $news)
    {
        $mode = $request->input('draft_action', 'publish');

        $rules = [
            'title' => 'required|max:255',
            'excerpt' => 'nullable',
            'content' => 'required',
            'category' => 'required|max:255',
            'image' => ($mode === 'publish' && !$news->image) ? 'required|image|max:2048' : 'nullable|image|max:2048',
            'author' => 'nullable|max:255',
            'read_time' => 'nullable|max:255',
            'published_at' => 'nullable|date',
            'scheduled_at' => ($mode === 'schedule') ? 'required|date' : 'nullable|date',
        ];

        $validated = $request->validate($rules);

        $payload = [
            'title' => $validated['title'],
            'excerpt' => $validated['excerpt'] ?? '',
            'content' => $validated['content'],
            'category' => $validated['category'],
            'author' => $validated['author'] ?? $news->author,
            'read_time' => $validated['read_time'] ?? $news->read_time,
            'is_featured' => $request->has('is_featured'),
            'is_published' => false,
            'published_at' => null,
            'scheduled_at' => null,
            'publish_status' => 'draft',
        ];

        if ($request->hasFile('image')) {
            if ($news->image) {
                Storage::disk('public')->delete($news->image);
            }
            $payload['image'] = $request->file('image')->store('news', 'public');
        } else {
            $payload['image'] = $news->image;
        }

        $payload['slug'] = Str::slug($payload['title']);

        switch ($mode) {
            case 'schedule':
                $payload['publish_status'] = 'scheduled';
                $payload['scheduled_at'] = $validated['scheduled_at'];
                break;
            case 'draft':
            case 'save_draft':
                $payload['publish_status'] = 'draft';
                break;
            default:
                $payload['publish_status'] = 'published';
                $payload['published_at'] = $validated['published_at'] ?? now();
                $payload['is_published'] = true;
                break;
        }

        $news->update($payload);

        return redirect()->route('admin.news.index')
            ->with('success', $mode === 'schedule' ? 'News scheduled successfully.' : ($mode === 'publish' ? 'News article updated and published.' : 'News saved as draft.'));
    }

    public function destroy(News $news)
    {
        if ($news->image) {
            Storage::disk('public')->delete($news->image);
        }

        $news->delete();

        return redirect()->route('admin.news.index')
            ->with('success', 'News article deleted successfully.');
    }

    public function bulkDelete(Request $request)
    {
        $ids = explode(',', $request->input('ids'));
        $newsItems = News::whereIn('id', $ids)->get();
        foreach ($newsItems as $news) {
            if ($news->image) {
                Storage::disk('public')->delete($news->image);
            }
            $news->delete();
        }
        return redirect()->route('admin.news.index')
            ->with('success', 'Selected news articles deleted successfully.');
    }
}
