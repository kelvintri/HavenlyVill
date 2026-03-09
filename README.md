# 🌴 Sistem Pengelolaan & Pemesanan Villa (Kawi Resort)

Selamat datang di repositori kode sumber **Sistem Booking Villa**.
Aplikasi web ini dibangun dengan arsitektur terkini untuk mendigitalisasi proses reservasi villa. Sistem menyediakan antarmuka terpisah antara Tamu (Pencari Villa) dan Admin (Pemilik/Pengelola Properti) dengan perhitungan harga otomatis dan pencegahan _double-booking_.

---

## 📖 Dokumentasi Lengkap Sistem

Untuk memenuhi persyaratan teknis tingkat lanjut, kami telah menjabarkan **pemenuhan seluruh syarat A sampai L**, membedah _tech-stack_, hingga penjelasan _Object-Oriented Programming_ dan _Database Migration_ secara mendalam.

👉 **[BACA DOKUMENTASI TEKNIS LENGKAP DI SINI (DOCUMENTATION.md)](DOCUMENTATION.md)** 👈

---

## 🏛️ Arsitektur dan Penjelasan Program Secara Detail

Aplikasi ini mengusung pola **MVC (Model-View-Controller)** yang diperkuat dengan reaktivitas **Livewire**.

### 1. Model (Lapisan Basis Data)

Merepresentasikan tabel fisik di dalam database.

- **`Villa`**: Entitas unit properti/kamar. Memuat nama, deskripsi, matriks fasilitas (Array JSON), dan matriks galeri gambar.
- **`Booking`**: Entitas jejak reservasi. Mengikat _Tamu_ dan _Villa_ pada rentang tanggal spesifik. Menangani komputasi harga (Hari x Harga per Malam).
- **`BlockedDate`**: Entitas kalender penahan (Lock), di mana admin bisa menutup villa pada tanggal perbaikan tanpa harus membuat order reservasi palsu.

### 2. View (Lapisan Visual Pengguna)

Semua tampilan ditulis menggunakan **Blade File** dan dipercantik dengan spesifikasi **Tailwind CSS**.

- **Guest UI**: Bersih, terfokus pada konversi penjualan, tanpa proses login (Akses Bebas). Tamu memasukkan tanggal dan sistem merender visual "Bisa Dipesan" atau "Penuh".
- **Admin UI**: Terlindungi _Middleware Authentication_. Admin dihadapkan pada tabel manajemen dan Kalender Visual raksasa.

### 3. Controller & Component (Lapisan Bisnis Reaktif)

Berbeda dengan Laravel klasik, aplikasi ini memakai **Livewire Components** (`app/Livewire/`) untuk menangani XHR Ajax tanpa tunda.

- **`BookingManager`**: Mesin di balik layar Admin untuk me-Render, me-filter, Cek Detail, dan Menerima/Menolak pemesanan dalam satu halaman.
- **`CalendarView`**: Merender konstruksi visual bulan-ke-bulan dari data transaksi.

---

## 📁 Tata Letak Folder & File Penting

Bagi para _developer_ yang ingin berkontribusi, berikut panduan navigasi _repository_ ini:

```text
📦 VILLAS/
├── 📂 app/                      # Inti Kode Program PHP Backend
│   ├── 📂 Models/               # File Cetak biru Database (Booking.php, Villa.php)
│   ├── 📂 Livewire/             # Komponen Controller UI Reaktif
│   │   ├── 📂 Admin/            # Komponen Khusus Penjaga Dashboard Admin
│   │   └── 📂 Guest/            # Komponen Navigasi Halaman Tamu Bebas
│   └── 📂 Services/             # Kelas Pihak ke-3 Pemisah Logika Kompleks
│
├── 📂 database/                 # Infrastruktur Otomatisasi Tabel SQL
│   ├── 📂 migrations/           # Skrip pencipta tabel (Tabel Users, Villas, Bookings)
│   └── 📂 seeders/              # Simulator Pembuatan 100+ Data Palsu untuk Uji Coba
│
├── 📂 public/                   # Gerbang Akses Web Root Document
│   └── 📂 assets/               # Lokasi Gambar DFD, Bagan, Logo & Galeri Upload CSS/JS Statis
│
├── 📂 resources/                # Kompilasi Front-End Murni
│   └── 📂 views/                # Lembar HTML Blade (guest/, admin/, components/)
│
├── 📂 routes/                   # File Penunjuk Arah URL
│   └── 📄 web.php               # Peta Jalan Akses Website
│
├── 📄 DOCUMENTATION.md          # 📜 Spesifikasi Dokumentasi Penuh (Baca File Ini!)
└── 📄 composer.json             # Daftar Eksternal Library PHP Pihak Ketiga (Vendor)
```

---

## 📊 Rancangan Interaksi Aktor (DFD & Use Case)

Sistem ini mentaati aturan Use Case dan DFD yang telah dikembangkan secara teoretis. Interaksi dibatasi ketat menggunakan tembok Autentikasi.

### 1. Data Flow Diagram (DFD Level 1)

Mengatur aliran penyerahan KODE BOOKING ke tamu, pergerakan Uang ke Kalkulator Mesin, lalu dikembalikan ke entitas Admin berupa Laporan.

![Data Flow Diagram Level 1](public/assets/DFD%20lv%201.png)

### 2. Use Case Diagram Aktor (Kawi Resort)

Memecah hak akses secara radikal; Tamu hanya menyentuh cangkang pemesanan, sedangkan Pemilik / Admin bebas merusak dan mengelola isi operasional villa.

![Use Case Diagram](<public/assets/use-case-kawi-resort.drawio%20(1).png>)

---

## 🛠️ Panduan Instalasi Singkat Pengembangan

1. `composer install` (Unduh seluruh perpustakaan pihak ketiga)
2. `npm install && npm run build` (Sintesiskan CSS Tailwind Modern)
3. `cp .env.example .env` (Konfigurasi Pangkalan Data SQL Anda)
4. `php artisan key:generate`
5. `php artisan migrate --seed` (Bangun Tabel dan Isi Data Simulasi)
6. `php artisan serve` (Jalankan Peladen Lokal)
