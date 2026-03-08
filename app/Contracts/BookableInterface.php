<?php

namespace App\Contracts;

use Illuminate\Support\Collection;

/**
 * Interface BookableInterface — kontrak untuk entitas yang bisa di-booking.
 *
 * Menerapkan requirement (h): penggunaan interface.
 * Setiap class yang mengimplementasikan interface ini
 * wajib menyediakan method untuk cek ketersediaan dan kalkulasi harga.
 *
 * @package App\Contracts
 */
interface BookableInterface
{
    /**
     * Mengecek ketersediaan unit pada rentang tanggal tertentu.
     *
     * @param int    $unitId    ID dari unit yang dicek
     * @param string $startDate Tanggal mulai (format: Y-m-d)
     * @param string $endDate   Tanggal selesai (format: Y-m-d)
     * @return bool  True jika tersedia, false jika tidak
     */
    public function checkAvailability(int $unitId, string $startDate, string $endDate): bool;

    /**
     * Menghitung total harga berdasarkan rentang tanggal.
     *
     * @param int    $unitId    ID dari unit
     * @param string $startDate Tanggal mulai (format: Y-m-d)
     * @param string $endDate   Tanggal selesai (format: Y-m-d)
     * @return float Total harga
     */
    public function calculatePrice(int $unitId, string $startDate, string $endDate): float;

    /**
     * Mendapatkan daftar booking dengan filter opsional.
     *
     * @param array $filters Array filter (Req f: penggunaan array)
     * @return Collection
     */
    public function getBookings(array $filters = []): Collection;
}
