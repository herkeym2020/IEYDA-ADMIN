<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MeetingNoticeResource;
use App\Http\Resources\MonthlyRealizationResource;
use App\Models\MeetingNotice;
use App\Models\MonthlyRealization;
use App\Models\Page;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FeatureContentController extends Controller
{
    public function notices(Request $request)
    {
        return MeetingNoticeResource::collection(
            MeetingNotice::popup()->orderByDesc('priority')->orderBy('starts_at')->limit(5)->get()
        );
    }

    public function realizations(Request $request)
    {
        return MonthlyRealizationResource::collection(
            MonthlyRealization::latestFeatured()->limit(6)->get()
        );
    }

    public function history(Request $request): JsonResponse
    {
        $page = Page::where('slug', 'ilorin-history')->first();
        if (!$page) return response()->json(['data' => null]);
        return response()->json(['data' => [
            'slug' => $page->slug,
            'title' => $page->title,
            'content' => $page->content,
            'metadata' => $page->metadata ?? [],
        ]]);
    }
}
