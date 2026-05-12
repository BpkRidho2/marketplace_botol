<?php

namespace App\Http\Controllers;

use App\Services\RecommendationService;
use Illuminate\Http\Request;

class RecommendationController extends Controller
{
    protected RecommendationService $recommendationService;

    public function __construct(RecommendationService $recommendationService)
    {
        // Laravel akan otomatis inject RecommendationService melalui
        // dependency injection — kita tidak perlu membuat instance manual
        $this->recommendationService = $recommendationService;
    }

    /**
     * Mengembalikan rekomendasi produk untuk user yang sedang login.
     * Dipanggil via AJAX dari halaman home agar halaman tidak lambat load.
     */
    public function index(Request $request)
    {
        $userId = auth()->id();
        $limit  = $request->get('limit', 5);

        $recommendations = $this->recommendationService->getRecommendations(
            $userId, $limit
        );

        return response()->json([
            'success' => true,
            'data'    => $recommendations,
            'count'   => $recommendations->count(),
        ]);
    }
}