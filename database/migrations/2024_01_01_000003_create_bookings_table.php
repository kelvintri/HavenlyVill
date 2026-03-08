<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migrasi: Membuat tabel 'bookings'.
 * Menyimpan data reservasi tamu untuk setiap villa.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            // Primary key
            $table->id();

            // Kode booking unik untuk tracking oleh tamu
            $table->string('booking_code')->unique();

            // Foreign key ke tabel villas
            $table->foreignId('villa_id')->constrained()->cascadeOnDelete();

            // Informasi tamu
            $table->string('guest_name');
            $table->string('guest_email');
            $table->string('guest_phone');
            $table->string('guest_id_number')->nullable();

            // Tanggal check-in dan check-out
            $table->date('check_in');
            $table->date('check_out');
            $table->integer('num_guests');

            // Harga total — tipe data desimal
            $table->decimal('total_price', 12, 2);

            // Status booking dengan enum
            $table->enum('status', [
                'pending',
                'confirmed',
                'rejected',
                'cancelled',
                'completed',
            ])->default('pending');

            // Catatan
            $table->text('notes')->nullable();
            $table->text('admin_notes')->nullable();

            $table->timestamps();

            // Index untuk pencarian berdasarkan tanggal
            $table->index(['villa_id', 'check_in', 'check_out']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
