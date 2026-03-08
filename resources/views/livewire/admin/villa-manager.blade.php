{{-- Villa Manager — CRUD Villa --}}
<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Kelola Villa</h1>
            <p class="text-gray-500 mt-1">Tambah, edit, atau hapus data villa.</p>
        </div>
        @if ($mode === 'list')
            <button wire:click="createVilla" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-lg hover:bg-emerald-700 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Villa
            </button>
        @endif
    </div>

    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg">{{ session('message') }}</div>
    @endif

    {{-- Form Create/Edit --}}
    @if ($mode === 'create' || $mode === 'edit')
        <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6" x-data="{ activeTab: 'info' }">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ $mode === 'create' ? 'Tambah Villa Baru' : 'Edit Villa' }}</h2>
            
            <div class="border-b border-gray-200 mb-6">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <button type="button" @click="activeTab = 'info'" 
                        :class="activeTab === 'info' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Informasi Dasar
                    </button>
                    <button type="button" @click="activeTab = 'gallery'" 
                        :class="activeTab === 'gallery' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Galeri Foto
                    </button>
                </nav>
            </div>

            <form wire:submit="saveVilla">
                <div x-show="activeTab === 'info'">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Villa</label>
                            <input type="text" wire:model="name" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" placeholder="Contoh: Villa Tepi Pantai">
                            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                            <textarea wire:model="description" rows="3" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" placeholder="Deskripsi lengkap villa..."></textarea>
                            @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi</label>
                            <input type="text" wire:model="location" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" placeholder="Contoh: Seminyak, Bali">
                            @error('location') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Harga per Malam (Rp)</label>
                            <input type="number" wire:model="price_per_night" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" placeholder="2500000">
                            @error('price_per_night') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Maks. Tamu</label>
                            <input type="number" wire:model="max_guests" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" min="1">
                            @error('max_guests') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kamar Tidur</label>
                            <input type="number" wire:model="bedrooms" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" min="1">
                            @error('bedrooms') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kamar Mandi</label>
                            <input type="number" wire:model="bathrooms" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" min="1">
                            @error('bathrooms') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Amenities (pisahkan dengan koma)</label>
                            <input type="text" wire:model="amenitiesInput" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" placeholder="WiFi, AC, Pool, Kitchen">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Foto Utama Villa</label>
                            <input type="file" wire:model="newImage" accept="image/*" class="w-full rounded-lg border border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-l-lg file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
                            @error('newImage') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            <div wire:loading wire:target="newImage" class="text-xs text-blue-500 mt-1">Mengunggah foto...</div>
                            @if ($newImage)
                                <div class="mt-2 text-sm text-green-600">Foto siap diunggah.</div>
                            @elseif($mode === 'edit' && !empty($existingImages) && isset($existingImages[0]) && $existingImages[0])
                                <div class="mt-2">
                                    <p class="text-xs text-gray-500 mb-1">Foto utama saat ini:</p>
                                    <img src="{{ asset('storage/' . $existingImages[0]) }}" class="h-20 w-32 object-cover rounded shadow">
                                </div>
                            @endif
                        </div>
                        <div class="md:col-span-2 flex items-center gap-2">
                            <input type="checkbox" wire:model="is_active" id="is_active" class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                            <label for="is_active" class="text-sm text-gray-700">Villa Aktif (tampil di landing page)</label>
                        </div>
                    </div>
                </div>

                <div x-show="activeTab === 'gallery'" style="display: none;">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tambahkan Foto Galeri (Bisa lebih dari 1)</label>
                        <input type="file" wire:model="galleryImages" multiple accept="image/*" class="w-full rounded-lg border border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-l-lg file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
                        @error('galleryImages.*') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        <div wire:loading wire:target="galleryImages" class="text-xs text-blue-500 mt-1">Mengunggah galeri...</div>
                        @if ($galleryImages)
                            <div class="mt-2 text-sm text-green-600">{{ count($galleryImages) }} foto siap ditambahkan ke galeri.</div>
                        @endif
                    </div>

                    @if(!empty($existingImages) && count(array_filter($existingImages, fn($k) => $k > 0, ARRAY_FILTER_USE_KEY)) > 0)
                    <div class="mt-6">
                        <h3 class="text-sm font-medium text-gray-700 mb-3">Foto Galeri Tersimpan</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @foreach($existingImages as $index => $image)
                                @if($index > 0 && $image)
                                <div class="relative group rounded-lg overflow-hidden border border-gray-200">
                                    <img src="{{ asset('storage/' . $image) }}" class="w-full h-24 object-cover">
                                    <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                        <button type="button" wire:click="removeImage({{ $index }})" class="p-1.5 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                <div class="flex gap-3 mt-6">
                    <button type="submit" class="px-6 py-2 bg-emerald-600 text-white text-sm font-medium rounded-lg hover:bg-emerald-700 transition">
                        {{ $mode === 'create' ? 'Simpan' : 'Perbarui' }}
                    </button>
                    <button type="button" wire:click="cancel" class="px-6 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition">Batal</button>
                </div>
            </form>
        </div>
    @endif

    {{-- Villa List --}}
    @if ($mode === 'list')
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($villas as $villa)
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-md transition">
                    <div class="h-40 bg-gradient-to-br from-emerald-400 to-teal-600 flex items-center justify-center overflow-hidden relative">
                        @if (is_array($villa->images) && count($villa->images) > 0)
                            @php $latestImage = $villa->images[array_key_last($villa->images)]; @endphp
                            <img src="{{ asset('storage/' . $latestImage) }}" alt="{{ $villa->name }}" class="w-full h-full object-cover">
                        @else
                            <svg class="w-16 h-16 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        @endif
                    </div>
                    <div class="p-4">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="font-semibold text-gray-900">{{ $villa->name }}</h3>
                            @if ($villa->is_active)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Aktif</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Nonaktif</span>
                            @endif
                        </div>
                        <p class="text-sm text-gray-500 mb-2">📍 {{ $villa->location }}</p>
                        <p class="text-lg font-bold text-emerald-600 mb-3">{{ $villa->formatted_price }}<span class="text-sm font-normal text-gray-400">/malam</span></p>
                        <div class="flex items-center gap-3 text-xs text-gray-500 mb-4">
                            <span>🛏️ {{ $villa->bedrooms }} Kamar</span>
                            <span>🚿 {{ $villa->bathrooms }} KM</span>
                            <span>👥 Max {{ $villa->max_guests }}</span>
                        </div>
                        <div class="flex gap-2">
                            <button wire:click="editVilla({{ $villa->id }})" class="flex-1 px-3 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition">Edit</button>
                            <button wire:click="deleteVilla({{ $villa->id }})" wire:confirm="Yakin ingin menghapus villa ini?" class="px-3 py-2 border border-red-300 text-red-600 text-sm font-medium rounded-lg hover:bg-red-50 transition">Hapus</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
