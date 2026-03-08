<x-layouts.guest :title="'Lacak Reservasi - Kawi Resort'">

<div class="w-full min-h-screen flex flex-col items-center pt-32 pb-20 px-4 relative overflow-hidden">
<!-- Background Image with Overlay -->
<div class="absolute inset-0 z-0">
<div class="absolute inset-0 bg-gradient-to-b from-background-light/40 via-background-light/80 to-background-light dark:from-background-dark/40 dark:via-background-dark/80 dark:to-background-dark z-10"></div>
<img alt="Luxury Bali Resort Infinity Pool" class="w-full h-full object-cover grayscale-[20%] opacity-40" data-alt="Luxury Bali Resort Infinity Pool at sunset" src="{{ asset('assets/track-reservation-bg.jpg') }}"/>
</div>
<!-- Content Container -->
<div class="relative z-20 max-w-2xl w-full text-center space-y-8">
<div class="space-y-4">
<span class="inline-block py-1 px-3 rounded-full bg-primary/10 text-primary text-xs font-bold uppercase tracking-widest">Guest Services</span>
<h2 class="text-4xl md:text-5xl font-black text-slate-900 dark:text-white tracking-tight">Lacak Reservasi</h2>
<p class="text-slate-600 dark:text-slate-400 text-lg leading-relaxed max-w-lg mx-auto">
                    Masukkan kode booking yang Anda terima untuk melihat detail dan status reservasi Anda di Kawi Resort.
                </p>
</div>

<livewire:guest.booking-status />

<!-- Help Links -->
<div class="flex flex-wrap justify-center gap-6 text-sm font-medium text-slate-500 dark:text-slate-400">
<a class="flex items-center gap-2 hover:text-primary transition-colors" href="#">
<span class="material-symbols-outlined text-lg">help</span>
                    Bantuan Kode Booking
                </a>
<a class="flex items-center gap-2 hover:text-primary transition-colors" href="#">
<span class="material-symbols-outlined text-lg">mail</span>
                    Hubungi Customer Service
                </a>
</div>
</div>
    </div>

</x-layouts.guest>
