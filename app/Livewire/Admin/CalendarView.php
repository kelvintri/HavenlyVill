<?php

namespace App\Livewire\Admin;

use App\Models\Villa;
use App\Services\VillaService;
use Carbon\Carbon;
use Livewire\Component;

/**
 * Livewire Component: CalendarView — tampilan kalender ketersediaan villa.
 *
 * Menampilkan kalender bulanan per villa dengan status booking.
 * Menerapkan: loop/pengulangan (Req d), array (Req f).
 *
 * @package App\Livewire\Admin
 */
class CalendarView extends Component
{
    /** ID villa yang dipilih */
    public int $selectedVillaId = 0;

    /** Bulan dan tahun yang ditampilkan (format: Y-m) */
    public string $currentMonth = '';

    /** Data kalender dalam bentuk array */
    public array $calendarData = [];

    /** Daftar tanggal yang tidak tersedia */
    public array $unavailableDates = [];

    /** Detail booking per tanggal (key = tanggal, value = info booking) */
    public array $bookingsByDate = [];

    /**
     * Lifecycle: inisialisasi data awal.
     *
     * @return void
     */
    public function mount(): void
    {
        $this->currentMonth = Carbon::now()->format('Y-m');
        $firstVilla = Villa::first();

        if ($firstVilla !== null) {
            $this->selectedVillaId = $firstVilla->id;
            $this->loadCalendar();
        }
    }

    /**
     * Memuat data kalender untuk bulan dan villa yang dipilih.
     * Menggunakan loop/pengulangan (Req d) dan array (Req f).
     *
     * @return void
     */
    public function loadCalendar(): void
    {
        // Percabangan (Req d): cek apakah villa dipilih
        if ($this->selectedVillaId === 0) {
            $this->calendarData = [];
            $this->bookingsByDate = [];
            return;
        }

        $villaService = new VillaService();
        $this->unavailableDates = $villaService->getUnavailableDates(
            $this->selectedVillaId,
            $this->currentMonth
        );

        // Ambil detail booking per tanggal untuk ditampilkan di kalender
        $this->bookingsByDate = $villaService->getBookingsByDate(
            $this->selectedVillaId,
            $this->currentMonth
        );

        $start = Carbon::parse($this->currentMonth)->startOfMonth();
        $end = Carbon::parse($this->currentMonth)->endOfMonth();
        $this->calendarData = [];

        // Pengulangan: while loop (Req d)
        $current = $start->copy();
        while ($current->lte($end)) {
            $dateStr = $current->format('Y-m-d');
            $bookingInfo = $this->bookingsByDate[$dateStr] ?? null;

            $this->calendarData[] = [
                'date' => $dateStr,
                'day' => $current->day,
                'dayOfWeek' => $current->dayOfWeek,
                'isToday' => $current->isToday(),
                'isPast' => $current->isPast() && !$current->isToday(),
                'isUnavailable' => in_array($dateStr, $this->unavailableDates),
                // Data booking untuk ditampilkan di sel kalender
                'booking' => $bookingInfo,
            ];
            $current->addDay();
        }
    }

    /**
     * Navigasi ke bulan sebelumnya.
     *
     * @return void
     */
    public function previousMonth(): void
    {
        $this->currentMonth = Carbon::parse($this->currentMonth)->subMonth()->format('Y-m');
        $this->loadCalendar();
    }

    /**
     * Navigasi ke bulan berikutnya.
     *
     * @return void
     */
    public function nextMonth(): void
    {
        $this->currentMonth = Carbon::parse($this->currentMonth)->addMonth()->format('Y-m');
        $this->loadCalendar();
    }

    /**
     * Handler: saat villa dipilih berubah.
     *
     * @return void
     */
    public function updatedSelectedVillaId(): void
    {
        $this->loadCalendar();
    }

    /**
     * Render komponen kalender.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        $villas = Villa::where('is_active', true)->get();
        $monthLabel = Carbon::parse($this->currentMonth)->translatedFormat('F Y');

        return view('livewire.admin.calendar-view', [
            'villas' => $villas,
            'monthLabel' => $monthLabel,
        ])->layout('components.layouts.admin', ['title' => 'Kalender']);
    }
}
