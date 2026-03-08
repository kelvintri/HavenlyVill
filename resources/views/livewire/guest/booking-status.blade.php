<div>
<!-- Search Form -->
<div class="bg-white dark:bg-slate-900/50 p-2 md:p-3 rounded-2xl shadow-2xl border border-primary/5 backdrop-blur-sm">
<form wire:submit="searchBooking" class="flex flex-col md:flex-row gap-3">
<div class="relative flex-grow">
<span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">confirmation_number</span>
<input wire:model="bookingCode" class="w-full pl-12 pr-4 py-4 rounded-xl border-none bg-slate-100 dark:bg-slate-800 focus:ring-2 focus:ring-primary/20 text-slate-900 dark:text-white placeholder:text-slate-400 text-lg transition-all" placeholder="Masukkan kode booking Anda" type="text"/>
</div>
<button type="submit" class="bg-primary text-white px-10 py-4 rounded-xl font-bold text-lg hover:shadow-xl hover:shadow-primary/30 active:scale-95 transition-all">
                        Cari
                    </button>
</form>
@error('bookingCode')
    <p class="mt-2 text-sm text-red-500 text-left px-2">{{ $message }}</p>
@enderror
</div>

@if ($searched)
    @if ($booking)
<div class="mt-12 w-full animate-in fade-in slide-in-from-bottom-4 duration-700">
<div class="bg-white dark:bg-slate-900/60 rounded-2xl shadow-2xl border border-primary/10 overflow-hidden backdrop-blur-md">
<div class="p-6 border-b border-primary/5 bg-primary/5 flex items-center justify-between">
<h3 class="text-xl font-bold text-slate-900 dark:text-white">Detail Reservasi</h3>
<span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider
    @if($booking['status'] === 'confirmed') bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400
    @elseif($booking['status'] === 'pending') bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400
    @elseif($booking['status'] === 'rejected') bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400
    @elseif($booking['status'] === 'cancelled') bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400
    @elseif($booking['status'] === 'completed') bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400
    @else bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400 @endif">{{ $booking['status_label'] }}</span>
</div>
<div class="p-8 space-y-8 text-left">
<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
<div class="space-y-1">
<p class="text-xs font-bold text-primary uppercase tracking-widest">Nama Tamu</p>
<p class="text-lg font-semibold text-slate-800 dark:text-slate-200">{{ $booking['guest_name'] }}</p>
</div>
<div class="space-y-1">
<p class="text-xs font-bold text-primary uppercase tracking-widest">Tipe Villa</p>
<p class="text-lg font-semibold text-slate-800 dark:text-slate-200">{{ $booking['villa_name'] }}</p>
</div>
<div class="space-y-1">
<p class="text-xs font-bold text-primary uppercase tracking-widest">Tanggal Menginap</p>
<p class="text-lg font-semibold text-slate-800 dark:text-slate-200">{{ \Carbon\Carbon::parse($booking['check_in'])->isoFormat('D MMM YYYY') }} - {{ \Carbon\Carbon::parse($booking['check_out'])->isoFormat('D MMM YYYY') }}</p>
</div>
<div class="space-y-1">
<p class="text-xs font-bold text-primary uppercase tracking-widest">Total Harga</p>
<p class="text-2xl font-black text-primary">{{ $booking['formatted_total_price'] }}</p>
</div>
</div>
{{-- Admin Notes --}}
@if (!empty($booking['admin_notes']))
    <div class="pt-6 border-t border-slate-100 dark:border-slate-800">
        <p class="text-xs font-bold text-primary uppercase tracking-widest mb-3">Catatan Admin</p>
        <div class="bg-amber-50 dark:bg-amber-900/20 text-amber-800 dark:text-amber-200 p-4 rounded-xl border border-amber-200 dark:border-amber-900/50">
            <p class="text-sm font-medium">{{ $booking['admin_notes'] }}</p>
        </div>
    </div>
@endif
<div class="pt-6 border-t border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row gap-4">
<button class="flex-1 bg-primary text-white px-6 py-3 rounded-xl font-bold hover:shadow-lg transition-all flex items-center justify-center gap-2">
<span class="material-symbols-outlined text-xl">download</span>
                    Download Invoice
                </button>
<button class="flex-1 border-2 border-primary/20 text-primary px-6 py-3 rounded-xl font-bold hover:bg-primary/5 transition-all flex items-center justify-center gap-2">
<span class="material-symbols-outlined text-xl">support_agent</span>
                    Hubungi Resepsionis
                </button>
</div>
</div>
</div>
</div>

    @else
<div class="mt-12 w-full animate-in fade-in slide-in-from-bottom-4 duration-700">
<div class="bg-white dark:bg-slate-900/60 rounded-2xl shadow-2xl border border-primary/10 p-12 text-center backdrop-blur-md">
    <div class="w-20 h-20 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center mx-auto mb-5">
        <span class="material-symbols-outlined text-4xl text-slate-300 dark:text-slate-600">search_off</span>
    </div>
    <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-2">Booking Tidak Ditemukan</h3>
    <p class="text-slate-500 dark:text-slate-400 text-sm max-w-sm mx-auto">
        Kode booking <span class="font-mono font-bold text-slate-700 dark:text-slate-300">"{{ $bookingCode }}"</span> tidak ditemukan dalam sistem kami. Pastikan kode yang Anda masukkan sudah benar.
    </p>
    <p class="text-xs text-slate-400 dark:text-slate-500 mt-4">Butuh bantuan? Hubungi <strong>+62 361 123 4567</strong></p>
</div>
</div>
    @endif
@endif
</div>
