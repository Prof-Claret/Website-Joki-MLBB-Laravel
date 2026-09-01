# 📱 Escam - Joki Game Platform

Aplikasi multi-role game joki services berbasis Laravel 13 dengan sistem pembayaran terintegrasi Midtrans.

## 🚀 Quick Start

### Prerequisites
- PHP 8.5+
- Composer
- Node.js LTS
- SQLite atau MySQL

### Installation

```bash
# 1. Instal dependencies PHP
composer install

# 2. Konfigurasi environment
cp .env.example .env
php artisan key:generate

# 3. Instal dependencies frontend
npm install

# 4. Build assets
npm run build

# 5. Migrasi database
php artisan migrate

# 6. Seed database (roles & settings)
php artisan db:seed

# 7. Jalankan server
php artisan serve
```

Aplikasi akan accessible di `http://localhost:8000`

## 🎯 Role-Based Features

### 👤 Customer (User)
**Akses**: Dashboard → Lihat daftar order & statistik

**Fitur Utama**:
- ✅ Membuat order joki game baru
- ✅ Input kredensial akun (terenkripsi)
- ✅ Tracking order realtime dengan progress bar
- ✅ Lihat bukti kerja (screenshots)
- ✅ Rating & review worker setelah selesai
- ✅ Lihat riwayat pembayaran
- ✅ Profil & pengaturan akun

**Routes**:
```
GET /orders                    # Daftar order saya
GET /orders/create             # Form buat order baru
POST /orders                   # Submit order
GET /orders/{id}               # Detail & tracking order
POST /orders/{id}/review       # Submit review
```

---

### 🔧 Worker (Pekerja Joki)
**Akses**: `/worker/orders` (Dashboard Worker)

**Fitur Utama**:
- ✅ Lihat order yang di-assign
- ✅ Update progress pekerjaan (0-100%)
- ✅ Upload bukti kerja (screenshot)
- ✅ Lihat rating & review dari customer
- ✅ Tracking earnings
- ✅ Request withdrawal dana

**Statistik**:
- Active orders (dalam progress)
- Completed orders
- Total earnings
- Pending withdrawal

**Routes**:
```
GET /worker/orders                    # Daftar order saya
POST /orders/{id}/progress            # Update progress
POST /orders/{id}/proof               # Upload bukti
```

---

### 👨‍💼 Admin (Administrator)
**Akses**: `/admin/orders` (Dashboard Admin)

**Fitur Utama**:
- ✅ Kelola semua order sistem
- ✅ Assign order ke worker
- ✅ Update status order (pending → in_progress → completed)
- ✅ Verifikasi bukti kerja
- ✅ Monitor revenue & transactions
- ✅ Manage user & worker accounts
- ✅ Lihat laporan sistem

**Dashboard Stats**:
- Pending orders
- Active orders
- Completed orders
- Total revenue
- Recent orders table

**Routes**:
```
GET /admin/orders                     # Daftar semua order
PATCH /admin/orders/{id}              # Update order status
```

---

### 🛠️ Developer (Super Admin)
**Akses**: `/developer/ranks` & `/developer/settings`

**Fitur Utama**:
- ✅ Manage game catalog
- ✅ CRUD rank/tier setiap game
- ✅ CRUD service pricing packages
- ✅ Upload icon rank/game
- ✅ Konfigurasi site settings dinamis
- ✅ Manage sistem pricing

**Developer Routes**:
```
GET /developer/ranks                  # Daftar rank
GET /developer/ranks/create           # Form buat rank
POST /developer/ranks                 # Store rank
GET /developer/ranks/{id}/edit        # Edit form
PUT /developer/ranks/{id}             # Update rank
DELETE /developer/ranks/{id}          # Delete rank

GET /developer/settings               # Daftar settings
POST /developer/settings              # Simpan setting
PUT /developer/settings/{id}          # Update setting
```

---

## 🔐 Authentication & Authorization

### Login Flow
```
1. User ke /login
2. Input email & password
3. Laravel Breeze validate credentials
4. Redirect ke /dashboard
5. DashboardController cek role
6. Render dashboard sesuai role
```

### Role-Based Middleware
Middleware `role` di `app/Http/Middleware/RoleMiddleware.php`:

```php
Route::middleware(['auth', 'role:developer'])->group(...)  // Hanya developer
Route::middleware(['auth', 'role:admin'])->group(...)      // Hanya admin
Route::middleware(['auth', 'role:worker'])->group(...)     // Hanya worker
Route::middleware(['auth'])->group(...)                     // Semua user login
```

### Default Credentials (untuk testing)
Jalankan `php artisan tinker`:
```php
// Create developer
$dev = User::create([
    'name' => 'Developer',
    'email' => 'dev@escam.local',
    'password' => bcrypt('password'),
    'role_id' => Role::where('slug', 'developer')->first()->id,
]);

// Create customer
$user = User::create([
    'name' => 'Customer',
    'email' => 'customer@escam.local',
    'password' => bcrypt('password'),
    'role_id' => Role::where('slug', 'user')->first()->id,
]);

// Create worker
$worker = User::create([
    'name' => 'Worker',
    'email' => 'worker@escam.local',
    'password' => bcrypt('password'),
    'role_id' => Role::where('slug', 'worker')->first()->id,
]);
```

---

## 💳 Order Workflow

### Customer Perspective
```
1. Create Order
   └─ Select game, service, rank range
   └─ Input WA number, account credentials
   └─ Sistem hitung harga otomatis
   └─ Buat order dengan status "pending"

2. Payment
   └─ Payment method: Midtrans atau Manual
   └─ Integration Midtrans untuk payment gateway
   └─ Callback update payment_status

3. Tracking
   └─ Lihat progress worker (0-100%)
   └─ Lihat proof/bukti kerja
   └─ Real-time update status

4. Complete & Review
   └─ Order selesai (status = "completed")
   └─ Rate worker 1-5 stars
   └─ Submit review & comment
```

### Pricing Calculation
```php
Base Price + (Star Difference × Price per Star)

Contoh:
- Service base price: Rp 50,000
- Price per star: Rp 5,000
- Rank from: 2 stars
- Rank to: 5 stars
- Star difference: 5 - 2 = 3 stars
- Total: Rp 50,000 + (3 × Rp 5,000) = Rp 65,000
```

---

## 📊 Database Schema

### Core Tables

**roles**
```sql
id, name, slug, description, is_active
```

**users**
```sql
id, name, username, email, phone, password, 
role_id, avatar_path, wa_number, bio, 
balance, is_active, last_login_at
```

**orders**
```sql
id, user_id, worker_id, game_id, service_id,
rank_from_id, rank_to_id, order_number,
status, priority, price, payment_method, payment_status,
wa_number, account_credentials (encrypted), 
request_hero, notes, tracking_code, worker_progress,
customer_rating, customer_review, created_at, updated_at
```

**transactions**
```sql
id, order_id, user_id, worker_id,
transaction_id, gateway, amount, fee,
status, payment_type, created_at, updated_at
```

**withdrawals**
```sql
id, user_id, amount, status,
bank_account, proof_path, created_at, updated_at
```

**reviews**
```sql
id, order_id, user_id, worker_id,
rating (1-5), comment, is_visible, created_at, updated_at
```

**games, ranks, services**
```sql
Product catalogs dengan relasi ke order
```

---

## 🔧 API Endpoints

Semua endpoint require authentication (Breeze session):

### Public Routes
```
GET  /                   # Home page
GET  /login              # Login form
POST /login              # Process login
GET  /register           # Register form
POST /register           # Process register
```

### Authenticated Routes (All Roles)
```
GET  /dashboard          # Role-based dashboard
GET  /profile            # Edit profil user
PATCH /profile           # Update profil
DELETE /profile          # Delete akun
```

### Order Management (Customer)
```
GET   /orders            # List order saya
GET   /orders/create     # Form buat order
POST  /orders            # Simpan order baru
GET   /orders/{id}       # Detail order
POST  /orders/{id}/review   # Kirim review
```

### Worker Operations
```
GET  /worker/orders               # Order yang di-assign
POST /orders/{id}/progress        # Update progress
POST /orders/{id}/proof           # Upload bukti
```

### Admin Functions
```
GET  /admin/orders               # Lihat semua order
PATCH /admin/orders/{id}         # Update order status
```

### Developer Controls
```
GET    /developer/ranks          # List ranks
POST   /developer/ranks          # Create rank
PUT    /developer/ranks/{id}     # Update rank
DELETE /developer/ranks/{id}     # Delete rank

GET    /developer/settings       # List settings
POST   /developer/settings       # Create setting
PUT    /developer/settings/{id}  # Update setting
```

---

## 🎨 Frontend Structure

```
resources/views/
├── layouts/
│   ├── app.blade.php           # Main layout dengan Breeze
│   └── navigation.blade.php    # Top navbar
├── dashboards/
│   ├── developer.blade.php     # Dev dashboard
│   ├── admin.blade.php         # Admin dashboard
│   ├── worker.blade.php        # Worker dashboard
│   └── user.blade.php          # Customer dashboard
├── orders/
│   ├── create.blade.php        # Buat order form
│   └── show.blade.php          # Order detail + tracking
├── auth/                        # Breeze auth views
└── dashboard.blade.php         # Default dashboard
```

---

## 🔐 Security Features

✅ **Password Encryption**: Laravel bcrypt  
✅ **Account Credentials Encryption**: Encrypted at rest  
✅ **CSRF Protection**: Laravel tokens  
✅ **SQL Injection Prevention**: Eloquent ORM  
✅ **XSS Protection**: Blade escaping  
✅ **Email Verification**: Breeze mailable  
✅ **Role-Based Access Control**: Middleware enforcement  

---

## 📱 Responsive Design

Semua view menggunakan Tailwind CSS v3 + Breeze defaults:
- ✅ Mobile-first responsive
- ✅ Dark mode support ready
- ✅ Accessible form controls
- ✅ Loading states & feedback

---

## 🚀 Deployment

### Production Checklist

```bash
# 1. Environment
echo "APP_ENV=production" >> .env
echo "APP_DEBUG=false" >> .env
echo "APP_URL=https://yourdomain.com" >> .env

# 2. Dependencies
composer install --optimize-autoloader --no-dev
npm run build

# 3. Database
php artisan migrate --force
php artisan db:seed

# 4. Cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. Storage permissions
chmod -R 775 storage bootstrap/cache

# 6. Webserver
# Configure web server to point to /public directory
```

---

## 📞 Testing

Jalankan test suite:
```bash
php artisan test                    # Run all tests
php artisan test --filter=Order     # Run order tests
php artisan test --filter=Auth      # Run auth tests
```

---

## 📝 Notes

- **Database**: Default SQLite untuk development
- **Mail**: Gunakan Mailtrap atau SMTP untuk production
- **Payment**: Midtrans sandbox credentials setup di `.env`
- **Storage**: Pilih 'local' atau 's3' di `config/filesystems.php`
- **Cache**: Redis atau file cache default

---

**Version**: 1.0.0  
**Last Updated**: 31 Aug 2026  
**Framework**: Laravel 13.29.0 + Laravel Breeze
