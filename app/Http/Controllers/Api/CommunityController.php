<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommunityResource;
use App\Models\Community;
use Illuminate\Http\Request;

class CommunityController extends Controller
{
    // List all approved communities
    public function index()
    {
        return CommunityResource::collection(Community::approved()->get());
    }

    // List all communities (admin)
    public function all()
    {
        return CommunityResource::collection(Community::all());
    }

    // Store a new community registration (pending by default)
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'lga' => 'required|string|max:255',
            'description' => 'nullable|string',
            'contact_name' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:32',
            'position' => 'nullable|string|max:255',
            'address' => 'nullable|string',
        ]);
        $data['status'] = 'pending';
        $community = Community::create($data);
        return new CommunityResource($community);
    }

    // Approve a pending community
    public function approve(Community $community)
    {
        $community->update(['status' => 'approved']);
        return new CommunityResource($community);
    }

    // Decline a pending community
    public function decline(Community $community)
    {
        $community->update(['status' => 'declined']);
        return new CommunityResource($community);
    }

    // Delete a community (admin only)
    public function destroy(Community $community)
    {
        $community->delete();
        return response()->json(['message' => 'Community deleted.']);
    }
}
