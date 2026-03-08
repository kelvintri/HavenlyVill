<?php

namespace App\Livewire\Guest;

use App\Models\Villa;
use App\Services\BookingService;
use App\Services\VillaService;
use Livewire\Component;

/**
 * Livewire Component: BookingForm — form reservasi untuk tamu.
 *
 * Menampilkan form booking dengan cek ketersediaan real-time.
 * Menerapkan: input/output (Req c), percabangan (Req d), method (Req e), array (Req f), simpan data (Req g).
 *
 * @package App\Livewire\Guest
 */
class BookingForm extends Component
{
    /** Villa yang di-booking */
    public Villa $villa;

    /** Form fields (Req c: interface input) */
    public string $guest_name = '';
    public string $guest_email = '';
    public string $guest_phone = '';
    public string $guest_id_number = '';
    public string $check_in = '';
    public string $check_out = '';
    public int $num_guests = 1;
    public string $notes = '';

    /** Status ketersediaan */
    public ?bool $isAvailable = null;
    public string $estimatedPrice = '';

    /** Hasil booking */
    public bool $bookingSuccess = false;
    public string $bookingCode = '';
    public string $bookingMessage = '';

    /**
     * Mengecek ketersediaan villa pada tanggal yang dipilih.
     * Menggunakan VillaService (Req e, h: interface & method).
     *
     * @return void
     */
    public function checkAvailability(): void
    {
        // Validasi tanggal terlebih dahulu (Req d: percabangan)
        if (empty($this->check_in) || empty($this->check_out)) {
            $this->isAvailable = null;
            return;
        }

        if ($this->check_out <= $this->check_in) {
            $this->isAvailable = null;
            $this->addError('check_out', 'Tanggal check-out harus setelah check-in.');
            return;
        }

        $villaService = new VillaService();
        $this->isAvailable = $villaService->checkAvailability(
            $this->villa->id,
            $this->check_in,
            $this->check_out
        );

        // Hitung estimasi harga (Req e: penggunaan fungsi)
        if ($this->isAvailable) {
            $price = $villaService->calculatePrice(
                $this->villa->id,
                $this->check_in,
                $this->check_out
            );
            $this->estimatedPrice = 'Rp ' . number_format($price, 0, ',', '.');
        }
    }

    /**
     * Handler saat tanggal berubah — auto cek ketersediaan.
     *
     * @return void
     */
    public function updatedCheckIn(): void
    {
        $this->checkAvailability();
    }

    /**
     * Handler saat tanggal check-out berubah.
     *
     * @return void
     */
    public function updatedCheckOut(): void
    {
        $this->checkAvailability();
    }

    /**
     * Submit booking — menyimpan data reservasi (Req g: simpan data).
     *
     * @return void
     */
    public function submitBooking(): void
    {
        // Validasi (Req d: percabangan & control structure)
        $this->validate([
            'guest_name' => 'required|string|max:255',
            'guest_email' => 'required|email|max:255',
            'guest_phone' => 'required|string|max:20',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'num_guests' => 'required|integer|min:1|max:' . $this->villa->max_guests,
        ]);

        $bookingService = new BookingService();

        // Menggunakan array untuk data booking (Req f)
        $result = $bookingService->createBooking([
            'villa_id' => $this->villa->id,
            'guest_name' => $this->guest_name,
            'guest_email' => $this->guest_email,
            'guest_phone' => $this->guest_phone,
            'guest_id_number' => $this->guest_id_number,
            'check_in' => $this->check_in,
            'check_out' => $this->check_out,
            'num_guests' => $this->num_guests,
            'notes' => $this->notes,
        ]);

        // Percabangan berdasarkan hasil (Req d)
        if ($result['success']) {
            $this->bookingSuccess = true;
            $this->bookingCode = $result['booking']->booking_code;
            $this->bookingMessage = $result['message'];
        } else {
            $this->addError('booking', $result['message']);
        }
    }

    /**
     * Render komponen booking form (Req c: output/tampilan).
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.guest.booking-form');
    }
}
