<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminSearchService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    protected $searchService;

    public function __construct(AdminSearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        $type = $request->get('type');

        if (!$query || strlen($query) < 2) {
            return response()->json(['results' => []]);
        }

        $results = $this->searchService->search($query, $type);

        return response()->json([
            'results' => $results->values(),
            'count' => $results->count(),
            'query' => $query,
        ]);
    }
}
