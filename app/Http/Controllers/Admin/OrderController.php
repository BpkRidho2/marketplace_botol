<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function incoming()
    {
        $orders = Order::with('user', 'items.product')
            ->where('status', 'pending')
            ->latest()
            ->paginate(15);

        return Inertia::render('Admin/Orders/Incoming', [
            'orders' => $orders,
        ]);
    }

    public function confirmed()
    {
        $orders = Order::with('user', 'items.product')
            ->where('status', 'processing')
            ->latest()
            ->paginate(15);

        return Inertia::render('Admin/Orders/Confirmed', [
            'orders' => $orders,
        ]);
    }

    public function completed()
    {
        $orders = Order::with('user', 'items.product')
            ->where('status', 'completed')
            ->latest()
            ->paginate(15);

        return Inertia::render('Admin/Orders/Completed', [
            'orders' => $orders,
        ]);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,completed,cancelled'
        ]);

        $order->update(['status' => $request->status]);

        return back()->with('success', 'Status pesanan berhasil diubah menjadi ' . ucfirst(str_replace('_', ' ', $request->status)) . '.');
    }
}
