<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model Villa — representasi unit villa yang bisa di-booking.
 *
 * Menggunakan JSON casting untuk amenities dan images (Req f: array).
 * Memiliki relasi one-to-many ke Booking dan BlockedDate.
 *
 * @property int      $id
 * @property string   $name
 * @property string   $slug
 * @property string   $description
 * @property string   $location
 * @property float    $price_per_night
 * @property int      $max_guests
 * @property int      $bedrooms
 * @property int      $bathrooms
 * @property array    $amenities
 * @property array    $images
 * @property bool     $is_active
 */
class Villa extends Model
{
    use HasFactory;

    /**
     * Atribut yang boleh diisi secara massal.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'location',
        'price_per_night',
        'max_guests',
        'bedrooms',
        'bathrooms',
        'amenities',
        'images',
        'is_active',
    ];

    /**
     * Casting: mengubah tipe data kolom secara otomatis.
     * amenities & images di-cast ke array (Req f).
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'amenities' => 'array',
            'images' => 'array',
            'price_per_night' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Relasi: Villa memiliki banyak Booking.
     *
     * @return HasMany
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Relasi: Villa memiliki banyak BlockedDate.
     *
     * @return HasMany
     */
    public function blockedDates(): HasMany
    {
        return $this->hasMany(BlockedDate::class);
    }

    /**
     * Mendapatkan harga yang sudah diformat ke Rupiah.
     *
     * @return string
     */
    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price_per_night, 0, ',', '.');
    }
}
