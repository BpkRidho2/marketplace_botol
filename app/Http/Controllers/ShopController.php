<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ShopController extends Controller
{
    public function create()
    {
        if (auth()->user()->shop) {
            return redirect()->route('seller.products.index');
        }

        return Inertia::render('Shop/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:shops,name',
            'description' => 'required|string',
            'address' => 'required|string',
            'phone' => 'required|string',
        ]);

        auth()->user()->shop()->create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'address' => $request->address,
            'phone' => $request->phone,
        ]);

        return redirect()->route('home')->with('success', 'Toko Anda berhasil dibuka!');
    }

    public function show(Shop $shop)
    {
        return Inertia::render('Shop/Show', [
            'shop' => $shop->load('products'),
        ]);
    }
}
