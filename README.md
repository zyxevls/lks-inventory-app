# 📦 Inventory & Transaction Management System

Sistem manajemen inventaris dan transaksi berbasis web yang dirancang untuk menangani operasional gudang, manajemen produk, hingga pelaporan transaksi yang kompleks.

## 🚀 Fitur Utama

* **Authentication & Role Management**: Pengamanan akses menggunakan Middleware untuk membedakan hak akses user.
* **Full CRUD Modules**: Manajemen data master untuk Produk, Kategori, dan Supplier.
* **Complex Transaction System**: Sistem transaksi yang mencakup detail item (Many-to-Many logic).
* **Insightful Dashboard**: Ringkasan data statistik dalam satu tampilan.
* **Reporting**: Generasi laporan untuk evaluasi bisnis.

---

## 📂 Struktur Proyek

Berikut adalah pemetaan file utama berdasarkan arsitektur aplikasi:

### 🎮 Controllers (`app/Http/Controllers`)
* `AuthController.php` - **[Auth]** Menangani login, register, dan logout.
* `ProductController.php` - **[CRUD]** Operasi data Produk.
* `CategoryController.php` - **[CRUD]** Operasi Kategori Produk.
* `SupplierController.php` - **[CRUD]** Operasi data Supplier.
* `TransactionController.php` - **[Complex]** Logika transaksi, pengurangan stok, dan detail transaksi.
* `DashboardController.php` - **[Complex]** Pengolahan data statistik untuk ringkasan visual.
* `ReportController.php` - Manajemen dan ekspor laporan transaksi.

### 🛡️ Middleware (`app/Http/Middleware`)
* `RoleMiddleware.php` - **[Auth]** Filter akses berdasarkan peran/level user.

### 🗄️ Database & Models
* **Models (`app/Models`)**: 
    * `User.php`, `Product.php`, `Category.php`, `Supplier.php`, `Transaction.php`, `TransactionDetail.php`
* **Migrations (`database/migrations`)**:
    * `create_users_table.php`
    * `create_categories_table.php`
    * `create_suppliers_table.php`
    * `create_products_table.php`
    * `create_transactions_table.php`
    * `create_transaction_details_table.php`
* **Seeder**: `DatabaseSeeder.php` - Pengisian data awal (dummy) untuk sistem.

### 🎨 Views (`resources/views`)
* `layouts/app.blade.php` - **Template Utama** (Master Layout).
* `auth/login.blade.php` - Halaman masuk aplikasi.
* `products/index.blade.php`, `create.blade.php`, `edit.blade.php` - UI Manajemen Produk.
* `transactions/index.blade.php`, `create.blade.php` - **[Complex]** UI input transaksi dengan banyak item.

### 🌐 Konfigurasi & Routing
* `routes/web.php` - Definisi seluruh endpoint/route aplikasi.
* `.env` - Konfigurasi environment (Database, App Key, dll).

---

## 🛠️ Cara Instalasi

1.  **Clone Repository**
    ```bash
    git clone https://github.com/username/repo-name.git
    cd repo-name
    ```

2.  **Instalasi Dependensi**
    ```bash
    composer install
    npm install && npm run dev
    ```

3.  **Konfigurasi Environment**
    Salin file `.env.example` menjadi `.env` dan sesuaikan pengaturan database Anda.
    ```env
    DB_DATABASE=nama_db_anda
    DB_USERNAME=root
    DB_PASSWORD=
    ```

4.  **Migrasi & Seed**
    ```bash
    php artisan migrate --seed
    ```

5.  **Jalankan Server**
    ```bash
    php artisan serve
    ```

---

## 📊 Relasi Database

* **Category** 1:N **Product** (Satu kategori memiliki banyak produk).
* **Supplier** 1:N **Product** (Satu supplier memasok banyak produk).
* **Transaction** 1:N **TransactionDetail** (Satu transaksi memiliki banyak detail produk).
* **Product** 1:N **TransactionDetail** (Satu produk bisa muncul di banyak detail transaksi).