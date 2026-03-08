<?php

namespace App\Livewire\Guest;

use App\Services\BookingService;
use Livewire\Component;

/**
 * Livewire Component: BookingStatus — cek status booking oleh tamu.
 *
 * Menerapkan: input/output (Req c), method (Req e), baca data (Req g).
 *
 * @package App\Livewire\Guest
 */
class BookingStatus extends Component
{
    /** Kode booking yang dimasukkan tamu (input) */
    public string $bookingCode = '';

    /** Data booking yang ditemukan (output) */
    public ?array $booking = null;

    /** Flag apakah sudah dicari */
    public bool $searched = false;

    /**
     * Mencari booking berdasarkan kode.
     * Menggunakan BookingService (Req e: method, Req g: baca data).
     *
     * @return void
     */
    public function searchBooking(): void
    {
        $this->validate([
            'bookingCode' => 'required|string',
        ]);

        $service = new BookingService();
        $result = $service->findByCode($this->bookingCode);

        $this->searched = true;

        // Percabangan (Req d): cek apakah booking ditemukan
        if ($result !== null) {
            $this->booking = $result->toArray();
            $this->booking['villa_name'] = $result->villa->name ?? '-';
            $this->booking['status_label'] = $result->status_label;
            $this->booking['status_badge'] = $result->status_badge;
            $this->booking['formatted_total_price'] = $result->formatted_total_price;
        } else {
            $this->booking = null;
        }
    }

    /**
     * Render komponen booking status.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.guest.booking-status');
    }
}
