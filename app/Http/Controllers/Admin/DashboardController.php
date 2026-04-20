<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $orders = Order::with('user', 'items.product')
            ->where('status', 'pending')
            ->latest()
            ->paginate(15);

        return Inertia::render('Admin/Dashboard/Index', [
            'orders' => $orders,
        ]);
    }
}
