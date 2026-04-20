<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Inertia\Inertia;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function incoming()
    {
        $reviews = Review::with('user', 'product')
            ->where('status', 'perlu_cek')
            ->latest()
            ->paginate(15);

        return Inertia::render('Admin/Reviews/Incoming', [
            'reviews' => $reviews,
        ]);
    }

    public function index()
    {
        $reviews = Review::with('user', 'product')
            ->where('status', 'sudah_upload')
            ->latest()
            ->paginate(15);

        return Inertia::render('Admin/Reviews/Index', [
            'reviews' => $reviews,
        ]);
    }

    public function destroy(Review $review)
    {
        $review->delete();

        return redirect()->route('admin.reviews.index')->with('success', 'Ulasan berhasil dihapus.');
    }

    public function approve(Review $review)
    {
        $review->update(['status' => 'sudah_upload']);

        return redirect()->back()->with('success', 'Ulasan berhasil diapprove.');
    }
}
