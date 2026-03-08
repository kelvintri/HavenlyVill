<?php

namespace App\Livewire\Admin;

use App\Services\BookingService;
use App\Services\VillaService;
use Livewire\Component;

/**
 * Livewire Component: Dashboard — halaman utama admin.
 *
 * Menampilkan statistik booking, revenue, dan overview villa.
 * Menggunakan array dan Collection (Req f).
 *
 * @package App\Livewire\Admin
 */
class Dashboard extends Component
{
    /**
     * Data statistik untuk dashboard.
     *
     * @var array
     */
    public array $statistics = [];

    /**
     * Daftar booking terbaru.
     *
     * @var array
     */
    public array $recentBookings = [];

    /**
     * Lifecycle: inisialisasi data saat komponen dimuat.
     *
     * @return void
     */
    public function mount(): void
    {
        $bookingService = new BookingService();
        $villaService = new VillaService();

        // Ambil statistik (Req f: penggunaan array)
        $this->statistics = $bookingService->getStatistics();

        // Ambil 5 booking terbaru
        $this->recentBookings = \App\Models\Booking::with('villa')
            ->latest()
            ->take(5)
            ->get()
            ->toArray();
    }

    /**
     * Render komponen dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.admin.dashboard')
            ->layout('components.layouts.admin', ['title' => 'Dashboard']);
    }
}
