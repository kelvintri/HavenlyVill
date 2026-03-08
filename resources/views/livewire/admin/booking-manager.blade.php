{{-- Booking Manager — Kelola semua booking --}}
<div>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Kelola Booking</h1>
        <p class="text-gray-500 mt-1">Kelola reservasi masuk, konfirmasi atau tolak booking.</p>
    </div>

    {{-- Flash Messages --}}
    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg">{{ session('message') }}</div>
    @endif
    @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg">{{ session('error') }}</div>
    @endif

    {{-- Filters --}}
    <div class="bg-white rounded-xl border border-gray-200 p-4 mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Cari</label>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Nama, email, atau kode..."
                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select wire:model.live="filterStatus" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                    <option value="">Semua Status</option>
                    <option value="pending">Pending</option>
                    <option value="confirmed">Dikonfirmasi</option>
                    <option value="rejected">Ditolak</option>
                    <option value="cancelled">Dibatalkan</option>
                    <option value="completed">Selesai</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Villa</label>
                <select wire:model.live="filterVilla" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                    <option value="">Semua Villa</option>
                    @foreach ($villas as $villa)
                        <option value="{{ $villa->id }}">{{ $villa->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    {{-- Booking Table --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tamu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Villa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Check-in</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Check-out</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($bookings as $booking)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-mono text-gray-900">{{ $booking->booking_code }}</td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $booking->guest_name }}</div>
                                <div class="text-xs text-gray-500">{{ $booking->guest_phone }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $booking->villa->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $booking->check_in->format('d M Y') }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $booking->check_out->format('d M Y') }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $booking->formatted_total_price }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $booking->status_badge }}">
                                    {{ $booking->status_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <button wire:click="viewBooking({{ $booking->id }})" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Detail</button>
                                    @if ($booking->status === 'pending')
                                        <button wire:click="confirmBooking({{ $booking->id }})" class="text-green-600 hover:text-green-800 text-sm font-medium">Terima</button>
                                        <button wire:click="rejectBooking({{ $booking->id }})" class="text-red-600 hover:text-red-800 text-sm font-medium">Tolak</button>
                                    @endif
                                    @if ($booking->status === 'confirmed')
                                        <button wire:click="completeBooking({{ $booking->id }})" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Selesai</button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                <p>Tidak ada booking ditemukan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $bookings->links() }}
        </div>
    </div>

    {{-- Modal Detail Booking --}}
    @if ($selectedBooking)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" wire:click.self="closeDetail">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg mx-4 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold text-gray-900">Detail Booking</h2>
                    <button wire:click="closeDetail" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between"><span class="text-gray-500">Kode</span><span class="font-mono font-medium">{{ $selectedBooking['booking_code'] }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-500">Tamu</span><span class="font-medium">{{ $selectedBooking['guest_name'] }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-500">Email</span><span>{{ $selectedBooking['guest_email'] }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-500">Telepon</span><span>{{ $selectedBooking['guest_phone'] }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-500">Villa</span><span>{{ $selectedBooking['villa']['name'] ?? '-' }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-500">Check-in</span><span>{{ \Carbon\Carbon::parse($selectedBooking['check_in'])->format('d M Y') }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-500">Check-out</span><span>{{ \Carbon\Carbon::parse($selectedBooking['check_out'])->format('d M Y') }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-500">Jumlah Tamu</span><span>{{ $selectedBooking['num_guests'] }} orang</span></div>
                    <div class="flex justify-between"><span class="text-gray-500">Total</span><span class="font-bold text-emerald-600">Rp {{ number_format($selectedBooking['total_price'], 0, ',', '.') }}</span></div>
                    @if ($selectedBooking['notes'])
                        <div class="pt-2 border-t"><span class="text-gray-500 block mb-1">Catatan Tamu:</span><p class="text-gray-700">{{ $selectedBooking['notes'] }}</p></div>
                    @endif
                </div>

                @if ($selectedBooking['status'] === 'pending')
                    <div class="mt-4 pt-4 border-t">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Admin</label>
                        <textarea wire:model="adminNotes" class="w-full rounded-lg border-gray-300 shadow-sm text-sm" rows="2" placeholder="Opsional..."></textarea>
                        <div class="flex gap-2 mt-3">
                            <button wire:click="confirmBooking({{ $selectedBooking['id'] }})" class="flex-1 px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-lg hover:bg-emerald-700">Terima</button>
                            <button wire:click="rejectBooking({{ $selectedBooking['id'] }})" class="flex-1 px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700">Tolak</button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
