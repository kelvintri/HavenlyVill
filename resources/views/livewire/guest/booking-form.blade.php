{{-- Booking Form — Formulir Reservasi Tamu --}}
<div>
    @if ($bookingSuccess)
        {{-- Success State --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-lg p-6 text-center">
            <div class="flex items-center justify-center w-16 h-16 mx-auto bg-green-100 rounded-full mb-4">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Booking Berhasil!</h3>
            <p class="text-gray-500 mb-4">{{ $bookingMessage }}</p>
            <div class="bg-gray-50 rounded-xl p-4 mb-4">
                <p class="text-sm text-gray-500 mb-1">Kode Booking Anda</p>
                <p class="text-2xl font-mono font-bold text-emerald-600">{{ $bookingCode }}</p>
                <p class="text-xs text-gray-400 mt-1">Simpan kode ini untuk mengecek status booking.</p>
            </div>
            <a href="{{ route('booking.status') }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-lg hover:bg-emerald-700 transition">
                Cek Status Booking
            </a>
        </div>
    @else
        {{-- Booking Form --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-1">Reservasi</h3>
            <p class="text-sm text-gray-500 mb-4">{{ $villa->formatted_price }}/malam</p>

            @error('booking')
                <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg">{{ $message }}</div>
            @enderror

            <form wire:submit="submitBooking" class="space-y-4">
                {{-- Tanggal Check-in --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Check-in</label>
                    <input type="date" wire:model.live="check_in" min="{{ date('Y-m-d') }}"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                    @error('check_in') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                {{-- Tanggal Check-out --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Check-out</label>
                    <input type="date" wire:model.live="check_out" min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                    @error('check_out') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                {{-- Availability Status --}}
                @if ($isAvailable !== null)
                    @if ($isAvailable)
                        <div class="p-3 bg-green-50 border border-green-200 rounded-lg">
                            <p class="text-sm text-green-700 font-medium">✅ Villa tersedia!</p>
                            @if ($estimatedPrice)
                                <p class="text-sm text-green-600 mt-1">Estimasi total: <strong>{{ $estimatedPrice }}</strong></p>
                            @endif
                        </div>
                    @else
                        <div class="p-3 bg-red-50 border border-red-200 rounded-lg">
                            <p class="text-sm text-red-700 font-medium">❌ Villa tidak tersedia pada tanggal tersebut.</p>
                        </div>
                    @endif
                @endif

                {{-- Jumlah Tamu --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Tamu</label>
                    <select wire:model="num_guests" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                        @for ($i = 1; $i <= $villa->max_guests; $i++)
                            <option value="{{ $i }}">{{ $i }} orang</option>
                        @endfor
                    </select>
                    @error('num_guests') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <hr class="border-gray-200">

                {{-- Nama Tamu --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" wire:model="guest_name" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm" placeholder="Nama lengkap Anda">
                    @error('guest_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" wire:model="guest_email" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm" placeholder="email@contoh.com">
                    @error('guest_email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                {{-- Telepon --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">No. Telepon / WhatsApp</label>
                    <input type="tel" wire:model="guest_phone" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm" placeholder="08xxxxxxxxxx">
                    @error('guest_phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                {{-- Catatan --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan (opsional)</label>
                    <textarea wire:model="notes" rows="2" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm" placeholder="Permintaan khusus..."></textarea>
                </div>

                {{-- Submit --}}
                <button type="submit"
                        @if($isAvailable !== true) disabled @endif
                        class="w-full py-3 bg-emerald-600 text-white font-semibold rounded-xl hover:bg-emerald-700 transition disabled:opacity-50 disabled:cursor-not-allowed">
                    Ajukan Booking
                </button>
                <p class="text-xs text-gray-400 text-center">Booking akan dikonfirmasi oleh admin kami.</p>
            </form>
        </div>
    @endif
</div>
