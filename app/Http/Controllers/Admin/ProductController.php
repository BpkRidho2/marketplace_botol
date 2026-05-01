<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Barryvdh\DomPDF\Facade\Pdf;

class ProductController extends Controller
{
    /**
     * Mengambil toko milik admin yang sedang login.
     * Jika belum punya toko, otomatis dibuatkan satu.
     * Dengan cara ini, admin tidak perlu setup manual toko dulu
     * sebelum bisa menambahkan produk.
     */
private function getAdminShop()
{
    // Menggunakan Shop::first() agar tidak tergantung pada id tertentu.
    // Ini lebih aman daripada Shop::find(2) karena kalau suatu saat
    // database di-reset, kode ini tetap berjalan dengan benar.
    $shop = Shop::first();

    // Fallback defensif: kalau somehow toko belum ada, buat baru.
    // Dalam kondisi normal ini tidak akan pernah terpanggil
    // karena toko sudah kita buat manual di database.
    if (!$shop) {
        $shop = Shop::create([
            'user_id'   => 1,
            'name'      => 'Jordan Plastik',
            'slug'      => 'jordan-plastik-' . time(),
            'is_active' => 1,
        ]);
    }

    return $shop;
}

    public function index()
    {
        $products = Product::with('category', 'shop')
            ->latest()
            ->paginate(15);

        return Inertia::render('Admin/Products/Index', [
            'products' => $products,
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Products/Create', [
            'categories' => Category::all(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'              => 'required|string|max:255',
            'category_id'       => 'required|exists:categories,id',
            'price'             => 'required|numeric|min:0',
            'stock'             => 'required|integer|min:0',
            'description'       => 'nullable|string',
            'image'             => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gallery_images'    => 'nullable|array',
            'gallery_images.*'  => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['name', 'category_id', 'price', 'stock', 'description']);
        
        // Slug dibuat dari nama produk
        $data['slug'] = Str::slug($request->name);

        // Upload gambar utama jika ada
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        // Upload galeri jika ada
        if ($request->hasFile('gallery_images')) {
            $gallery = [];
            foreach ($request->file('gallery_images') as $image) {
                $gallery[] = $image->store('products/gallery', 'public');
            }
            $data['gallery_images'] = $gallery;
        }

        // Gunakan helper getAdminShop() yang aman — tidak akan null
        $data['shop_id'] = $this->getAdminShop()->id;

        Product::create($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        return Inertia::render('Admin/Products/Edit', [
            'product'    => $product->load('category'),
            'categories' => Category::all(),
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'              => 'required|string|max:255',
            'category_id'       => 'required|exists:categories,id',
            'price'             => 'required|numeric|min:0',
            'stock'             => 'required|integer|min:0',
            'description'       => 'nullable|string',
            'image'             => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gallery_images'    => 'nullable|array',
            'gallery_images.*'  => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['name', 'category_id', 'price', 'stock', 'description']);
        
        // Perbaikan bug: slug juga harus diperbarui saat nama berubah
        $data['slug'] = Str::slug($request->name);

        // Ganti gambar utama jika ada yang baru diupload
        if ($request->hasFile('image')) {
            // Hapus gambar lama dari storage agar tidak menumpuk
            if ($product->image && file_exists(storage_path('app/public/' . $product->image))) {
                unlink(storage_path('app/public/' . $product->image));
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        // Ganti galeri jika ada yang baru diupload
        if ($request->hasFile('gallery_images')) {
            // Hapus galeri lama
            if ($product->gallery_images && is_array($product->gallery_images)) {
                foreach ($product->gallery_images as $oldImage) {
                    if (file_exists(storage_path('app/public/' . $oldImage))) {
                        unlink(storage_path('app/public/' . $oldImage));
                    }
                }
            }
            $gallery = [];
            foreach ($request->file('gallery_images') as $image) {
                $gallery[] = $image->store('products/gallery', 'public');
            }
            $data['gallery_images'] = $gallery;
        }

        $product->update($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function export()
    {
        // Ambil semua produk milik toko admin (aman, tidak akan null)
        $adminShopId = $this->getAdminShop()->id;
        
        $products = Product::with('category', 'shop')
            ->where('shop_id', $adminShopId)
            ->latest()
            ->get();

        $html = '<style>
            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
            th { background-color: #f2f2f2; font-weight: bold; }
            h1 { text-align: center; margin-bottom: 20px; }
        </style>
        <h1>Daftar Produk</h1>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Stock</th>
                </tr>
            </thead>
            <tbody>';

        foreach ($products as $index => $product) {
            $html .= '<tr>
                <td>' . ($index + 1) . '</td>
                <td>' . htmlspecialchars($product->name) . '</td>
                <td>' . ($product->category->name ?? '-') . '</td>
                <td>Rp ' . number_format($product->price, 0, ',', '.') . '</td>
                <td>' . $product->stock . ' pcs</td>
            </tr>';
        }

        $html .= '</tbody></table>';

        $pdf = Pdf::loadHTML($html);
        return $pdf->download('daftar_produk_' . date('Y-m-d') . '.pdf');
    }
}