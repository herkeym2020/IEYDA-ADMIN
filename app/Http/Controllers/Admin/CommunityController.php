<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Community;
use Illuminate\Http\Request;

class CommunityController extends Controller
{
    public function index()
    {
        $communities = Community::orderByDesc('created_at')->get();
        return view('admin.communities.index', compact('communities'));
    }

    public function create()
    {
        return view('admin.communities.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'lga' => 'required|string|max:255',
            'description' => 'nullable|string',
            'contact_name' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:32',
        ]);
        $data['status'] = 'approved';
        Community::create($data);
        return redirect()->route('admin.communities.index')->with('success', 'Community added.');
    }

    public function edit(Community $community)
    {
        return view('admin.communities.edit', compact('community'));
    }

    public function update(Request $request, Community $community)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'lga' => 'required|string|max:255',
            'description' => 'nullable|string',
            'contact_name' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:32',
        ]);
        $community->update($data);
        return redirect()->route('admin.communities.index')->with('success', 'Community updated.');
    }

    public function approve(Community $community)
    {
        $community->update(['status' => 'approved']);
        return redirect()->route('admin.communities.index')->with('success', 'Community approved.');
    }

    public function decline(Community $community)
    {
        $community->update(['status' => 'declined']);
        return redirect()->route('admin.communities.index')->with('success', 'Community declined.');
    }

    public function destroy(Community $community)
    {
        $community->delete();
        return redirect()->route('admin.communities.index')->with('success', 'Community deleted.');
    }

    public function bulkDelete(Request $request)
    {
        $ids = explode(',', $request->input('ids'));
        Community::whereIn('id', $ids)->delete();
        return redirect()->route('admin.communities.index')->with('success', 'Selected communities deleted.');
    }
}
