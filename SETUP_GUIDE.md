# Panduan Setup & Running - Marketplace Botol

Ini adalah project Laravel 12 dengan Inertia.js + Vue 3 dan Tailwind CSS. Ikuti panduan ini untuk setup dan menjalankan aplikasi.

## 📋 Requirement

- PHP 8.2 atau versi lebih tinggi
- Node.js LTS (v18 atau lebih tinggi)
- npm atau yarn
- Composer

## ⚡ Quick Setup (Rekomendasi)

Untuk setup cepat dan otomatis, jalankan satu command:

```bash
composer run setup
```

Command ini akan melakukan:
1. Install PHP dependencies (composer install)
2. Copy `.env.example` ke `.env`
3. Generate APP_KEY
4. Run database migrations
5. Install Node.js dependencies (npm install)
6. Build frontend assets

> **Setelah command selesai, lanjut ke bagian [Menjalankan Aplikasi](#menjalankan-aplikasi)**

---

## 🔧 Manual Setup (Jika diperlukan)

Jika Anda ingin setup secara manual, ikuti langkah-langkah berikut:

### 1. Clone/Extract Project
```bash
cd marketplace_botol
```

### 2. Install PHP Dependencies
```bash
composer install
```

### 3. Setup Environment File
```bash
cp .env.example .env
```

### 4. Generate Application Key
```bash
php artisan key:generate
```

### 5. Setup Database

**MySQL Configuration:**

Edit file `.env` dan sesuaikan konfigurasi database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=marketplace_botol
DB_USERNAME=root
DB_PASSWORD=
```

Buat database MySQL:
```bash
mysql -u root -e "CREATE DATABASE marketplace_botol;"
```

Jalankan migrations:
```bash
php artisan migrate
```

Jika ingin seeding data dummy:
```bash
php artisan db:seed
```

### 6. Install Frontend Dependencies
```bash
npm install
```

### 7. Build Frontend Assets
**Development (dengan reload otomatis):**
```bash
npm run dev
```

**Production:**
```bash
npm run build
```

---

## 🚀 Menjalankan Aplikasi

Ada dua cara untuk menjalankan aplikasi:

### Cara 1: Semua dalam Satu Terminal (Rekomendasi)
```bash
composer run dev
```

Command ini akan menjalankan:
- Laravel development server (http://localhost:8000)
- Queue listener
- Log viewer (Pail)
- Vite dev server

**Output:** Akan menampilkan 4 proses dengan warna berbeda:
- 🔵 **server** - Laravel dev server
- 🟣 **queue** - Job queue processor
- 🔴 **logs** - Real-time logs
- 🟠 **vite** - Frontend build server

### Cara 2: Multiple Terminal (Alternatif)

Jika Anda lebih suka menjalankan di terminal terpisah:

**Terminal 1 - Laravel Server:**
```bash
php artisan serve
```
→ Aplikasi akan berjalan di http://localhost:8000

**Terminal 2 - Frontend Dev Server:**
```bash
npm run dev
```
→ Vite akan menjalankan dev server (auto hot reload)

**Terminal 3 (Opsional) - Queue Listener:**
```bash
php artisan queue:listen
```

**Terminal 4 (Opsional) - View Logs:**
```bash
php artisan pail
```

---

## 📝 Perintah Penting Lainnya

### Database
```bash
# Jalankan migration
php artisan migrate

# Rollback migration terbaru
php artisan migrate:rollback

# Rollback semua migration
php artisan migrate:reset

# Fresh migration (reset + migrate)
php artisan migrate:fresh

# Seed data dummy
php artisan db:seed

# Fresh + Seed
php artisan migrate:fresh --seed
```

### Testing
```bash
# Jalankan semua test
composer run test

# Atau menggunakan artisan
php artisan test

# Test file spesifik
php artisan test tests/Feature/ExampleTest.php
```

### Development
```bash
# Tinker - PHP REPL interaktif
php artisan tinker

# Clear cache
php artisan cache:clear

# Clear config cache
php artisan config:clear
```

### Frontend Build
```bash
# Development (hot reload)
npm run dev

# Production build
npm run build
```

---

## 🗂️ Project Structure

```
marketplace_botol/
├── app/                    # Aplikasi code
│   ├── Http/              # Controllers, Middleware, Requests
│   ├── Models/            # Eloquent models
│   ├── Policies/          # Authorization policies
│   ├── Services/          # Business logic
│   └── Providers/         # Service providers
├── routes/                # Route definitions
├── database/
│   ├── migrations/        # Database migrations
│   ├── factories/         # Model factories
│   └── seeders/          # Database seeders
├── resources/
│   ├── js/               # Vue components & JavaScript
│   ├── css/              # Tailwind CSS
│   └── views/            # Blade templates
├── tests/                # Test files
├── config/               # Configuration files
└── storage/              # Generated files, logs, cache
```

---

## 🔌 Database Configuration

### MySQL (Default)
Aplikasi dikonfigurasi menggunakan **MySQL** secara default.

**Konfigurasi di `.env`:**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=marketplace_botol
DB_USERNAME=root
DB_PASSWORD=
```

**Setup:**
1. Pastikan MySQL server sudah running
2. Buat database (jika belum ada):
```bash
mysql -u root -e "CREATE DATABASE marketplace_botol;"
```

3. Update kredensial di `.env` sesuai konfigurasi MySQL Anda
4. Jalankan migrations:
```bash
php artisan migrate
```

### SQLite (Alternatif)
Jika ingin menggunakan SQLite, edit `.env`:
```env
DB_CONNECTION=sqlite
```

File database akan dibuat otomatis di `database/database.sqlite`.

---

## 🌐 Environment Variables

File `.env` berisi konfigurasi penting. Berikut key yang perlu diketahui:

```env
APP_NAME=Marketplace Botol    # Nama aplikasi
APP_ENV=local                 # local, production, testing
APP_DEBUG=true                # Debug mode (false di production)
APP_URL=http://localhost      # URL aplikasi

# Database MySQL
DB_CONNECTION=mysql           # Database driver (mysql, sqlite, pgsql)
DB_HOST=127.0.0.1            # Host database
DB_PORT=3306                 # Port MySQL
DB_DATABASE=marketplace_botol # Nama database
DB_USERNAME=root             # Username MySQL
DB_PASSWORD=                 # Password MySQL (kosong jika tidak ada)

SESSION_DRIVER=database       # Session storage
CACHE_STORE=database          # Cache storage
QUEUE_CONNECTION=database     # Queue driver
MAIL_MAILER=log              # Mailer (log di local)
```

---

## ✅ Checklist Setup

- [ ] PHP 8.2+ terinstall
- [ ] Node.js LTS terinstall
- [ ] Composer terinstall
- [ ] .env file sudah dibuat
- [ ] APP_KEY sudah generate
- [ ] Database migrations sudah berjalan
- [ ] npm dependencies sudah install
- [ ] Frontend assets sudah build
- [ ] Aplikasi bisa diakses di http://localhost:8000

---

## 🐛 Troubleshooting

### Error: "No application encryption key has been specified"
```bash
php artisan key:generate
```

### Error: "The storage path is not writable"
```bash
chmod -R 755 storage bootstrap/cache
```

### Vite assets tidak loading di browser
- Pastikan `npm run dev` sudah berjalan
- Clear browser cache (Ctrl+Shift+Delete)
- Check console browser untuk errors

### Database error saat migrate
```bash
# Fresh database
php artisan migrate:fresh

# Atau jika menggunakan MySQL, pastikan database sudah ada
mysql -u root -e "CREATE DATABASE marketplace_botol;"
```

### Node modules error
```bash
# Hapus lock files dan reinstall
rm -rf node_modules package-lock.json
npm install
```

---

## 📚 Dokumentasi Referensi

- [Laravel Documentation](https://laravel.com/docs/12.x)
- [Inertia.js](https://inertiajs.com/)
- [Vue 3](https://vuejs.org/)
- [Tailwind CSS](https://tailwindcss.com/)
- [Vite](https://vitejs.dev/)

---

## 🚀 Siap untuk Production?

Sebelum deploy ke production:

1. Ubah `.env`:
```env
APP_ENV=production
APP_DEBUG=false
```

2. Build assets untuk production:
```bash
npm run build
```

3. Generate production optimizations:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

4. Setup proper database (MySQL/PostgreSQL)
5. Configure email/mail settings
6. Setup payment gateway (jika diperlukan)
7. Setup storage untuk file uploads
8. Configure queue worker untuk background jobs

---

Semoga panduan ini membantu! Happy coding! 🎉
