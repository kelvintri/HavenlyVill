<?php

namespace App\Services;

use App\Contracts\BookableInterface;
use App\Models\Villa;
use App\Models\Booking;
use App\Models\BlockedDate;
use Illuminate\Support\Collection;
use Carbon\Carbon;

/**
 * VillaService — service untuk mengelola villa dan cek ketersediaan.
 *
 * Menerapkan requirement (h):
 * - Inheritance: extends BaseService
 * - Interface: implements BookableInterface
 * - Polymorphism: override getValidationRules()
 *
 * @package App\Services
 */
class VillaService extends BaseService implements BookableInterface
{
    /**
     * Constructor — memanggil parent constructor dengan model Villa.
     */
    public function __construct()
    {
        parent::__construct(new Villa(), 'VillaService');
    }

    /**
     * Mengecek ketersediaan villa pada rentang tanggal tertentu.
     * Cek apakah ada booking yang tumpang tindih atau tanggal yang diblokir.
     *
     * @param int    $unitId    ID villa
     * @param string $startDate Tanggal check-in
     * @param string $endDate   Tanggal check-out
     * @return bool  True jika tersedia
     */
    public function checkAvailability(int $unitId, string $startDate, string $endDate): bool
    {
        // Cek apakah ada booking yang overlap (Req d: percabangan if-else)
        $hasConflict = Booking::where('villa_id', $unitId)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where(function ($query) use ($startDate, $endDate) {
                $query->where(function ($q) use ($startDate, $endDate) {
                    $q->where('check_in', '<', $endDate)
                      ->where('check_out', '>', $startDate);
                });
            })
            ->exists();

        if ($hasConflict) {
            return false;
        }

        // Cek apakah ada tanggal yang diblokir dalam rentang tersebut
        $hasBlockedDates = BlockedDate::where('villa_id', $unitId)
            ->whereBetween('date', [$startDate, $endDate])
            ->exists();

        return !$hasBlockedDates;
    }

    /**
     * Menghitung total harga berdasarkan jumlah malam.
     *
     * @param int    $unitId    ID villa
     * @param string $startDate Tanggal check-in
     * @param string $endDate   Tanggal check-out
     * @return float Total harga
     */
    public function calculatePrice(int $unitId, string $startDate, string $endDate): float
    {
        $villa = Villa::findOrFail($unitId);
        $nights = Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate));

        // Pastikan minimal 1 malam
        $nights = max(1, $nights);

        return (float) ($villa->price_per_night * $nights);
    }

    /**
     * Mendapatkan daftar booking untuk villa tertentu.
     *
     * @param array $filters Array filter opsional (Req f: array)
     * @return Collection
     */
    public function getBookings(array $filters = []): Collection
    {
        $query = Booking::query();

        // Pengulangan untuk memproses filter (Req d: loop)
        foreach ($filters as $key => $value) {
            if ($key === 'villa_id' && $value !== null) {
                $query->where('villa_id', $value);
            } elseif ($key === 'status' && $value !== null) {
                $query->where('status', $value);
            } elseif ($key === 'date_from' && $value !== null) {
                $query->where('check_in', '>=', $value);
            } elseif ($key === 'date_to' && $value !== null) {
                $query->where('check_out', '<=', $value);
            }
        }

        return $query->with('villa')->latest()->get();
    }

    /**
     * Mendapatkan semua villa yang aktif.
     *
     * @return Collection
     */
    public function getActiveVillas(): Collection
    {
        return Villa::where('is_active', true)->get();
    }

    /**
     * Mendapatkan villa berdasarkan slug.
     *
     * @param string $slug
     * @return Villa|null
     */
    public function findBySlug(string $slug): ?Villa
    {
        return Villa::where('slug', $slug)->first();
    }

    /**
     * Mendapatkan tanggal-tanggal yang tidak tersedia untuk sebuah villa.
     * Mengembalikan array tanggal (Req f: penggunaan array).
     *
     * @param int    $villaId   ID villa
     * @param string $monthYear Bulan dan tahun (format: Y-m)
     * @return array Array berisi tanggal-tanggal yang tidak tersedia
     */
    public function getUnavailableDates(int $villaId, string $monthYear): array
    {
        $startOfMonth = Carbon::parse($monthYear)->startOfMonth();
        $endOfMonth = Carbon::parse($monthYear)->endOfMonth();
        $unavailableDates = [];

        // Ambil tanggal dari booking yang sudah dikonfirmasi/pending
        $bookings = Booking::where('villa_id', $villaId)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('check_in', '<=', $endOfMonth)
            ->where('check_out', '>=', $startOfMonth)
            ->get();

        // Pengulangan (Req d: loop) — iterasi setiap booking
        foreach ($bookings as $booking) {
            $current = Carbon::parse($booking->check_in);
            $end = Carbon::parse($booking->check_out);

            // Loop untuk setiap hari dalam rentang booking
            while ($current->lt($end)) {
                if ($current->between($startOfMonth, $endOfMonth)) {
                    $unavailableDates[] = $current->format('Y-m-d');
                }
                $current->addDay();
            }
        }

        // Tambahkan tanggal yang diblokir
        $blockedDates = BlockedDate::where('villa_id', $villaId)
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->pluck('date')
            ->map(fn ($date) => Carbon::parse($date)->format('Y-m-d'))
            ->toArray();

        // Gabungkan dan hapus duplikat (Req f: operasi array)
        $unavailableDates = array_unique(array_merge($unavailableDates, $blockedDates));
        sort($unavailableDates);

        return $unavailableDates;
    }

    /**
     * Mendapatkan detail booking per tanggal untuk kalender.
     * Mengembalikan array asosiatif [tanggal => info booking] (Req f).
     *
     * @param int    $villaId   ID villa
     * @param string $monthYear Bulan dan tahun (format: Y-m)
     * @return array Array asosiatif [tanggal => ['guest_name', 'booking_code', 'status', 'status_color']]
     */
    public function getBookingsByDate(int $villaId, string $monthYear): array
    {
        $startOfMonth = Carbon::parse($monthYear)->startOfMonth();
        $endOfMonth = Carbon::parse($monthYear)->endOfMonth();
        $bookingsByDate = [];

        // Ambil booking aktif (pending + confirmed) yang overlap bulan ini
        $bookings = Booking::where('villa_id', $villaId)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('check_in', '<=', $endOfMonth)
            ->where('check_out', '>=', $startOfMonth)
            ->get();

        // Warna berdasarkan status (Req f: array mapping)
        $statusColors = [
            'pending' => 'amber',
            'confirmed' => 'red',
        ];

        $statusLabels = [
            'pending' => 'Pending',
            'confirmed' => 'Dikonfirmasi',
        ];

        // Iterasi setiap booking dan mapping ke tanggal (Req d: loop)
        foreach ($bookings as $booking) {
            $current = Carbon::parse($booking->check_in);
            $end = Carbon::parse($booking->check_out);

            while ($current->lt($end)) {
                if ($current->between($startOfMonth, $endOfMonth)) {
                    $dateStr = $current->format('Y-m-d');
                    $bookingsByDate[$dateStr] = [
                        'guest_name' => $booking->guest_name,
                        'booking_code' => $booking->booking_code,
                        'status' => $booking->status,
                        'status_label' => $statusLabels[$booking->status] ?? $booking->status,
                        'status_color' => $statusColors[$booking->status] ?? 'gray',
                        'check_in' => $booking->check_in->format('d M'),
                        'check_out' => $booking->check_out->format('d M'),
                    ];
                }
                $current->addDay();
            }
        }

        return $bookingsByDate;
    }

    /**
     * Override method abstract dari BaseService — menerapkan polymorphism (Req h).
     *
     * @param array $data
     * @return array Aturan validasi khusus Villa
     */
    protected function getValidationRules(array $data = []): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'price_per_night' => 'required|numeric|min:0',
            'max_guests' => 'required|integer|min:1',
            'bedrooms' => 'required|integer|min:1',
            'bathrooms' => 'required|integer|min:1',
        ];
    }
}
