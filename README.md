# Penjelasan Requirement Program

Dokumen ini berisi jawaban beserta potongan kode (snippets) dari aplikasi **Villa Booking** ini untuk memenuhi spesifikasi tugas pemrograman.

---

### a. Program yang dibuat harus sesuai dengan rancangan (Data Flow Diagram/Use Case) yang dibuat

**Jawaban:** Ya. Program ini dirancang sesuai Use Case di mana:

- **Guest (Tamu):** Dapat melihat daftar villa, mengecek ketersediaan tanggal, dan membuat _booking_.
- **Admin:** Memiliki fitur _dashboard_ lengkap untuk mengelola (_CRUD_) Villa, _Booking_, _Blocked Date_ kalender (seperti terlihat pada `/admin/calendar`), dan menyetujui/menolak _booking_.

---

### b. Menerapkan coding guidelines sesuai dengan bahasa pemrograman yang digunakan

**Jawaban:** Ya. Aplikasi ini dikembangkan menggunakan _framework_ Laravel (PHP) dengan standar _coding guidelines_ **PSR-12**. Di antaranya menggunakan _CamelCase_ untuk nama _method_, _StudlyCaps_ untuk nama _class_, _type-hinting_ yang jelas (seperti `: array` dan `: string`), dan struktur _namespace_ bawaan Laravel.

**Contoh Kode (app/Models/Booking.php):**

```php
/**
 * Mendapatkan total harga yang diformat ke Rupiah.
 *
 * @return string
 */
public function getFormattedTotalPriceAttribute(): string
{
    // Menggunakan strict return type `: string`
    return 'Rp ' . number_format($this->total_price, 0, ',', '.');
}
```

---

### c. Program yang dibuat mempunyai interface input dan output(tampilan) ke pengguna

**Jawaban:** Ya. Aplikasi memiliki antar muka pengguna (_User Interface_) baik untuk sisi _Guest_ maupun _Admin_. Data diambil dari _backend_ dan ditampilkan dalam format HTML yang di-_styling_ dengan Tailwind CSS via Blade/Livewire.

**Contoh Kode (resources/views/guest/booking-status.blade.php):**

```html
<!-- Input form pencarian booking -->
<form wire:submit="checkStatus" class="space-y-4">
    <div>
        <label
            for="booking_code"
            class="block text-sm font-medium text-gray-700"
            >Kode Booking</label
        >
        <input
            type="text"
            id="booking_code"
            wire:model="booking_code"
            required
            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
        />
    </div>
    <button
        type="submit"
        class="w-full bg-[#1e3a8a] text-white py-2 rounded-md hover:bg-blue-700"
    >
        Cek Status
    </button>
</form>

<!-- Output Hasil -->
@if ($booking)
<div class="mt-6 p-4 border rounded-md bg-gray-50 border-gray-200">
    <h3 class="text-lg font-semibold text-gray-900">Detail Pesanan</h3>
    <p class="text-sm text-gray-700">Tamu: {{ $booking->guest_name }}</p>
    <p class="text-sm text-gray-700">Status: {{ $booking->status_label }}</p>
</div>
@endif
```

---

### d. Program yang dibuat harus menerapkan tipe data yang sesuai, mengikuti syntax Bahasa pemrograman yang digunakan, dan mempunyai struktur control percabangan (if..then..else) dan pengulangan (do while....for...dll)

**Jawaban:** Ya. Program telah menggunakan _casting_ tipe data di Model, serta struktur _control_ modern PHP (PHP 8 `match` expression yang setara percabangan multi kondisi `if-elseif` / `switch`) untuk melogika _status badge_. Pengulangan (`@foreach`) digunakan secara luas di file _view_ Blade saat merender tabel maupun list.

**Contoh Kode (app/Models/Booking.php):**

```php
/**
 * Casting tipe data kolom database ke tipe data native PHP.
 */
protected function casts(): array
{
    return [
        'check_in' => 'date',         // Format Date object
        'total_price' => 'decimal:2', // Format float/decimal
    ];
}

/**
 * Mendapatkan label warna badge berdasarkan status (Control structure: Match/Percabangan)
 */
public function getStatusBadgeAttribute(): string
{
    // Struktur percabangan fungsional (match mirip switch/if-else)
    return match ($this->status) {
        'pending' => 'bg-yellow-100 text-yellow-800',
        'confirmed' => 'bg-green-100 text-green-800',
        'rejected' => 'bg-red-100 text-red-800',
        'completed' => 'bg-blue-100 text-blue-800',
        default => 'bg-gray-100 text-gray-500',
    };
}
```

---

### e. Program yang dibuat harus menerapkan penggunaan prosedur, fungsi, atau method

**Jawaban:** Ya. Sebagian besar logika dipisahkan dalam _method_ objek (fungsi yang berada di dalam Class). Ini guna menerapkan prinsip DRY (_Don't Repeat Yourself_).

**Contoh Kode (app/Models/Booking.php):**

```php
/**
 * Method/fungsi untuk menghitung jumlah malam menginap secara otomatis
 *
 * @return int
 */
public function getNightsAttribute(): int
{
    // Menggunakan method bawaan Carbon Date (diffInDays)
    return $this->check_in->diffInDays($this->check_out);
}
```

---

### f. Program yang dibuat harus menggunakan Array

**Jawaban:** Ya. Program ini sangat bergantung pada struktur data Array (tipe dictionary/asosiatif di PHP). Salah satunya digunakan untuk menampung pemetaan _label status_ bahasa Inggris ke Bahasa Indonesia atau properti `fillable`.

**Contoh Kode (app/Models/Booking.php):**

```php
/**
 * Mendapatkan label status dalam Bahasa Indonesia.
 * Array asosiatif digunakan untuk mapping data
 */
public function getStatusLabelAttribute(): string
{
    // Penggunaan Array
    $labels = [
        'pending' => 'Menunggu',
        'confirmed' => 'Dikonfirmasi',
        'rejected' => 'Ditolak',
        'cancelled' => 'Dibatalkan',
        'completed' => 'Selesai',
    ];

    // Mengambil nilai dari array
    return $labels[$this->status] ?? 'Tidak Diketahui';
}
```

---

### g. Program yang dibuat harus mempunyai fasilitas untuk menyimpan dan membaca data di media penyimpan

**Jawaban:** Ya. Fasilitas penyimpanan (CRUD - _Create, Read, Update, Delete_) ditangani oleh ORM Eloquent di dalam Framework yang terkoneksi langsung dengan media penyimpan (_Database Server MySQL/SQLite/Postgres_). File relasi antar tabel dipanggil (dibaca/Read) secara dinamis menggunakan sintaks objek, serta data di _views_ diakses dari _Database_.

**Contoh Kode (app/Models/Booking.php):**

```php
/**
 * Relasi: Membaca (Read) data Villa yang terkait dengan data Booking ini ke Media Penyimpan/Database
 *
 * @return BelongsTo
 */
public function villa(): BelongsTo
{
    return $this->belongsTo(Villa::class);
    // ^ Berkomunikasi dengan database untuk menarik detail Villa terkait
}
```

---

### h. Program harus menerapkan hak akses tipe data dengan benar, mempunyai properties, menerapkan inheritance, polymorpy, overloading, dan interface.

**Jawaban:** Ya. Aplikasi ini 100% berbasis OOP model.

1. **Inheritance & Properties / Access Modifiers:** Model `Booking` menurunkan (_extends_) _core class_ `Model` asli milik Laravel, dengan menimpa _property protected_ `$fillable`.
2. **Polymorphism/Trait:** Penggunaan `use HasFactory;` untuk berbagi sifat _factory pattern_ antarkelas (sejenis trait _polymorphism_).
3. **Interfaces:** _Library_ Laravel di-"_behind the scenes_" menggunakan berbagai _interface_ seperti `Arrayable` dan _contract_ untuk mem-parsing respons.

**Contoh Kode (app/Models/Booking.php):**

```php
class Booking extends Model // <- Inheritance
{
    use HasFactory;         // <- Trait (Polymorphic composition)

    /**
     * Hak akses: Protected (Hanya Class ini dan class turunannya yang dapat mengakses)
     * Property: $fillable
     */
    protected $fillable = [
        'booking_code',
        'villa_id',
        'guest_name',
        // ...
    ];
}
```

---

### i. Program yang dibuat harus terdiri dari 2 atau lebih namespace atau package

**Jawaban:** Ya. Terdapat pengelompokan menggunakan `namespace` agar file tidak bentrok sekaligus menerapkan _package structure_ (mirip Java). Misalnya _Models_ ada di dalam `namespace App\Models;`, sedangkan _Controller_ atau komponen Livewire (_di dalam `/app/Livewire`_) tentu ada di dalam `namespace App\Livewire;`.

**Contoh Kode (app/Models/Booking.php):**

```php
<?php

namespace App\Models; // <- Deklarasi Namespace bagian Models

// Meng-import class dari package/namespace eksternal framework (Illuminate)
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
```

---

### j. Program yang dibuat harus menggunakan atau menanfaatkan eksternal library yang sudah ada dan tersedia

**Jawaban:** Ya. Program sangat bergantung pada _Dependency Manager_ Composer di backend dan NPM di frontend. _Library_ dari pihak ke-3 didefinisikan secara tegas untuk menunjang framework berjalan lancar.

**Contoh Kode (composer.json):**

```json
"require": {
    "php": "^8.2",
    "laravel/framework": "^12.0",
    "laravel/tinker": "^2.10.1",
    "livewire/livewire": "^4.2"  // <- Eksternal library ekosistem PHP/Laravel UI
},
```

---

### k. Program harus menggunakan basis data

**Jawaban:** Ya. Program ini sangat mengandalkan sistem basis data relasional. Model terhubung ke tabel _database_ dengan kolom-kolom persisten (seperti `guest_name`, `booking_code`, `status`, dsb). Pembuatan tabel menggunakan fitur Migrations yang dieksekusi via terminal (`php artisan migrate`).

---

### l. Program harus didokumentasikan dengan baik dengan standard atau guidelines dokumentasi sesuai dengan bahasa pemrograman yang digunakan

**Jawaban:** Ya. Program mematuhi sistem _DocBlocks_ standar (seperti _PHPDoc_), di mana bagian atas nama kelas maupun sebelum pendefinisian suatu method/fungsi akan diberi penjalasan (komentar multiline `/** ... */`), penanda param `@property`, maupun penanda penipuan pengembalian `@return`.

**Contoh Kode (app/Models/Booking.php):**

```php
/**
 * Model Booking — representasi reservasi tamu untuk sebuah villa.
 *
 * @property int      $id
 * @property string   $booking_code
 * @property int      $villa_id
 * @property string   $guest_name...
 * @property string   $status
 */
class Booking extends Model
{
    //...
```
