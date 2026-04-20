<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index(Request $request, \App\Services\RecommendationService $recommendationService)
    {
        $products = Product::with(['category', 'shop'])
            ->when($request->category, function ($query, $category) {
                return $query->whereHas('category', function ($q) use ($category) {
                    $q->where('slug', $category);
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $orders = [];
        if (auth()->check()) {
            $orders = auth()->user()->orders()->latest()->get()->toArray();
        }

        return Inertia::render('Products/Index', [
            'products' => $products,
            'categories' => Category::all(),
            'filters' => $request->only(['category']),
            'recommendations' => $recommendationService->getRecommendations(4),
            'orders' => $orders,
        ]);
    }

    public function show(Product $product)
    {
        return Inertia::render('Products/Show', [
            'product' => $product->load(['category', 'shop', 'reviews.user'])
        ]);
    }
}