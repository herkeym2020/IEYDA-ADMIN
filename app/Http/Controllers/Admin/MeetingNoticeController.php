<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MeetingNotice;
use Illuminate\Http\Request;

class MeetingNoticeController extends Controller
{
    public function index()
    {
        return view('admin.meeting-notices.index', ['notices' => MeetingNotice::orderByDesc('starts_at')->paginate(20)]);
    }

    public function create()
    {
        return view('admin.meeting-notices.form', ['notice' => new MeetingNotice()]);
    }

    public function store(Request $request)
    {
        MeetingNotice::create($this->validated($request));
        return redirect()->route('admin.meeting-notices.index')->with('success', 'Meeting notice created.');
    }

    public function edit(MeetingNotice $meeting_notice)
    {
        return view('admin.meeting-notices.form', ['notice' => $meeting_notice]);
    }

    public function update(Request $request, MeetingNotice $meeting_notice)
    {
        $meeting_notice->update($this->validated($request));
        return redirect()->route('admin.meeting-notices.index')->with('success', 'Meeting notice updated.');
    }

    public function destroy(MeetingNotice $meeting_notice)
    {
        $meeting_notice->delete();
        return redirect()->route('admin.meeting-notices.index')->with('success', 'Meeting notice deleted.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'meeting_type' => 'nullable|string|max:100',
            'summary' => 'required|string|max:1000',
            'details' => 'nullable|string',
            'starts_at' => 'required|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
            'location' => 'nullable|string|max:255',
            'action_label' => 'nullable|string|max:80',
            'action_url' => 'nullable|url|max:500',
            'priority' => 'nullable|integer|min:0|max:999',
        ]);
        $data['is_active'] = $request->boolean('is_active');
        $data['show_popup'] = $request->boolean('show_popup');
        return $data;
    }
}
