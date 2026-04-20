<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Inertia\Inertia;

class StockController extends Controller
{
    public function index()
    {
        $products = Product::with('category', 'shop')
            ->latest()
            ->paginate(15);

        return Inertia::render('Admin/Stock/Index', [
            'products' => $products,
        ]);
    }
}
