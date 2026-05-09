<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Services\SearchService;
use Illuminate\Http\Request;

class GlobalSearchController extends Controller
{
    protected $searchService;

    public function __construct(SearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    /**
     * Recherche instantanée
     */
    public function instant(Request $request)
    {
        $term = $request->get('q');
        $results = $this->searchService->globalSearch($term);

        return response()->json([
            'success' => true,
            'results' => $results
        ]);
    }
}
