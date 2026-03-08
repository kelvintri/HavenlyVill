<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model BlockedDate — tanggal yang diblokir oleh admin.
 *
 * @property int    $id
 * @property int    $villa_id
 * @property string $date
 * @property string $reason
 */
class BlockedDate extends Model
{
    /**
     * Atribut yang boleh diisi secara massal.
     *
     * @var list<string>
     */
    protected $fillable = [
        'villa_id',
        'date',
        'reason',
    ];

    /**
     * Casting tipe data.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }

    /**
     * Relasi: BlockedDate milik satu Villa.
     *
     * @return BelongsTo
     */
    public function villa(): BelongsTo
    {
        return $this->belongsTo(Villa::class);
    }
}
