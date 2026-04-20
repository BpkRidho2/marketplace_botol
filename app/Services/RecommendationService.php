<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class RecommendationService
{
    /**
     * Get recommendations for the authenticated user using User-Based Collaborative Filtering.
     *
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRecommendations($limit = 4)
    {
        $userId = Auth::id();
        if (!$userId) {
            return Product::with(['category', 'shop'])->inRandomOrder()->take($limit)->get();
        }

        // 1. Get all reviews to build the matrix
        $allReviews = Review::all(['user_id', 'product_id', 'rating']);

        if ($allReviews->isEmpty()) {
            return Product::with(['category', 'shop'])->inRandomOrder()->take($limit)->get();
        }

        // 2. Build User-Product Matrix
        $matrix = [];
        $userRatings = [];
        foreach ($allReviews as $review) {
            $matrix[$review->user_id][$review->product_id] = $review->rating;
            if ($review->user_id == $userId) {
                $userRatings[$review->product_id] = $review->rating;
            }
        }

        if (empty($userRatings)) {
            // If current user has no ratings, return popular or random products
            return Product::with(['category', 'shop'])->withAvg('reviews', 'rating')->orderByDesc('reviews_avg_rating')->take($limit)->get();
        }

        // 3. Calculate similarity between current user and other users (Cosine Similarity)
        $similarities = [];
        foreach ($matrix as $otherUserId => $otherRatings) {
            if ($otherUserId == $userId)
                continue;

            $similarities[$otherUserId] = $this->calculateCosineSimilarity($userRatings, $otherRatings);
        }

        // Sort by highest similarity
        arsort($similarities);

        // 4. Get product recommendations from similar users
        $recommendations = [];
        $topSimilarUsers = array_slice($similarities, 0, 5, true); // Top 5 similar users

        foreach ($topSimilarUsers as $otherUserId => $similarity) {
            if ($similarity <= 0)
                continue;

            foreach ($matrix[$otherUserId] as $productId => $rating) {
                // If current user hasn't rated this product yet
                if (!isset($userRatings[$productId])) {
                    if (!isset($recommendations[$productId])) {
                        $recommendations[$productId] = 0;
                    }
                    // Weighted rating based on user similarity
                    $recommendations[$productId] += $rating * $similarity;
                }
            }
        }

        if (empty($recommendations)) {
            return Product::with(['category', 'shop'])->inRandomOrder()->take($limit)->get();
        }

        arsort($recommendations);
        $productIds = array_keys(array_slice($recommendations, 0, $limit, true));

        return Product::with(['category', 'shop'])->whereIn('id', $productIds)->get();
    }

    /**
     * Calculate Cosine Similarity between two vectors (user ratings).
     */
    private function calculateCosineSimilarity($vec1, $vec2)
    {
        $dotProduct = 0;
        $norm1 = 0;
        $norm2 = 0;

        $allProductIds = array_unique(array_merge(array_keys($vec1), array_keys($vec2)));

        foreach ($allProductIds as $id) {
            $val1 = $vec1[$id] ?? 0;
            $val2 = $vec2[$id] ?? 0;

            $dotProduct += $val1 * $val2;
            $norm1 += pow($val1, 2);
            $norm2 += pow($val2, 2);
        }

        if ($norm1 == 0 || $norm2 == 0)
            return 0;

        return $dotProduct / (sqrt($norm1) * sqrt($norm2));
    }
}
