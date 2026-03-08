{{-- Calendar View — Tampilan Kalender Ketersediaan --}}
<div>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Kalender Ketersediaan</h1>
        <p class="text-gray-500 mt-1">Lihat ketersediaan villa per bulan.</p>
    </div>

    {{-- Villa Selector & Month Navigation --}}
    <div class="bg-white rounded-xl border border-gray-200 p-4 mb-6">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="w-full sm:w-64">
                <select wire:model.live="selectedVillaId" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                    @foreach ($villas as $villa)
                        <option value="{{ $villa->id }}">{{ $villa->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-center gap-4">
                <button wire:click="previousMonth" class="p-2 rounded-lg border border-gray-300 hover:bg-gray-50 transition">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <h2 class="text-lg font-semibold text-gray-900 min-w-[180px] text-center">{{ $monthLabel }}</h2>
                <button wire:click="nextMonth" class="p-2 rounded-lg border border-gray-300 hover:bg-gray-50 transition">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Legend --}}
    <div class="flex flex-wrap items-center gap-4 sm:gap-6 mb-4">
        <div class="flex items-center gap-2">
            <div class="w-4 h-4 rounded bg-emerald-100 border border-emerald-300"></div>
            <span class="text-sm text-gray-600">Tersedia</span>
        </div>
        <div class="flex items-center gap-2">
            <div class="w-4 h-4 rounded bg-amber-100 border border-amber-300"></div>
            <span class="text-sm text-gray-600">Booking Pending</span>
        </div>
        <div class="flex items-center gap-2">
            <div class="w-4 h-4 rounded bg-red-100 border border-red-300"></div>
            <span class="text-sm text-gray-600">Dikonfirmasi</span>
        </div>
        <div class="flex items-center gap-2">
            <div class="w-4 h-4 rounded bg-blue-200 border border-blue-400"></div>
            <span class="text-sm text-gray-600">Hari Ini</span>
        </div>
        <div class="flex items-center gap-2">
            <div class="w-4 h-4 rounded bg-gray-100 border border-gray-200"></div>
            <span class="text-sm text-gray-600">Lewat</span>
        </div>
    </div>

    {{-- Calendar Grid --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        {{-- Day Headers --}}
        <div class="grid grid-cols-7 bg-gray-50 border-b border-gray-200">
            @php $dayNames = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab']; @endphp
            @foreach ($dayNames as $dayName)
                <div class="px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase">{{ $dayName }}</div>
            @endforeach
        </div>

        {{-- Calendar Days --}}
        <div class="grid grid-cols-7">
            {{-- Empty cells for offset --}}
            @if (count($calendarData) > 0)
                @for ($i = 0; $i < $calendarData[0]['dayOfWeek']; $i++)
                    <div class="p-2 border-b border-r border-gray-100 min-h-[80px]"></div>
                @endfor
            @endif

            {{-- Pengulangan: render setiap hari (Req d: loop) --}}
            @foreach ($calendarData as $day)
                @php
                    $hasBooking = $day['booking'] !== null;

                    // Percabangan untuk menentukan kelas CSS (Req d: if-else)
                    if ($day['isToday'] && $hasBooking) {
                        $cellClass = 'bg-blue-50 border-blue-200';
                        $textClass = 'text-blue-700 font-bold';
                    } elseif ($day['isToday']) {
                        $cellClass = 'bg-blue-50 border-blue-200';
                        $textClass = 'text-blue-700 font-bold';
                    } elseif ($hasBooking && $day['booking']['status'] === 'confirmed') {
                        $cellClass = 'bg-red-50 border-red-200';
                        $textClass = 'text-red-700 font-medium';
                    } elseif ($hasBooking && $day['booking']['status'] === 'pending') {
                        $cellClass = 'bg-amber-50 border-amber-200';
                        $textClass = 'text-amber-700 font-medium';
                    } elseif ($day['isUnavailable']) {
                        $cellClass = 'bg-red-50 border-red-100';
                        $textClass = 'text-red-600 font-medium';
                    } elseif ($day['isPast']) {
                        $cellClass = 'bg-gray-50 border-gray-100';
                        $textClass = 'text-gray-400';
                    } else {
                        $cellClass = 'bg-emerald-50/50 border-gray-100';
                        $textClass = 'text-gray-700';
                    }
                @endphp
                <div class="p-2 border-b border-r {{ $cellClass }} min-h-[80px] transition hover:opacity-80 relative group">
                    <span class="text-sm {{ $textClass }}">{{ $day['day'] }}</span>

                    {{-- Info booking di tanggal yg terbooked --}}
                    @if ($hasBooking)
                        <div class="mt-1 space-y-0.5">
                            @if ($day['booking']['status'] === 'confirmed')
                                <span class="block px-1.5 py-0.5 bg-red-100 text-red-700 text-[10px] font-semibold rounded truncate">
                                    {{ $day['booking']['guest_name'] }}
                                </span>
                            @else
                                <span class="block px-1.5 py-0.5 bg-amber-100 text-amber-700 text-[10px] font-semibold rounded truncate">
                                    {{ $day['booking']['guest_name'] }}
                                </span>
                            @endif
                            <span class="block text-[9px] text-gray-500 font-mono truncate">{{ $day['booking']['booking_code'] }}</span>
                        </div>

                        {{-- Tooltip on hover --}}
                        <div class="hidden group-hover:block absolute z-10 left-0 top-full mt-1 w-52 bg-gray-900 text-white text-xs rounded-lg p-3 shadow-xl pointer-events-none">
                            <p class="font-semibold mb-1">{{ $day['booking']['guest_name'] }}</p>
                            <p class="text-gray-300">Kode: {{ $day['booking']['booking_code'] }}</p>
                            <p class="text-gray-300">{{ $day['booking']['check_in'] }} — {{ $day['booking']['check_out'] }}</p>
                            <p class="mt-1">
                                @if ($day['booking']['status'] === 'confirmed')
                                    <span class="px-1.5 py-0.5 bg-red-500/30 text-red-200 rounded text-[10px]">Dikonfirmasi</span>
                                @else
                                    <span class="px-1.5 py-0.5 bg-amber-500/30 text-amber-200 rounded text-[10px]">Pending</span>
                                @endif
                            </p>
                        </div>
                    @elseif ($day['isUnavailable'])
                        <div class="mt-1">
                            <span class="inline-block px-1.5 py-0.5 bg-red-100 text-red-600 text-[10px] rounded">Diblokir</span>
                        </div>
                    @endif

                    @if ($day['isToday'])
                        <div class="mt-0.5">
                            <span class="inline-block px-1.5 py-0.5 bg-blue-100 text-blue-600 text-[10px] rounded">Hari Ini</span>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>

