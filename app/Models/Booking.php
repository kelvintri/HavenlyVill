<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model Booking — representasi reservasi tamu untuk sebuah villa.
 *
 * @property int      $id
 * @property string   $booking_code
 * @property int      $villa_id
 * @property string   $guest_name
 * @property string   $guest_email
 * @property string   $guest_phone
 * @property string   $check_in
 * @property string   $check_out
 * @property int      $num_guests
 * @property float    $total_price
 * @property string   $status
 */
class Booking extends Model
{
    use HasFactory;

    /**
     * Atribut yang boleh diisi secara massal.
     *
     * @var list<string>
     */
    protected $fillable = [
        'booking_code',
        'villa_id',
        'guest_name',
        'guest_email',
        'guest_phone',
        'guest_id_number',
        'check_in',
        'check_out',
        'num_guests',
        'total_price',
        'status',
        'notes',
        'admin_notes',
    ];

    /**
     * Casting tipe data kolom.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'check_in' => 'date',
            'check_out' => 'date',
            'total_price' => 'decimal:2',
        ];
    }

    /**
     * Relasi: Booking milik satu Villa.
     *
     * @return BelongsTo
     */
    public function villa(): BelongsTo
    {
        return $this->belongsTo(Villa::class);
    }

    /**
     * Menghitung jumlah malam menginap.
     *
     * @return int
     */
    public function getNightsAttribute(): int
    {
        return $this->check_in->diffInDays($this->check_out);
    }

    /**
     * Mendapatkan total harga yang diformat ke Rupiah.
     *
     * @return string
     */
    public function getFormattedTotalPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->total_price, 0, ',', '.');
    }

    /**
     * Mendapatkan label warna badge berdasarkan status.
     * Menggunakan percabangan match/if-else (Req d).
     *
     * @return string
     */
    public function getStatusBadgeAttribute(): string
    {
        // Percabangan (Req d: control structure)
        return match ($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'confirmed' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800',
            'cancelled' => 'bg-gray-100 text-gray-800',
            'completed' => 'bg-blue-100 text-blue-800',
            default => 'bg-gray-100 text-gray-500',
        };
    }

    /**
     * Mendapatkan label status dalam Bahasa Indonesia.
     *
     * @return string
     */
    public function getStatusLabelAttribute(): string
    {
        $labels = [
            'pending' => 'Menunggu',
            'confirmed' => 'Dikonfirmasi',
            'rejected' => 'Ditolak',
            'cancelled' => 'Dibatalkan',
            'completed' => 'Selesai',
        ];

        return $labels[$this->status] ?? 'Tidak Diketahui';
    }
}
