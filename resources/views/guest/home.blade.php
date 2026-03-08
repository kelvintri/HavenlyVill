<x-layouts.guest :title="'A Spiritual Sanctuary to Escape'">
    <!-- Hero Section -->
    <section class="relative h-screen w-full overflow-hidden flex items-end justify-start">
        <div class="absolute inset-0 bg-black/40 z-10"></div>
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuCTaNEi_7Ka0H3dUrnAoC3ZukjBpPATUadj8L-7_SoZcAzuPwiIre4M77HZOhRqHZYH5Yhnb2Wm_xrVsRXYcfvssaAhTi7oo625PjEXFh3T7pvN39o8wWPUT8B5y3Gck0N11gF8hGnuRV9jaHP0-qx8WlYEiUFEslR2wZx8WkCLE0b-XsLpxOMn2KfjbqhzNhZ2VX2sXjcYQbl6s4YFUp-nKl2xUy7BJ5hgYYAio1ns7yWjrqhfsqzoOjskUFuUCSMEuQyaCx0mHes');"></div>
        <div class="relative z-20 px-4 max-w-4xl text-left ml-6 md:ml-20 mb-32">
            <h2 class="text-white text-lg md:text-xl font-medium tracking-[0.3em] uppercase mb-4">Kawi Resort</h2>
            <h1 class="text-white text-4xl md:text-5xl font-extrabold leading-tight tracking-tight mb-8">
                A SPIRITUAL SANCTUARY <br/> TO ESCAPE
            </h1>
            <div class="flex flex-col sm:flex-row gap-4 justify-start">
                <a href="#about" class="bg-primary text-white text-center px-8 py-4 rounded-lg text-base font-bold hover:scale-105 transition-transform inline-block">
                    Explore Our Story
                </a>
                <a href="#accommodations" class="bg-white/10 text-center backdrop-blur-md text-white border border-white/30 px-8 py-4 rounded-lg text-base font-bold hover:bg-white/20 transition-all inline-block">
                    View Villas
                </a>
            </div>
        </div>
        <div class="absolute bottom-10 left-1/2 -translate-x-1/2 z-20 animate-bounce">
            <span class="material-symbols-outlined text-white text-3xl">keyboard_double_arrow_down</span>
        </div>
    </section>

    <!-- About Section -->
    <section class="py-24 px-6 max-w-7xl mx-auto" id="about">
        <div class="grid md:grid-cols-2 gap-16 items-center">
            <div class="space-y-6">
                <span class="text-primary font-bold tracking-widest uppercase text-sm">Discover Serenity</span>
                <h2 class="text-4xl md:text-5xl font-extrabold text-slate-900 dark:text-slate-100">Luxury Escape in Tampaksiring</h2>
                <p class="text-lg text-slate-600 dark:text-slate-400 leading-relaxed">
                    Nestled in the heart of Bali's spiritual center, Kawi Resort is a boutique sanctuary designed for deep rejuvenation. Our villas are harmoniously integrated into the lush tropical landscape, offering breathtaking views of the sacred Pakerisan river valley.
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 pt-8">
                    <div class="flex flex-col gap-3">
                        <span class="material-symbols-outlined text-primary text-3xl">nature_people</span>
                        <h4 class="font-bold">Nature</h4>
                        <p class="text-sm text-slate-500">Ancient tropical forests surrounding.</p>
                    </div>
                    <div class="flex flex-col gap-3">
                        <span class="material-symbols-outlined text-primary text-3xl">self_improvement</span>
                        <h4 class="font-bold">Serenity</h4>
                        <p class="text-sm text-slate-500">Quiet sanctuary for the soul.</p>
                    </div>
                    <div class="flex flex-col gap-3">
                        <span class="material-symbols-outlined text-primary text-3xl">temple_hindu</span>
                        <h4 class="font-bold">Spirituality</h4>
                        <p class="text-sm text-slate-500">Energy of Tampaksiring.</p>
                    </div>
                </div>
            </div>
            <div class="relative h-[500px] rounded-2xl overflow-hidden shadow-2xl">
                <img alt="Resort Architecture" class="w-full h-full object-cover" data-alt="Traditional Balinese architecture with modern luxury finish" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDlIhZ5nglffspjQwzkrAZsEYYpOSmfGqvA-AejZ7xu2zeVvFgnbcyGUKCEHNEfC-NwXoYJnu71O_WAepOSHRneZDnh8_ol9X2Lk1QNDGyJkUG70rl1_Ingd48CIYrfoCHXHZqYfLv25PY8QSLYjVq5oIpie41r5D-3CR1pa557dBkVFBUHSc08Wuf2WhCb0cpz6HOJtDaZBaMvG-wvwnUpfBys0XQmxg0wZk1sAYuwRcZVjx7B6bHtr6lNzU4UsRmALh1S6o2mCg4"/>
            </div>
        </div>
    </section>

    <!-- Accommodations Section -->
    <section class="py-24 bg-primary/5 dark:bg-primary/10" id="accommodations">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-extrabold mb-4">Our Accommodations</h2>
                <p class="text-slate-600 dark:text-slate-400 max-w-2xl mx-auto">Choose from our selection of curated villas, each offering a unique perspective of the valley's majesty.</p>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                @php
                    $villas = \App\Models\Villa::where('is_active', true)->get();
                    $villaImages = [
                        'delux-valley-0.webp',
                        'suite-pool-access-1.webp',
                        'private-pool-0.webp'
                    ];
                @endphp
                
                @foreach ($villas as $index => $villa)
                    @php
                        // Gunakan gambar dummy lokal jika belum diset dari DB untuk menjaga estetik
                        if (is_array($villa->images) && count($villa->images) > 0) {
                            $imageAsset = 'storage/' . $villa->images[array_key_last($villa->images)];
                        } else {
                            $imageAsset = isset($villaImages[$index]) ? 'assets/' . $villaImages[$index] : 'assets/delux-valley-0.webp';
                        }
                    @endphp
                    <a href="{{ route('villa.detail', $villa->slug) }}" class="group bg-white dark:bg-background-dark rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all border border-primary/5 block">
                        <div class="h-64 overflow-hidden relative">
                            <img alt="{{ $villa->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" src="{{ asset($imageAsset) }}"/>
                            @if($index === 0)
                                <div class="absolute top-4 left-4 bg-primary text-white text-xs font-bold px-3 py-1 rounded-full">Top Rated</div>
                            @endif
                        </div>
                        <div class="p-8">
                            <h3 class="text-xl font-bold mb-2">{{ $villa->name }}</h3>
                            <p class="text-slate-500 dark:text-slate-400 text-sm mb-6 line-clamp-2">{{ $villa->description }}</p>
                            <div class="flex items-center justify-between">
                                <span class="text-primary font-bold">From {{ $villa->formatted_price }}/night</span>
                                <span class="text-sm font-bold flex items-center gap-1 text-slate-800 dark:text-slate-200 group-hover:text-primary transition-colors">
                                    Details <span class="material-symbols-outlined text-sm">arrow_forward</span>
                                </span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Experiences Section -->
    <section class="py-24 px-6 max-w-7xl mx-auto" id="experiences">
        <div class="grid md:grid-cols-2 gap-12">
            <div class="space-y-12">
                <div class="space-y-4">
                    <h2 class="text-4xl font-extrabold">Unforgettable Experiences</h2>
                    <p class="text-slate-600 dark:text-slate-400">Beyond luxury living, we offer soulful journeys that connect you with Balinese heritage and wellness.</p>
                </div>
                <div class="flex gap-6 group cursor-pointer">
                    <div class="size-20 rounded-xl bg-primary/10 flex items-center justify-center shrink-0 group-hover:bg-primary group-hover:text-white transition-colors">
                        <span class="material-symbols-outlined text-4xl text-primary group-hover:text-white transition-colors">spa</span>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold mb-2">Kinara Spa</h3>
                        <p class="text-slate-500">Ancient healing rituals and modern wellness therapies in a tranquil forest setting.</p>
                    </div>
                </div>
                <div class="flex gap-6 group cursor-pointer">
                    <div class="size-20 rounded-xl bg-primary/10 flex items-center justify-center shrink-0 group-hover:bg-primary group-hover:text-white transition-colors">
                        <span class="material-symbols-outlined text-4xl text-primary group-hover:text-white transition-colors">restaurant</span>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold mb-2">Collina Kawi Restaurant</h3>
                        <p class="text-slate-500">A culinary journey featuring organic ingredients harvested from our resort garden.</p>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <img alt="Spa Experience" class="rounded-2xl w-full h-[400px] object-cover mt-12 shadow-xl" src="{{ asset('assets/kinara-spa.jpg') }}" onerror="this.src='https://lh3.googleusercontent.com/aida-public/AB6AXuDNJhDDqRozQdwu5heNSb2PJM8ge9Bjihapthrqf07F_3piMdwTStANagHkM2MGuxCg2ASgCoJ7aRFDTABoy1qB69DPCMm2RoDMTNZjJddgBHyXLMSEIDDX_bflSMn3ZliIJG4s7AA0byJ3tWvT2XYJE2orJpYUvgb6oNZFGH-8idXva7b0ufpQKR8xEszZkGPW4Uq8EGOy0U9oKlmWGdSolJyBsargO9yoFGLKtC3DghwzWZrXbKpHVz_896D-wMaLIHSNuJ7is64'"/>
                <img alt="Dining Experience" class="rounded-2xl w-full h-[400px] object-cover shadow-xl" src="{{ asset('assets/collina-restaruant.jpg') }}" onerror="this.src='https://lh3.googleusercontent.com/aida-public/AB6AXuBXzS2xTNlkiljNgsL4GkJto2UbAetNeT5a8UDGPXDjzX5etAJPkqkr2HL4qliC-iGL_iR9XABC3UaKdLePlIZRyPkZpfSaAXpu52KJhorq58fHJYmEL0z7WXRPST1UWgsBUQRhtVGMGNgPxrM3PD3lHe1G32nvOPhsD5k04KSarbGsn8O8K58ihCSwEnwSw7KlFCT1n4vSqD9AGS7AdG4WBydZpm-YAYXynyyeSw-dwocZGVZV8E-Ppky5t5NkdfLFw1D4UxPRl-8'"/>
            </div>
        </div>
    </section>

    <!-- Exclusive Offers -->
    <section class="py-24 bg-primary text-white" id="offers">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-6">
                <div class="max-w-xl">
                    <h2 class="text-4xl font-extrabold mb-4">Exclusive Offers</h2>
                    <p class="opacity-80 text-lg">Plan your escape with our specially curated packages designed for memories that last a lifetime.</p>
                </div>
                <button class="border border-white/30 px-8 py-4 rounded-lg hover:bg-white/10 transition-all font-bold tracking-wide">View All Offers</button>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Offer 1 -->
                <div class="relative group overflow-hidden rounded-2xl cursor-pointer h-[400px] flex flex-col justify-end p-8 shadow-2xl">
                    <img alt="Spiritual Journey" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" src="{{ asset('assets/A Spiritual Journey Package.jpg') }}" />
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>
                    <div class="relative z-10">
                        <h3 class="text-2xl font-bold mb-4">Spiritual Journey</h3>
                        <p class="text-white/80 mb-8 leading-relaxed">Includes guided temple tours, meditation sessions, and daily healthy breakfast for two.</p>
                        <span class="text-lg font-bold flex items-center gap-2 group-hover:gap-4 transition-all text-white">Explore Package <span class="material-symbols-outlined">arrow_forward</span></span>
                    </div>
                </div>
                <!-- Offer 2 -->
                <div class="relative group overflow-hidden rounded-2xl cursor-pointer h-[400px] flex flex-col justify-end p-8 shadow-2xl">
                    <img alt="Honeymoon Bliss" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" src="{{ asset('assets/Honeymoon Bliss Package.jpg') }}" />
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>
                    <div class="relative z-10">
                        <h3 class="text-2xl font-bold mb-4">Honeymoon Bliss</h3>
                        <p class="text-white/80 mb-8 leading-relaxed">Romantic villa decoration, flower bath, candlelit dinner, and couple's massage.</p>
                        <span class="text-lg font-bold flex items-center gap-2 group-hover:gap-4 transition-all text-white">Explore Package <span class="material-symbols-outlined">arrow_forward</span></span>
                    </div>
                </div>
                <!-- Offer 3 -->
                <div class="relative group overflow-hidden rounded-2xl cursor-pointer h-[400px] flex flex-col justify-end p-8 shadow-2xl">
                    <img alt="Culinary Journey" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" src="{{ asset('assets/Culinary Journey Package.jpg') }}" />
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>
                    <div class="relative z-10">
                        <h3 class="text-2xl font-bold mb-4">Culinary Journey</h3>
                        <p class="text-white/80 mb-8 leading-relaxed">Traditional Balinese cooking class, market visit, and tasting menu at Collina Kawi.</p>
                        <span class="text-lg font-bold flex items-center gap-2 group-hover:gap-4 transition-all text-white">Explore Package <span class="material-symbols-outlined">arrow_forward</span></span>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.guest>
