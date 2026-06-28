<?php

namespace App\Services;

use App\Models\Review;
use App\Models\Product;
use Illuminate\Support\Collection;

class RecommendationService
{
    /**
     * Jumlah tetangga (neighbor) yang digunakan dalam perhitungan CF.
     */
    private int $k = 5;

    /**
     * Minimal produk yang harus sama-sama dirating dua user
     * agar similarity dipakai sebagai neighbor.
     */
    private int $minCoRatedItems = 2;

    /**
     * Metode utama yang dipanggil dari controller.
     * Menerima user_id dan limit, mengembalikan koleksi produk rekomendasi.
     */
    public function getRecommendations(int $userId, int $limit = 5): Collection
    {
        // Langkah 1: Ambil semua rating yang sudah diverifikasi
        $allRatings = Review::where('status', 'sudah_upload')
            ->select('user_id', 'product_id', 'rating')
            ->get();

        // Langkah 2: Bangun matriks user-produk
        // Struktur: $matrix[user_id][product_id] = rating
        $matrix = $this->buildMatrix($allRatings);

        // Jika user belum punya rating (user baru atau guest dengan ID=0),
        // langsung fallback ke produk terpopuler
        if (!isset($matrix[$userId])) {
            return $this->getPopularProducts($limit);
        }

        // Langkah 3: Hitung cosine similarity user aktif vs semua user lain
        $similarities = $this->calculateSimilarities($userId, $matrix);

        // Langkah 4: Ambil K tetangga dengan similarity tertinggi
        $neighbors = $this->getTopNeighbors($similarities, $userId);

        // Langkah 5: Prediksi rating untuk produk yang belum pernah di-rating user
        $recommendations = $this->predictAndRecommend($userId, $matrix, $neighbors, $limit);

        // Jika hasil CF kosong (semua produk sudah di-rating), fallback ke populer
        if ($recommendations->isEmpty()) {
            return $this->getPopularProducts($limit);
        }

        // Langkah 6: Ambil data produk lengkap beserta relasi yang dibutuhkan Vue
        return Product::with(['category', 'shop'])
            ->whereIn('id', $recommendations->keys())
            ->get()
            ->map(function ($product) use ($recommendations) {
                // Sisipkan prediksi rating ke objek produk agar bisa ditampilkan di frontend
                $product->predicted_rating = round($recommendations[$product->id], 2);
                return $product;
            })
            ->sortByDesc('predicted_rating')
            ->values();
    }

    /**
     * Membangun matriks user-produk dari data rating mentah.
     * Hasilnya adalah array 2 dimensi: $matrix[user_id][product_id] = rating
     */
    private function buildMatrix(Collection $ratings): array
    {
        $matrix = [];
        foreach ($ratings as $rating) {
            $matrix[$rating->user_id][$rating->product_id] = $rating->rating;
        }
        return $matrix;
    }

    /**
     * Menghitung Cosine Similarity antara user aktif dengan semua user lain.
     *
     * Rumus: sim(A,B) = (A·B) / (||A|| × ||B||)
     * Hasil antara 0 (tidak mirip) sampai 1 (identik).
     */
    private function calculateSimilarities(int $userId, array $matrix): array
    {
        $similarities = [];
        $userRatings  = $matrix[$userId];

        foreach ($matrix as $otherUserId => $otherRatings) {
            if ($otherUserId === $userId) continue;

            // Hanya hitung dot product pada produk yang sama-sama di-rating
            $commonProducts = array_intersect_key($userRatings, $otherRatings);

            $jumlahCoRatedItems = count($commonProducts);

            if   ($jumlahCoRatedItems < $this->minCoRatedItems) {
                    $similarities[$otherUserId] = 0;
                    continue;
            }

            $dotProduct = 0;
            foreach ($commonProducts as $productId => $_) {
                $dotProduct += $userRatings[$productId] * $otherRatings[$productId];
            }

            // Magnitude dihitung hanya dari co-rated items (konsisten dengan dot product)
            // Ini adalah implementasi Basic Cosine Similarity sesuai rumus standar
            $magnitudeUser  = sqrt(array_sum(array_map(fn($r) => $r * $r,
                    array_intersect_key($userRatings, $commonProducts))));
            $magnitudeOther = sqrt(array_sum(array_map(fn($r) => $r * $r,
                    array_intersect_key($otherRatings, $commonProducts))));

            if ($magnitudeUser == 0 || $magnitudeOther == 0) {
                $similarities[$otherUserId] = 0;
                continue;
            }

            $similarities[$otherUserId] = $dotProduct / ($magnitudeUser * $magnitudeOther);
        }

        return $similarities;
    }

    /**
     * Mengambil K user dengan similarity tertinggi sebagai "tetangga".
     */
    private function getTopNeighbors(array $similarities, int $userId): array
    {
        arsort($similarities);
        $neighbors = array_filter($similarities, fn($sim) => $sim > 0);
        return array_slice($neighbors, 0, $this->k, true);
    }

    /**
     * Memprediksi rating untuk produk yang BELUM di-rating oleh user aktif.
     *
     * Rumus: pred(u,p) = Σ(sim(u,v) × rating(v,p)) / Σ|sim(u,v)|
     */
    private function predictAndRecommend(
        int $userId,
        array $matrix,
        array $neighbors,
        int $limit
    ): Collection {
        $userRatings = $matrix[$userId];
        $predictions = [];

        foreach ($neighbors as $neighborId => $similarity) {
            $neighborRatings = $matrix[$neighborId] ?? [];

            foreach ($neighborRatings as $productId => $rating) {
                // Lewati produk yang sudah pernah di-rating user — tidak direkomendasikan ulang
                if (isset($userRatings[$productId])) continue;

                if (!isset($predictions[$productId])) {
                    $predictions[$productId] = ['numerator' => 0, 'denominator' => 0];
                }

                $predictions[$productId]['numerator']   += $similarity * $rating;
                $predictions[$productId]['denominator'] += abs($similarity);
            }
        }

        $predictedRatings = collect();
        foreach ($predictions as $productId => $values) {
            if ($values['denominator'] > 0) {
                $predictedRatings[$productId] = $values['numerator'] / $values['denominator'];
            }
        }

        return $predictedRatings->sortDesc()->take($limit);
    }

    /**
     * Fallback: produk terpopuler berdasarkan rata-rata rating.
     * Digunakan untuk guest (userId=0) dan user yang belum punya riwayat rating.
     */
    private function getPopularProducts(int $limit): Collection
    {
        return Product::with(['category', 'shop'])
            ->withAvg('reviews', 'rating')
            ->having('reviews_avg_rating', '>', 0)
            ->orderByDesc('reviews_avg_rating')
            ->limit($limit)
            ->get()
            ->map(function ($product) {
                $product->predicted_rating = round($product->reviews_avg_rating, 2);
                return $product;
            });
    }
}