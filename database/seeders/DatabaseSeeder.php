<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Villa;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * DatabaseSeeder — mengisi database dengan data awal.
 *
 * Membuat 1 admin dan 3 villa percobaan.
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Menjalankan seeder database.
     * Menggunakan array dan pengulangan (Req d, f).
     *
     * @return void
     */
    public function run(): void
    {
        // Buat akun admin default
        User::create([
            'name' => 'Admin Villa',
            'email' => 'admin@villa.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Data 3 villa percobaan menggunakan array of arrays (Req f)
        $villasData = [
            [
                'name' => 'Villa Tepi Pantai',
                'slug' => 'villa-tepi-pantai',
                'description' => 'Villa mewah dengan pemandangan langsung ke pantai. Dilengkapi private pool infinity yang menghadap lautan, taman tropis, dan akses langsung ke pantai pasir putih. Cocok untuk liburan romantis atau keluarga kecil.',
                'location' => 'Seminyak, Bali',
                'price_per_night' => 2500000,
                'max_guests' => 6,
                'bedrooms' => 3,
                'bathrooms' => 2,
                'amenities' => [
                    'Private Pool',
                    'Beachfront',
                    'WiFi',
                    'AC',
                    'Kitchen',
                    'BBQ Area',
                    'Parking',
                    'Garden',
                ],
                'images' => [],
                'is_active' => true,
            ],
            [
                'name' => 'Villa Puncak Hijau',
                'slug' => 'villa-puncak-hijau',
                'description' => 'Villa di dataran tinggi dengan udara sejuk dan pemandangan pegunungan yang menakjubkan. Arsitektur modern minimalis dengan sentuhan kayu alami. Ideal untuk retreat keluarga atau gathering kecil.',
                'location' => 'Ubud, Bali',
                'price_per_night' => 1800000,
                'max_guests' => 8,
                'bedrooms' => 4,
                'bathrooms' => 3,
                'amenities' => [
                    'Mountain View',
                    'Private Pool',
                    'WiFi',
                    'AC',
                    'Kitchen',
                    'Garden',
                    'Parking',
                    'Fireplace',
                ],
                'images' => [],
                'is_active' => true,
            ],
            [
                'name' => 'Villa Sawah Tenang',
                'slug' => 'villa-sawah-tenang',
                'description' => 'Villa tradisional Bali yang dikelilingi persawahan hijau. Suasana damai dan tenang dengan arsitektur joglo Jawa modern. Sempurna untuk melarikan diri dari hiruk-pikuk kota.',
                'location' => 'Tabanan, Bali',
                'price_per_night' => 1500000,
                'max_guests' => 4,
                'bedrooms' => 2,
                'bathrooms' => 2,
                'amenities' => [
                    'Rice Field View',
                    'Private Pool',
                    'WiFi',
                    'AC',
                    'Kitchen',
                    'Yoga Deck',
                    'Parking',
                ],
                'images' => [],
                'is_active' => true,
            ],
        ];

        // Pengulangan untuk membuat setiap villa (Req d: loop foreach)
        foreach ($villasData as $villaData) {
            Villa::create($villaData);
        }
    }
}
