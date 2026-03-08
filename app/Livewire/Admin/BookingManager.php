<?php

namespace App\Livewire\Admin;

use App\Models\Booking;
use App\Models\Villa;
use App\Services\BookingService;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Livewire Component: BookingManager — mengelola daftar booking.
 *
 * Menampilkan daftar booking dengan filter dan aksi approve/reject.
 * Menerapkan: percabangan (Req d), array (Req f), method (Req e).
 *
 * @package App\Livewire\Admin
 */
class BookingManager extends Component
{
    use WithPagination;

    /** Filter berdasarkan status booking */
    public string $filterStatus = '';

    /** Filter berdasarkan villa */
    public string $filterVilla = '';

    /** Kata kunci pencarian */
    public string $search = '';

    /** Data booking yang sedang dilihat detailnya */
    public ?array $selectedBooking = null;

    /** Catatan admin saat update status */
    public string $adminNotes = '';

    /**
     * Mengubah status booking menjadi confirmed.
     * Menggunakan BookingService (Req e: penggunaan method).
     *
     * @param int $bookingId
     * @return void
     */
    public function confirmBooking(int $bookingId): void
    {
        $service = new BookingService();
        $result = $service->updateStatus($bookingId, 'confirmed', $this->adminNotes);

        // Percabangan (Req d)
        if ($result['success']) {
            session()->flash('message', $result['message']);
        } else {
            session()->flash('error', $result['message']);
        }

        $this->adminNotes = '';
        $this->selectedBooking = null;
    }

    /**
     * Mengubah status booking menjadi rejected.
     *
     * @param int $bookingId
     * @return void
     */
    public function rejectBooking(int $bookingId): void
    {
        $service = new BookingService();
        $result = $service->updateStatus($bookingId, 'rejected', $this->adminNotes);

        if ($result['success']) {
            session()->flash('message', $result['message']);
        } else {
            session()->flash('error', $result['message']);
        }

        $this->adminNotes = '';
        $this->selectedBooking = null;
    }

    /**
     * Menandai booking selesai.
     *
     * @param int $bookingId
     * @return void
     */
    public function completeBooking(int $bookingId): void
    {
        $service = new BookingService();
        $result = $service->updateStatus($bookingId, 'completed', $this->adminNotes);

        if ($result['success']) {
            session()->flash('message', $result['message']);
        } else {
            session()->flash('error', $result['message']);
        }

        $this->adminNotes = '';
    }

    /**
     * Menampilkan detail booking tertentu.
     *
     * @param int $bookingId
     * @return void
     */
    public function viewBooking(int $bookingId): void
    {
        $booking = Booking::with('villa')->find($bookingId);
        $this->selectedBooking = $booking?->toArray();
    }

    /**
     * Menutup modal detail booking.
     *
     * @return void
     */
    public function closeDetail(): void
    {
        $this->selectedBooking = null;
        $this->adminNotes = '';
    }

    /**
     * Reset filter pencarian saat berubah.
     *
     * @return void
     */
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Render komponen booking manager.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        $query = Booking::with('villa');

        // Filter berdasarkan status (Req d: percabangan)
        if ($this->filterStatus !== '') {
            $query->where('status', $this->filterStatus);
        }

        // Filter berdasarkan villa
        if ($this->filterVilla !== '') {
            $query->where('villa_id', $this->filterVilla);
        }

        // Pencarian berdasarkan nama/email/kode
        if ($this->search !== '') {
            $query->where(function ($q) {
                $q->where('guest_name', 'like', "%{$this->search}%")
                  ->orWhere('guest_email', 'like', "%{$this->search}%")
                  ->orWhere('booking_code', 'like', "%{$this->search}%");
            });
        }

        $bookings = $query->latest()->paginate(10);
        $villas = Villa::where('is_active', true)->get();

        return view('livewire.admin.booking-manager', [
            'bookings' => $bookings,
            'villas' => $villas,
        ])->layout('components.layouts.admin', ['title' => 'Kelola Booking']);
    }
}
