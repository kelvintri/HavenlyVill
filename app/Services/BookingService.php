<?php

namespace App\Services;

use App\Contracts\ReportableInterface;
use App\Models\Booking;
use App\Models\Villa;
use Illuminate\Support\Str;
use Carbon\Carbon;

/**
 * BookingService — service untuk mengelola proses booking.
 *
 * Menerapkan requirement (h):
 * - Inheritance: extends BaseService
 * - Interface: implements ReportableInterface
 * - Polymorphism: override getValidationRules()
 * - Overloading pattern: method createBooking() dengan parameter opsional
 *
 * @package App\Services
 */
class BookingService extends BaseService implements ReportableInterface
{
    /**
     * Instance VillaService untuk cek ketersediaan.
     * Access modifier: protected.
     *
     * @var VillaService
     */
    protected VillaService $villaService;

    /**
     * Constructor — memanggil parent dan inject VillaService.
     */
    public function __construct()
    {
        parent::__construct(new Booking(), 'BookingService');
        $this->villaService = new VillaService();
    }

    /**
     * Membuat booking baru dengan validasi ketersediaan.
     *
     * Method ini juga mendemonstrasikan pattern overloading (Req h):
     * parameter $options bersifat opsional dan mengubah perilaku method.
     *
     * @param array      $data    Data booking (Req f: array)
     * @param array|null $options Opsi tambahan: ['skip_availability' => bool, 'auto_confirm' => bool]
     * @return array    ['success' => bool, 'message' => string, 'booking' => Booking|null]
     */
    public function createBooking(array $data, ?array $options = null): array
    {
        // "Overloading" — perilaku berbeda berdasarkan parameter (Req h)
        $skipAvailability = $options['skip_availability'] ?? false;
        $autoConfirm = $options['auto_confirm'] ?? false;

        // Cek ketersediaan (Req d: percabangan if-else)
        if (!$skipAvailability) {
            $isAvailable = $this->villaService->checkAvailability(
                $data['villa_id'],
                $data['check_in'],
                $data['check_out']
            );

            if (!$isAvailable) {
                return [
                    'success' => false,
                    'message' => 'Villa tidak tersedia pada tanggal yang dipilih.',
                    'booking' => null,
                ];
            }
        }

        // Hitung total harga
        $totalPrice = $this->villaService->calculatePrice(
            $data['villa_id'],
            $data['check_in'],
            $data['check_out']
        );

        // Generate kode booking unik
        $bookingCode = $this->generateBookingCode();

        // Simpan ke database (Req g: menyimpan data)
        $booking = Booking::create([
            'booking_code' => $bookingCode,
            'villa_id' => $data['villa_id'],
            'guest_name' => $data['guest_name'],
            'guest_email' => $data['guest_email'],
            'guest_phone' => $data['guest_phone'],
            'guest_id_number' => $data['guest_id_number'] ?? null,
            'check_in' => $data['check_in'],
            'check_out' => $data['check_out'],
            'num_guests' => $data['num_guests'],
            'total_price' => $totalPrice,
            'status' => $autoConfirm ? 'confirmed' : 'pending',
            'notes' => $data['notes'] ?? null,
        ]);

        return [
            'success' => true,
            'message' => 'Booking berhasil dibuat dengan kode: ' . $bookingCode,
            'booking' => $booking,
        ];
    }

    /**
     * Mengubah status booking.
     * Menggunakan percabangan match sebagai control structure (Req d).
     *
     * @param int    $bookingId  ID booking
     * @param string $newStatus  Status baru
     * @param string $adminNotes Catatan admin (opsional)
     * @return array
     */
    public function updateStatus(int $bookingId, string $newStatus, string $adminNotes = ''): array
    {
        $booking = Booking::find($bookingId);

        if ($booking === null) {
            return ['success' => false, 'message' => 'Booking tidak ditemukan.'];
        }

        // Validasi transisi status yang diperbolehkan (Req d: percabangan)
        $allowedTransitions = [
            'pending' => ['confirmed', 'rejected'],
            'confirmed' => ['cancelled', 'completed'],
            'rejected' => [],
            'cancelled' => [],
            'completed' => [],
        ];

        $currentAllowed = $allowedTransitions[$booking->status] ?? [];

        if (!in_array($newStatus, $currentAllowed)) {
            return [
                'success' => false,
                'message' => "Tidak bisa mengubah status dari '{$booking->status}' ke '{$newStatus}'.",
            ];
        }

        $booking->update([
            'status' => $newStatus,
            'admin_notes' => $adminNotes ?: $booking->admin_notes,
        ]);

        return [
            'success' => true,
            'message' => 'Status booking berhasil diubah menjadi ' . $newStatus,
        ];
    }

    /**
     * Mencari booking berdasarkan kode booking (Req g: membaca data).
     *
     * @param string $bookingCode
     * @return Booking|null
     */
    public function findByCode(string $bookingCode): ?Booking
    {
        return Booking::where('booking_code', $bookingCode)
            ->with('villa')
            ->first();
    }

    /**
     * Menghasilkan laporan booking berdasarkan periode.
     * Implementasi dari ReportableInterface (Req h: interface).
     *
     * @param string $period 'daily', 'weekly', 'monthly', 'yearly'
     * @return array Data laporan (Req f: penggunaan array)
     */
    public function generateReport(string $period): array
    {
        $now = Carbon::now();

        // Tentukan rentang tanggal berdasarkan period (Req d: percabangan)
        $startDate = match ($period) {
            'daily' => $now->copy()->startOfDay(),
            'weekly' => $now->copy()->startOfWeek(),
            'monthly' => $now->copy()->startOfMonth(),
            'yearly' => $now->copy()->startOfYear(),
            default => $now->copy()->startOfMonth(),
        };

        $bookings = Booking::whereBetween('created_at', [$startDate, $now])->get();

        return [
            'period' => $period,
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $now->format('Y-m-d'),
            'total_bookings' => $bookings->count(),
            'total_revenue' => $bookings->where('status', 'confirmed')->sum('total_price'),
            'status_breakdown' => [
                'pending' => $bookings->where('status', 'pending')->count(),
                'confirmed' => $bookings->where('status', 'confirmed')->count(),
                'rejected' => $bookings->where('status', 'rejected')->count(),
                'cancelled' => $bookings->where('status', 'cancelled')->count(),
                'completed' => $bookings->where('status', 'completed')->count(),
            ],
        ];
    }

    /**
     * Mendapatkan statistik ringkasan keseluruhan.
     * Implementasi dari ReportableInterface (Req h: interface).
     *
     * @return array Array statistik (Req f: penggunaan array)
     */
    public function getStatistics(): array
    {
        $totalBookings = Booking::count();
        $totalRevenue = Booking::whereIn('status', ['confirmed', 'completed'])->sum('total_price');
        $pendingCount = Booking::where('status', 'pending')->count();
        $totalVillas = Villa::where('is_active', true)->count();

        return [
            'total_bookings' => $totalBookings,
            'total_revenue' => $totalRevenue,
            'pending_bookings' => $pendingCount,
            'active_villas' => $totalVillas,
        ];
    }

    /**
     * Override method abstract dari BaseService — menerapkan polymorphism (Req h).
     *
     * @param array $data
     * @return array Aturan validasi khusus Booking
     */
    protected function getValidationRules(array $data = []): array
    {
        return [
            'villa_id' => 'required|exists:villas,id',
            'guest_name' => 'required|string|max:255',
            'guest_email' => 'required|email|max:255',
            'guest_phone' => 'required|string|max:20',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'num_guests' => 'required|integer|min:1',
        ];
    }

    /**
     * Generate kode booking unik (Req e: penggunaan fungsi).
     * Access modifier: private — hanya digunakan internal.
     *
     * @return string Kode booking format: VB-XXXXXXXX
     */
    private function generateBookingCode(): string
    {
        do {
            $code = 'VB-' . strtoupper(Str::random(8));
        } while (Booking::where('booking_code', $code)->exists());

        return $code;
    }
}
