# ⚡ Quick Testing Guide

## Menjalankan Aplikasi

```bash
# Terminal 1: Start Laravel server
php artisan serve

# Terminal 2 (optional): Start frontend dev build
npm run dev
```

Akses: **http://localhost:8000**

---

## Test Credentials

Gunakan `php artisan tinker` untuk membuat test users:

```php
php artisan tinker

# Create Developer account
User::create([
    'name' => 'Developer Admin',
    'email' => 'developer@test.local',
    'password' => bcrypt('password'),
    'phone' => '081234567890',
    'wa_number' => '081234567890',
    'role_id' => 1, // Developer role
]);

# Create Customer account  
User::create([
    'name' => 'Test Customer',
    'email' => 'customer@test.local',
    'password' => bcrypt('password'),
    'phone' => '082345678901',
    'wa_number' => '082345678901',
    'role_id' => 4, // User role
]);

# Create Worker account
User::create([
    'name' => 'Professional Worker',
    'email' => 'worker@test.local',
    'password' => bcrypt('password'),
    'phone' => '083456789012',
    'wa_number' => '083456789012',
    'role_id' => 3, // Worker role
]);

# Create Admin account
User::create([
    'name' => 'Admin User',
    'email' => 'admin@test.local',
    'password' => bcrypt('password'),
    'phone' => '084567890123',
    'wa_number' => '084567890123',
    'role_id' => 2, // Admin role
]);
```

---

## Test User Accounts

| Role | Email | Password | Access |
|------|-------|----------|--------|
| 🛠️ Developer | `developer@test.local` | `password` | `/developer/ranks`, `/developer/settings` |
| 👨‍💼 Admin | `admin@test.local` | `password` | `/admin/orders` |
| 🔧 Worker | `worker@test.local` | `password` | `/worker/orders` |
| 👤 Customer | `customer@test.local` | `password` | `/orders`, `/dashboard` |

---

## Testing Workflow

### 1️⃣ Developer Setup (Developer Role)

Login sebagai Developer → `/developer/ranks`

Buat sample game & rank:
```php
# Terminal tinker
$game = Game::create([
    'name' => 'Mobile Legends',
    'slug' => 'mobile-legends',
    'description' => 'Popular MOBA game',
    'is_active' => true,
]);

$rank = Rank::create([
    'game_id' => $game->id,
    'name' => 'Gold',
    'slug' => 'gold',
    'star_system' => 'stars',
    'min_star' => 10,
    'max_star' => 19,
    'sort_order' => 3,
    'is_active' => true,
]);

$service = Service::create([
    'game_id' => $game->id,
    'name' => 'Rank Up Service',
    'description' => 'Naik rank di game anda',
    'base_price' => 50000,
    'price_per_star' => 5000,
    'estimated_duration_hours' => 24,
    'is_active' => true,
]);
```

---

### 2️⃣ Customer Create Order (User Role)

Login sebagai Customer → Click "New Order" atau `/orders/create`

Form akan:
1. Select Game: "Mobile Legends"
2. Select Service: "Rank Up Service"
3. Select Rank From: "Gold"
4. Select Rank To: "Gold+" (star difference = pricing)
5. Input WA Number
6. Input Account Credentials (will be encrypted)
7. Optional: Request Hero, Additional Notes
8. Price calculated automatically
9. Click "Create Order"

Status: **Pending** (waiting for payment/admin assignment)

---

### 3️⃣ Admin Assign & Process (Admin Role)

Login sebagai Admin → `/admin/orders`

Lihat order yang baru dibuat:
- View order details
- Assign to worker: PATCH `/admin/orders/{id}` with `worker_id`
- Update status dari "pending" → "in_progress"

---

### 4️⃣ Worker Execute Order (Worker Role)

Login sebagai Worker → `/worker/orders`

Update progress:
- POST `/orders/{id}/progress` dengan `worker_progress: 0-100`
- POST `/orders/{id}/proof` untuk upload screenshot bukti

---

### 5️⃣ Customer Track & Review (User Role)

Login sebagai Customer → `/orders/{id}`

Lihat:
- ✅ Realtime progress (0-100%)
- ✅ Work proofs (screenshots)
- ✅ Payment status
- ✅ Worker info

Setelah order completed (status = "completed"):
- Rating: 1-5 stars
- Comment: Text feedback
- Click "Submit Rating"

---

## Database Inspection

```bash
# Access SQLite database
sqlite3 database/database.sqlite

# List all tables
.tables

# Query users
SELECT id, name, email, role_id FROM users;

# Query orders
SELECT id, order_number, status, price FROM orders;

# Exit
.quit
```

---

## Common Commands

```bash
# Clear cache
php artisan cache:clear
php artisan route:cache

# Run migrations fresh (⚠️ clears data!)
php artisan migrate:fresh --seed

# Reset password (tinker)
$user = User::find(1);
$user->password = bcrypt('newpassword');
$user->save();

# Check routes
php artisan route:list

# Run tests
php artisan test

# Generate API token (if using Sanctum)
$user->createToken('app-token');
```

---

## File Locations

```
app/
├── Http/Controllers/
│   ├── DashboardController.php      ← Role routing logic
│   ├── OrderController.php          ← Order CRUD
│   ├── RankController.php           ← Rank management
│   └── SiteSettingController.php    ← Settings
│
├── Models/
│   ├── User.php                     ← User model
│   ├── Order.php                    ← Order model
│   ├── Role.php                     ← Roles
│   └── [7 more models...]
│
└── Http/Middleware/
    └── RoleMiddleware.php           ← Role checker

routes/
├── web.php                          ← Main routes (role groups)
├── auth.php                         ← Breeze auth routes
└── console.php

resources/views/
├── dashboards/                      ← Role dashboards
├── orders/                          ← Order forms
└── auth/                            ← Login/Register
```

---

## Troubleshooting

### "Route not found"
```bash
php artisan route:cache --clear
php artisan route:list
```

### "Column not found"
```bash
php artisan migrate --force
php artisan db:seed
```

### "View not found"
```bash
php artisan view:cache --clear
```

### "npm: command not found"
```bash
# Add Node to PATH
$env:PATH += ";C:\Program Files\nodejs"
npm install && npm run build
```

### "php: command not found"
```bash
# Add PHP to PATH  
$env:PATH += ";$env:USERPROFILE\.config\herd-lite\bin"
php -v
```

---

## Next Steps

1. ✅ Test login/logout with each role
2. ✅ Create order as customer
3. ✅ Assign order as admin
4. ✅ Update progress as worker
5. ✅ Submit review as customer
6. ✅ Manage ranks as developer
7. ✅ Run test suite: `php artisan test`

Happy testing! 🎉
