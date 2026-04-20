# User-Based Collaborative Filtering - Dokumentasi Lengkap

## 📖 Daftar Isi

1. [Pengenalan](#pengenalan)
2. [Konsep Dasar](#konsep-dasar)
3. [Bagaimana Cara Kerjanya](#bagaimana-cara-kerjanya)
4. [Implementasi di Project](#implementasi-di-project)
5. [Algoritma Langkah-Langkah](#algoritma-langkah-langkah)
6. [Contoh Praktis](#contoh-praktis)
7. [Keuntungan & Kekurangan](#keuntungan--kekurangan)
8. [Referensi](#referensi)

---

## 📝 Pengenalan

**User-Based Collaborative Filtering** adalah algoritma yang digunakan untuk memberikan rekomendasi produk kepada pengguna berdasarkan preferensi pengguna lain yang memiliki selera serupa.

### Ide Pokok:

> "Jika User A dan User B memiliki preferensi yang sama terhadap produk-produk yang pernah mereka topai, maka produk yang disukai User B tetapi belum diberi rating oleh User A akan direkomendasikan kepada User A."

**Contoh Real-World:**

- User A memberi rating 5 bintang untuk botol minuman merk X dan Y
- User B juga memberi rating 5 bintang untuk botol minuman merk X dan Y
- Selain itu, User B memberi rating 5 bintang untuk botol merk Z
- Karena User A dan B punya selera serupa, botol merk Z akan direkomendasikan kepada User A

---

## 🎯 Konsep Dasar

### 1. **User-Product Matrix**

Matriks yang menunjukkan rating setiap user terhadap setiap produk:

```
         Produk 1  Produk 2  Produk 3  Produk 4
User 1      5         3         -         4
User 2      4         -         5         3
User 3      -         2         4         5
User 4      3         4         3         -
```

Penjelasan:

- `-` (dash) = user belum memberi rating
- Angka (1-5) = rating yang diberikan user

### 2. **User Similarity (Kesamaan Pengguna)**

Ukuran seberapa mirip preferensi dua user. Semakin tinggi nilainya, semakin mirip selera mereka.

**Formula Cosine Similarity:**

```
similarity(User A, User B) =
    (dot product User A dan User B) / (norm User A × norm User B)

Dimana:
- dot product = jumlah hasil perkalian rating user untuk produk yang sama
- norm = √(jumlah kuadrat setiap rating)
```

### 3. **Weighted Recommendation**

Rekomendasi produk berdasarkan rating dari user-user serupa, diberi bobot sesuai tingkat kesamaan mereka.

---

## 🔄 Bagaimana Cara Kerjanya

### Alur Umum:

```
┌─────────────────────────────────────┐
│  User X Mencari Rekomendasi         │
└──────────────┬──────────────────────┘
               │
               ▼
┌─────────────────────────────────────┐
│  1. Ambil Semua Rating dari Database│
│     (Bangun User-Product Matrix)    │
└──────────────┬──────────────────────┘
               │
               ▼
┌─────────────────────────────────────┐
│  2. Hitung Similarity User X dengan │
│     Semua User Lain menggunakan     │
│     Cosine Similarity               │
└──────────────┬──────────────────────┘
               │
               ▼
┌─────────────────────────────────────┐
│  3. Ambil Top N User dengan         │
│     Similarity Tertinggi            │
└──────────────┬──────────────────────┘
               │
               ▼
┌─────────────────────────────────────┐
│  4. Lihat Produk yang Disukai Top N │
│     User Tapi Belum di-rate User X  │
└──────────────┬──────────────────────┘
               │
               ▼
┌─────────────────────────────────────┐
│  5. Hitung Weighted Score dari      │
│     Rating × Similarity             │
└──────────────┬──────────────────────┘
               │
               ▼
┌─────────────────────────────────────┐
│  6. Urutkan dan Return Top N        │
│     Rekomendasi Produk              │
└─────────────────────────────────────┘
```

---

## 🛠️ Implementasi di Project

File implementasi: [`app/Services/RecommendationService.php`](app/Services/RecommendationService.php)

### Struktur Kode:

```php
class RecommendationService {
    public function getRecommendations($limit = 4)
    // Main method untuk mendapatkan rekomendasi

    private function calculateCosineSimilarity($vec1, $vec2)
    // Helper method untuk menghitung cosine similarity
}
```

### Di Mana Digunakan?

Cek file berikut untuk penggunaan:

```bash
grep -r "RecommendationService" app/ --include="*.php"
# Output akan menunjukkan di mana service ini digunakan
```

---

## 📊 Algoritma Langkah-Langkah

### Step 1: Build User-Product Matrix

```php
$allReviews = Review::all(['user_id', 'product_id', 'rating']);

$matrix = [];
$userRatings = [];
foreach ($allReviews as $review) {
    // Matriks[user_id][product_id] = rating
    $matrix[$review->user_id][$review->product_id] = $review->rating;

    // Simpan rating dari user yang request
    if ($review->user_id == $currentUserId) {
        $userRatings[$review->product_id] = $review->rating;
    }
}
```

**Output:**

```
$matrix = [
    1 => [1 => 5, 2 => 3, 4 => 4],      // User 1
    2 => [1 => 4, 3 => 5, 4 => 3],      // User 2
    3 => [2 => 2, 3 => 4, 4 => 5],      // User 3
]

$userRatings = [1 => 5, 2 => 3, 4 => 4]  // Rating dari User yang request
```

### Step 2: Calculate User Similarity (Cosine Similarity)

```php
foreach ($matrix as $otherUserId => $otherRatings) {
    if ($otherUserId == $userId) continue;

    // Hitung similarity antara current user dan other user
    $similarities[$otherUserId] =
        $this->calculateCosineSimilarity($userRatings, $otherRatings);
}

arsort($similarities); // Urutkan dari tertinggi ke terendah
```

**Implementasi Cosine Similarity:**

```php
private function calculateCosineSimilarity($vec1, $vec2) {
    $dotProduct = 0;
    $norm1 = 0;
    $norm2 = 0;

    // Ambil semua product ID yang ada di kedua vector
    $allProductIds = array_unique(
        array_merge(array_keys($vec1), array_keys($vec2))
    );

    foreach ($allProductIds as $id) {
        $val1 = $vec1[$id] ?? 0;  // Rating user 1 (atau 0 jika tidak ada)
        $val2 = $vec2[$id] ?? 0;  // Rating user 2 (atau 0 jika tidak ada)

        $dotProduct += $val1 * $val2;
        $norm1 += pow($val1, 2);
        $norm2 += pow($val2, 2);
    }

    if ($norm1 == 0 || $norm2 == 0) return 0;

    // Cosine Similarity = dot product / (norm1 × norm2)
    return $dotProduct / (sqrt($norm1) * sqrt($norm2));
}
```

**Visualisasi Perhitungan:**

```
User A ratings:  [5, 3, 0, 4]  (untuk produk 1, 2, 3, 4)
User B ratings:  [4, 0, 5, 3]

dot product = (5×4) + (3×0) + (0×5) + (4×3) = 20 + 0 + 0 + 12 = 32
norm A = √(5² + 3² + 0² + 4²) = √(25 + 9 + 0 + 16) = √50 ≈ 7.07
norm B = √(4² + 0² + 5² + 3²) = √(16 + 0 + 25 + 9) = √50 ≈ 7.07

similarity = 32 / (7.07 × 7.07) = 32 / 50 = 0.64
```

Range nilai similarity adalah 0 sampai 1:

- 0 = Tidak ada kesamaan sama sekali
- 1 = Kesamaan sempurna

### Step 3: Get Top N Similar Users

```php
$topSimilarUsers = array_slice($similarities, 0, 5, true);
// Ambil 5 user dengan similarity tertinggi
```

### Step 4: Find Candidate Products

```php
$recommendations = [];

foreach ($topSimilarUsers as $otherUserId => $similarity) {
    if ($similarity <= 0) continue;

    foreach ($matrix[$otherUserId] as $productId => $rating) {
        // Jika current user belum rate produk ini
        if (!isset($userRatings[$productId])) {
            if (!isset($recommendations[$productId])) {
                $recommendations[$productId] = 0;
            }

            // Akumulasi weighted rating
            $recommendations[$productId] += $rating * $similarity;
        }
    }
}
```

**Contoh Perhitungan:**

```
Product 5 (belum di-rate oleh User X):
- User A (similarity 0.8) memberi rating 5
  Kontribusi = 5 * 0.8 = 4.0

- User B (similarity 0.6) memberi rating 4
  Kontribusi = 4 * 0.6 = 2.4

- User C (similarity 0.5) memberi rating 3
  Kontribusi = 3 * 0.5 = 1.5

Total weighted score = 4.0 + 2.4 + 1.5 = 7.9
```

### Step 5: Sort & Return

```php
arsort($recommendations);  // Urutkan descending
$productIds = array_keys(
    array_slice($recommendations, 0, $limit, true)
);

return Product::with(['category', 'shop'])
    ->whereIn('id', $productIds)
    ->get();
```

---

## 💡 Contoh Praktis

### Scenario: Marketplace Botol

**Kondisi:**

- User X telah memberi rating untuk botol tipe A (5 bintang) dan tipe B (4 bintang)
- User Y memberi rating botol tipe A (5 bintang), tipe B (4 bintang), dan tipe C (5 bintang)
- User Z memberi rating botol tipe D (1 bintang) dan tipe E (2 bintang)

**Langkah-Langkah:**

1. **Build Matrix:**

```
       Tipe A  Tipe B  Tipe C  Tipe D  Tipe E
User X   5       4       -       -       -
User Y   5       4       5       -       -
User Z   -       -       -       1       2
```

2. **Hitung Similarity User X dengan lainnya:**

```
Similarity(X, Y):
- Produk yang sama: A(5,5), B(4,4)
- dot = 5×5 + 4×4 = 25 + 16 = 41
- norm X = √(25+16) = √41 ≈ 6.4
- norm Y = √(25+16+25) = √66 ≈ 8.1
- similarity X-Y = 41 / (6.4 × 8.1) ≈ 0.79

Similarity(X, Z):
- Produk yang sama: NONE
- dot = 0
- similarity X-Z = 0
```

3. **Rekomendasi terbaik:**

```
Karena similarity(X,Y) ≈ 0.79 (sangat tinggi), dan User Y memberi rating 5 untuk Tipe C,
maka Tipe C akan direkomendasikan ke User X dengan score tinggi.
```

---

## ✅ Keuntungan & Kekurangan

### ✨ Keuntungan:

| Keuntungan               | Penjelasan                                                                        |
| ------------------------ | --------------------------------------------------------------------------------- |
| **Intuitif**             | Logika mudah dipahami: "Jika A dan B mirip, rekomendasikan hal yang C sukai ke A" |
| **Tidak Perlu Feature**  | Tidak perlu tahu detail produk (warna, ukuran, material)                          |
| **Personalisasi**        | Rekomendasi berbeda untuk setiap user                                             |
| **Sederhana & Cepat**    | Implementasi straightforward dan tidak butuh banyak resource                      |
| **Bisa Menemukan Niche** | Bisa rekomendasi produk niche jika ada user dengan selera mirip                   |

### ⚠️ Kekurangan:

| Kekurangan             | Penjelasan                                                                    |
| ---------------------- | ----------------------------------------------------------------------------- |
| **Cold Start Problem** | User baru tanpa history rating tidak bisa dapat rekomendasi yang baik         |
| **Scalability**        | Semakin banyak user, semakin lambat perhitungan similarity                    |
| **Sparsity**           | Jika rating data sedikit (kebanyakan produk tidak di-rate), akurasi berkurang |
| **Gray Sheep**         | User dengan preferensi unik tidak akan punya "user serupa"                    |
| **Hanya Rating Based** | Tidak bisa memanfaatkan data lain (kategori, brand, harga)                    |
| **Popularity Bias**    | Rekomendasi akan mengikuti tren (produk populer diprioritaskan)               |

---

## 🔧 Optimalisasi

### 1. **Pembatasan User Similarity**

Buang user dengan similarity terlalu rendah (threshold):

```php
if ($similarity < 0.3) continue;  // Minimal 0.3 similarity
```

### 2. **Limit Similar Users**

Hanya gunakan top 5-10 user paling mirip:

```php
$topSimilarUsers = array_slice($similarities, 0, 5, true);
// Menghemat CPU, tetap akurat
```

### 3. **Caching**

Cache hasil perhitungan similarity:

```php
Cache::remember("similarity.user.{$userId}", 3600, function() {
    // Calculate similarity
    return $similarities;
});
```

### 4. **Hybrid Approach**

Kombinasikan user-based dengan item-based atau content-based:

```php
- 50% dari User-Based Collaborative Filtering
- 50% dari Content-Based (rekomendasi produk serupa berdasarkan kategori)
```

### 5. **Cold Start Handling**

Untuk user baru tanpa rating:

```php
if (empty($userRatings)) {
    // Return produk populer (highest average rating)
    return Product::withAvg('reviews', 'rating')
        ->orderByDesc('reviews_avg_rating')
        ->take($limit)
        ->get();
}
```

---

## 📚 Formula Mathematika

### Cosine Similarity Formula

$$\text{similarity}(A, B) = \frac{A \cdot B}{\|A\| \|B\|} = \frac{\sum_{i=1}^{n} A_i \times B_i}{\sqrt{\sum_{i=1}^{n} A_i^2} \times \sqrt{\sum_{i=1}^{n} B_i^2}}$$

### Weighted Recommendation Score

$$\text{score}(item) = \sum_{u \in \text{similar users}} \text{similarity}(user, u) \times \text{rating}_u(item)$$

### Euclidean Distance (alternatif untuk similarity)

$$\text{distance}(A, B) = \sqrt{\sum_{i=1}^{n} (A_i - B_i)^2}$$

Semakin kecil distance, semakin mirip.

---

## 🧪 Testing & Validation

### Manual Testing:

```php
// Dalam tinker atau test
php artisan tinker

$service = app(\App\Services\RecommendationService::class);
$recommendations = $service->getRecommendations(4);

$recommendations->each(function($product) {
    echo $product->name . "\n";
});
```

### Metrics Evaluation:

1. **Precision**: Berapa persen rekomendasi yang user sukai?
2. **Recall**: Berapa persen produk yang user sukai yang ter-rekomendasi?
3. **RMSE**: Mean squared error antara rating prediksi vs actual
4. **Coverage**: Berapa persen produk yang bisa di-rekomendasi?

---

## 📖 Referensi

### Library & Tools

- [Surprise Library](https://surpriselib.com/) - Python recommendation library
- [Collaborative Filtering Research](https://grouplens.org/) - GroupLens Research
- [User-Based CF Tutorial](https://en.wikipedia.org/wiki/Collaborative_filtering)

### Algoritma Alternatif

- **Item-Based Collaborative Filtering**: Rekomendasi berdasarkan kesamaan produk
- **Matrix Factorization (SVD)**: Teknik lanjutan untuk CF
- **Deep Learning**: Neural networks untuk recommendation
- **Hybrid Systems**: Kombinasi multiple algorithms

### Bacaan Lebih Lanjut

1. "Recommender Systems Handbook" - Ricci, Rokach, Shapira
2. "Collective Intelligence in Action" - Satnam Alag
3. [Netflix Prize Competition](https://www.netflixprize.com/) - Real-world example

---

## 📞 Pertanyaan Umum

### Q: Bagaimana jika tidak ada user serupa?

**A:** Fallback ke rekomendasi random atau berdasarkan rating tertinggi:

```php
if (empty($recommendations)) {
    return Product::inRandomOrder()->take($limit)->get();
}
```

### Q: Apakah similarity score bisa negatif?

**A:** Tidak dengan cosine similarity (range 0-1). Nilai negatif hanya muncul di Pearson correlation.

### Q: Berapa banyak user yg perlu untuk rekomendasi bagus?

**A:** Minimal 100-200 user dengan rating minimal 10-20 per user untuk hasil yang reasonable.

### Q: Apakah real-time calculation diperlukan?

**A:** Tidak perlu. Boleh di-batch/pre-compute setiap jam/hari.

---

Semoga dokumentasi ini membantu memahami Collaborative Filtering di project Marketplace Botol! 🎉
