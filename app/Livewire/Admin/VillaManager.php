<?php

namespace App\Livewire\Admin;

use App\Models\Villa;
use App\Services\VillaService;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

/**
 * Livewire Component: VillaManager — CRUD villa.
 *
 * Menerapkan: method (Req e), array (Req f), simpan data (Req g).
 *
 * @package App\Livewire\Admin
 */
class VillaManager extends Component
{
    use WithFileUploads;

    /** Mode: 'list', 'create', 'edit' */
    public string $mode = 'list';

    /** ID villa yang sedang di-edit */
    public ?int $editingVillaId = null;

    /** Form fields */
    public string $name = '';
    public string $description = '';
    public string $location = '';
    public string $price_per_night = '';
    public string $max_guests = '';
    public string $bedrooms = '';
    public string $bathrooms = '';
    public string $amenitiesInput = '';
    public bool $is_active = true;
    public $newImage;
    public $galleryImages = [];
    public array $existingImages = [];

    /**
     * Membuka form untuk membuat villa baru.
     *
     * @return void
     */
    public function createVilla(): void
    {
        $this->resetForm();
        $this->mode = 'create';
    }

    /**
     * Membuka form edit untuk villa tertentu.
     *
     * @param int $villaId
     * @return void
     */
    public function editVilla(int $villaId): void
    {
        $villa = Villa::findOrFail($villaId);
        $this->editingVillaId = $villa->id;
        $this->name = $villa->name;
        $this->description = $villa->description;
        $this->location = $villa->location;
        $this->price_per_night = (string) $villa->price_per_night;
        $this->max_guests = (string) $villa->max_guests;
        $this->bedrooms = (string) $villa->bedrooms;
        $this->bathrooms = (string) $villa->bathrooms;
        $this->amenitiesInput = is_array($villa->amenities) ? implode(', ', $villa->amenities) : '';
        $this->is_active = $villa->is_active;
        $this->existingImages = is_array($villa->images) ? $villa->images : [];
        $this->mode = 'edit';
    }

    /**
     * Menyimpan villa baru atau update villa yang di-edit.
     * Menerapkan prosedur penyimpanan data (Req g).
     *
     * @return void
     */
    public function saveVilla(): void
    {
        // Validasi input
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'price_per_night' => 'required|numeric|min:0',
            'max_guests' => 'required|integer|min:1',
            'bedrooms' => 'required|integer|min:1',
            'bathrooms' => 'required|integer|min:1',
            'newImage' => 'nullable|image|max:2048',
            'galleryImages.*' => 'nullable|image|max:2048',
        ]);

        // Parse amenities dari string ke array (Req f: penggunaan array)
        $amenities = array_map('trim', explode(',', $this->amenitiesInput));
        $amenities = array_filter($amenities);

        $data = [
            'name' => $this->name,
            'slug' => Str::slug($this->name),
            'description' => $this->description,
            'location' => $this->location,
            'price_per_night' => (float) $this->price_per_night,
            'max_guests' => (int) $this->max_guests,
            'bedrooms' => (int) $this->bedrooms,
            'bathrooms' => (int) $this->bathrooms,
            'amenities' => array_values($amenities),
            'is_active' => $this->is_active,
        ];

        // Manajemen Gambar (Primary di images[0], Gallery di array sisanya)
        $currentImages = $this->existingImages;

        // Upload Primary Image
        if ($this->newImage) {
            $primaryPath = $this->newImage->store('villas', 'public');
            if (empty($currentImages)) {
                $currentImages[0] = $primaryPath;
            } else {
                $currentImages[0] = $primaryPath; // Ganti gambar pertama
            }
        }

        // Upload Gallery Images
        if (!empty($this->galleryImages)) {
            // Jika villa baru dan belum ada primary image, set elemen pertama dengan null agar struktur konsisten
            if (empty($currentImages)) {
                $currentImages[0] = null; 
            }
            foreach ($this->galleryImages as $image) {
                $currentImages[] = $image->store('villas', 'public');
            }
        }

        // Simpan hanya jika ada perubahan pada gambar
        if (!empty($currentImages)) {
            // Filter nilai null jika ada dari inisialisasi awal
            $data['images'] = array_values(array_filter($currentImages, function($img) {
                return $img !== null;
            }));
        }

        $service = new VillaService();

        // Percabangan if-else (Req d)
        if ($this->mode === 'edit' && $this->editingVillaId !== null) {
            $service->update($this->editingVillaId, $data);
            session()->flash('message', 'Villa berhasil diperbarui.');
        } else {
            $service->create($data);
            session()->flash('message', 'Villa berhasil ditambahkan.');
        }

        $this->resetForm();
        $this->mode = 'list';
    }

    /**
     * Menghapus gambar dari galeri yang sudah ada.
     *
     * @param int $index
     * @return void
     */
    public function removeImage(int $index): void
    {
        if (isset($this->existingImages[$index])) {
            unset($this->existingImages[$index]);
            // Re-index array tapi pastikan index 0 tetap null jika primary dihapus
            if ($index === 0) {
                $this->existingImages[0] = null;
            }
            $this->existingImages = array_values($this->existingImages);
        }
    }

    /**
     * Menghapus villa berdasarkan ID.
     *
     * @param int $villaId
     * @return void
     */
    public function deleteVilla(int $villaId): void
    {
        $villa = Villa::find($villaId);
        // Hapus file fisik gambar jika perlu di sini

        $service = new VillaService();
        $service->delete($villaId);
        session()->flash('message', 'Villa berhasil dihapus.');
    }

    /**
     * Kembali ke mode list.
     *
     * @return void
     */
    public function cancel(): void
    {
        $this->resetForm();
        $this->mode = 'list';
    }

    /**
     * Reset semua field form.
     *
     * @return void
     */
    private function resetForm(): void
    {
        $this->editingVillaId = null;
        $this->name = '';
        $this->description = '';
        $this->location = '';
        $this->price_per_night = '';
        $this->max_guests = '';
        $this->bedrooms = '';
        $this->bathrooms = '';
        $this->amenitiesInput = '';
        $this->is_active = true;
        $this->newImage = null;
        $this->galleryImages = [];
        $this->existingImages = [];
    }

    /**
     * Render komponen villa manager.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        $villas = Villa::latest()->get();
        return view('livewire.admin.villa-manager', ['villas' => $villas])
            ->layout('components.layouts.admin', ['title' => 'Kelola Villa']);
    }
}
