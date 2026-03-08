<x-layouts.guest :title="$villa->name . ' - Kawi Resort'">

    <main class="max-w-7xl mx-auto px-4 md:px-10 py-8 lg:mt-6">
        {{-- Breadcrumb & Title --}}
        <div class="mb-6 flex flex-col items-start pt-6">
            <div class="flex items-center gap-2 text-slate-500 text-sm mb-4">
                <a href="{{ route('home') }}" class="hover:text-primary transition font-medium">Beranda</a>
                <span class="material-symbols-outlined text-sm">chevron_right</span>
                <span class="text-slate-900 font-bold">{{ $villa->name }}</span>
            </div>
        </div>

        {{-- Hero Gallery --}}
        @php
            $hasImages = is_array($villa->images) && count(array_filter($villa->images)) > 0;
            $validImages = array_filter(is_array($villa->images) ? $villa->images : []);
            $indexedImages = array_values($validImages);

            $primary = isset($indexedImages[0]) ? asset('storage/' . $indexedImages[0]) : asset('assets/villa-detail-primary.jpg');
            $gallery2 = isset($indexedImages[1]) ? asset('storage/' . $indexedImages[1]) : asset('assets/villa-detail-gallery-1.jpg');
            $gallery3 = isset($indexedImages[2]) ? asset('storage/' . $indexedImages[2]) : asset('assets/villa-detail-gallery-2.jpg');
            
            $extraPhotosCount = count($indexedImages) > 3 ? count($indexedImages) - 3 : 0;
            
            $alpineImages = [];
            if (count($indexedImages) > 0) {
                foreach ($indexedImages as $img) {
                    $alpineImages[] = asset('storage/' . $img);
                }
            } else {
                $alpineImages = [
                    asset('assets/villa-detail-primary.jpg'),
                    asset('assets/villa-detail-gallery-1.jpg'),
                    asset('assets/villa-detail-gallery-2.jpg')
                ];
            }
        @endphp

        <div x-data="{ 
                galleryOpen: false, 
                currentIndex: 0, 
                images: {{ json_encode($alpineImages) }},
                openGallery(index) {
                    this.currentIndex = index;
                    this.galleryOpen = true;
                    document.body.style.overflow = 'hidden';
                },
                closeGallery() {
                    this.galleryOpen = false;
                    document.body.style.overflow = '';
                },
                nextImage() {
                    this.currentIndex = this.currentIndex === this.images.length - 1 ? 0 : this.currentIndex + 1;
                },
                prevImage() {
                    this.currentIndex = this.currentIndex === 0 ? this.images.length - 1 : this.currentIndex - 1;
                }
            }"
            @keydown.window.escape="closeGallery()"
            @keydown.window.right="nextImage()"
            @keydown.window.left="prevImage()"
        >
            <section class="grid grid-cols-1 md:grid-cols-3 gap-4 h-[400px] md:h-[500px] mb-12">
                <div @click="openGallery(0)" class="md:col-span-2 relative rounded-2xl overflow-hidden group cursor-pointer">
                    <div class="absolute inset-0 bg-cover bg-center transition-transform duration-700 group-hover:scale-105" style="background-image: url('{{ $primary }}')"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
                    <div class="absolute bottom-6 left-6 text-white">
                        <span class="px-4 py-1.5 bg-primary text-xs font-bold rounded-full uppercase tracking-widest shadow-xl">Kawi Resort</span>
                    </div>
                </div>
                <div class="hidden md:flex flex-col gap-4">
                    <div @click="openGallery(1)" class="h-1/2 relative rounded-2xl overflow-hidden group cursor-pointer">
                        <div class="absolute inset-0 bg-cover bg-center transition-transform duration-700 group-hover:scale-105" style="background-image: url('{{ $gallery2 }}')"></div>
                    </div>
                    <div @click="openGallery(2)" class="h-1/2 relative rounded-2xl overflow-hidden group cursor-pointer">
                        <div class="absolute inset-0 bg-cover bg-center transition-transform duration-700 group-hover:scale-105" style="background-image: url('{{ $gallery3 }}')"></div>
                        @if($extraPhotosCount > 0)
                        <div class="absolute inset-0 bg-black/50 flex items-center justify-center backdrop-blur-[1px] hover:bg-black/60 transition">
                            <span class="text-white font-bold text-2xl tracking-tight">+{{ $extraPhotosCount }} Photos</span>
                        </div>
                        @endif
                    </div>
                </div>
            </section>

            <!-- Fullscreen Gallery Modal -->
            <div x-show="galleryOpen" 
                 style="display: none; background-color: rgba(0,0,0,0.95);" 
                 class="fixed inset-0 z-[100] backdrop-blur-md flex flex-col items-center justify-center p-4"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="closeGallery()">
                
                <!-- Close Button -->
                <button @click.stop="closeGallery()" class="absolute top-6 right-6 text-white/70 hover:text-white bg-black/50 hover:bg-black/70 rounded-full p-2 transition z-50">
                    <span class="material-symbols-outlined text-3xl">close</span>
                </button>
                
                <!-- Image Counter -->
                <div class="absolute top-6 left-6 text-white/90 bg-black/50 px-4 py-2 rounded-full font-medium text-sm z-50" @click.stop>
                    <span x-text="currentIndex + 1"></span> / <span x-text="images.length"></span>
                </div>

                <!-- Navigation Buttons -->
                <button @click.stop="prevImage()" class="absolute left-4 md:left-10 top-1/2 -translate-y-1/2 text-white/70 hover:text-white bg-black/50 hover:bg-black/70 rounded-full p-3 md:p-4 transition z-50">
                    <span class="material-symbols-outlined text-3xl">chevron_left</span>
                </button>
                
                <button @click.stop="nextImage()" class="absolute right-4 md:right-10 top-1/2 -translate-y-1/2 text-white/70 hover:text-white bg-black/50 hover:bg-black/70 rounded-full p-3 md:p-4 transition z-50">
                    <span class="material-symbols-outlined text-3xl">chevron_right</span>
                </button>

                <!-- Main Image -->
                <div class="w-full max-w-5xl px-4 md:px-16 flex items-center justify-center h-full max-h-[80vh] py-10 relative mt-10">
                    <img :src="images[currentIndex]" 
                         @click.stop
                         class="max-w-full max-h-full object-contain rounded-lg shadow-2xl" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         :key="currentIndex"
                         alt="Gallery Image">
                </div>
                
                <!-- Thumbnail Strip -->
                <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex items-center gap-2 max-w-[90vw] overflow-x-auto no-scrollbar py-2 px-4 shadow-xl" @click.stop>
                    <template x-for="(img, index) in images" :key="index">
                        <button @click.stop="currentIndex = index" 
                                class="relative h-16 w-24 md:h-20 md:w-32 flex-shrink-0 rounded-md overflow-hidden transition-all duration-300 border-2"
                                :class="currentIndex === index ? 'border-primary scale-110 opacity-100 z-10' : 'border-transparent opacity-50 hover:opacity-100 z-0'">
                            <img :src="img" class="absolute inset-0 w-full h-full object-cover">
                        </button>
                    </template>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            <!-- Left Content Area -->
            <div class="lg:col-span-2">
                <div class="mb-10">
                    <h1 class="text-4xl md:text-5xl font-extrabold text-slate-900 mb-4">{{ $villa->name }}</h1>
                    <div class="flex items-center gap-4 text-primary font-medium italic">
                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm">location_on</span> {{ $villa->location }}</span>
                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm">star</span> 4.9 (120 Reviews)</span>
                    </div>
                </div>

                <!-- At a Glance -->
                <section class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-12">
                    <div class="p-5 bg-white border border-primary/10 rounded-2xl flex flex-col items-center text-center shadow-sm">
                        <span class="material-symbols-outlined text-3xl text-primary mb-2">group</span>
                        <span class="text-xs uppercase tracking-widest text-slate-500 mb-1">Kapasitas</span>
                        <span class="font-bold text-slate-900">{{ $villa->max_guests }} Orang</span>
                    </div>
                    <div class="p-5 bg-white border border-primary/10 rounded-2xl flex flex-col items-center text-center shadow-sm">
                        <span class="material-symbols-outlined text-3xl text-primary mb-2">hotel</span>
                        <span class="text-xs uppercase tracking-widest text-slate-500 mb-1">Kamar</span>
                        <span class="font-bold text-slate-900">{{ $villa->bedrooms }} Tersedia</span>
                    </div>
                    <div class="p-5 bg-white border border-primary/10 rounded-2xl flex flex-col items-center text-center shadow-sm">
                        <span class="material-symbols-outlined text-3xl text-primary mb-2">shower</span>
                        <span class="text-xs uppercase tracking-widest text-slate-500 mb-1">Mandi</span>
                        <span class="font-bold text-slate-900">{{ $villa->bathrooms }} KM Dalam</span>
                    </div>
                    <div class="p-5 bg-white border border-primary/10 rounded-2xl flex flex-col items-center text-center shadow-sm">
                        <span class="material-symbols-outlined text-3xl text-primary mb-2">park</span>
                        <span class="text-xs uppercase tracking-widest text-slate-500 mb-1">View</span>
                        <span class="font-bold text-slate-900">Nature</span>
                    </div>
                </section>

                <!-- Description -->
                <section class="mb-14">
                    <h3 class="text-2xl font-bold mb-6 border-l-4 border-primary pl-4 text-slate-900">Deskripsi Villa</h3>
                    <p class="text-lg text-slate-600 leading-relaxed italic border border-slate-100 bg-slate-50 p-6 rounded-2xl">
                        "{{ $villa->description }}"
                    </p>
                </section>

                <!-- Luxury Amenities -->
                @if (is_array($villa->amenities) && count($villa->amenities) > 0)
                <section class="mb-14">
                    <h3 class="text-2xl font-bold mb-8 text-slate-900">Fasilitas Lengkap</h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-y-6 gap-x-4">
                        @foreach ($villa->amenities as $amenity)
                        <div class="flex items-center gap-3">
                            <div class="size-10 flex-shrink-0 rounded-xl bg-primary/10 flex items-center justify-center text-primary">
                                <span class="material-symbols-outlined text-[20px]">check</span>
                            </div>
                            <span class="font-medium text-slate-700">{{ trim($amenity) }}</span>
                        </div>
                        @endforeach
                    </div>
                </section>
                @endif

                <!-- Testimonial -->
                <section class="bg-primary/5 p-8 md:p-10 rounded-3xl border border-primary/10 relative overflow-hidden mt-6">
                    <span class="material-symbols-outlined absolute -top-4 -left-4 text-9xl text-primary/10 select-none">format_quote</span>
                    <div class="relative z-10">
                        <p class="text-xl md:text-2xl italic text-slate-700 mb-8 font-display leading-relaxed">
                            "Menemukan ketenangan sempurna. Desain villa yang menyatu dengan alam dan pelayanan yang luar biasa membuat pengalaman menginap kami tak terlupakan."
                        </p>
                        <div class="flex items-center gap-4">
                            <div class="size-14 rounded-full overflow-hidden bg-white flex items-center justify-center shadow-md">
                                <span class="material-symbols-outlined text-primary text-3xl">sentiment_satisfied</span>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-900 text-lg">Tamu Terverifikasi</h4>
                                <span class="text-sm text-primary font-medium tracking-wide">Stayed recently</span>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <!-- Booking Widget -->
            <aside class="lg:col-span-1">
                <div class="sticky top-28 bg-white p-6 md:p-8 rounded-3xl border border-primary/10 shadow-[0_20px_40px_-15px_rgba(122,31,52,0.1)]">
                    <div class="mb-6 pb-6 border-b border-slate-100 flex items-baseline justify-between">
                        <div>
                            <span class="text-3xl font-black text-primary">{{ $villa->formatted_price }}</span>
                            <span class="text-slate-500 font-medium text-sm"> / malam</span>
                        </div>
                    </div>
                    {{-- Embed Booking Form Livewire --}}
                    <div class="bg-slate-50 -mx-4 px-4 py-2 rounded-xl border border-slate-100 mb-4">
                        <livewire:guest.booking-form :villa="$villa" />
                    </div>
                </div>
            </aside>
        </div>
    </main>
</x-layouts.guest>
