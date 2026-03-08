<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migrasi: Membuat tabel 'blocked_dates'.
 * Menyimpan tanggal yang diblokir oleh admin agar tidak bisa di-booking.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blocked_dates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('villa_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->string('reason')->nullable();
            $table->timestamps();

            // Unique constraint: satu villa tidak bisa punya tanggal blokir duplikat
            $table->unique(['villa_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blocked_dates');
    }
};
