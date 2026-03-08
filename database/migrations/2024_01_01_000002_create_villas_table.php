<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migrasi: Membuat tabel 'villas'.
 * Menyimpan informasi lengkap setiap villa termasuk amenities dalam format JSON (array).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('villas', function (Blueprint $table) {
            // Primary key
            $table->id();

            // Informasi dasar villa
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('location');

            // Harga dan kapasitas — tipe data numerik
            $table->decimal('price_per_night', 12, 2);
            $table->integer('max_guests');
            $table->integer('bedrooms');
            $table->integer('bathrooms');

            // Data array — disimpan sebagai JSON (Req f: penggunaan array)
            $table->json('amenities')->nullable();
            $table->json('images')->nullable();

            // Status aktif
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('villas');
    }
};
