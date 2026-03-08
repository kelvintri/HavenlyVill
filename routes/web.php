<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — Rute aplikasi Villa Booking
|--------------------------------------------------------------------------
| Menerapkan requirement (i): penggunaan 2+ namespace/package
| Route ini menghubungkan URL ke controller dan Livewire component.
*/

// ========================================
// Guest Routes — Halaman Publik
// ========================================
Route::get('/', function () {
    return view('guest.home');
})->name('home');

Route::get('/villa/{slug}', function (string $slug) {
    $villa = \App\Models\Villa::where('slug', $slug)->where('is_active', true)->firstOrFail();
    return view('guest.villa-detail', compact('villa'));
})->name('villa.detail');

Route::get('/booking/status', function () {
    return view('guest.booking-status');
})->name('booking.status');

// ========================================
// Authentication Routes
// ========================================
Route::get('/login', function () {
    // Redirect jika sudah login
    if (auth()->check()) {
        return redirect()->route('admin.dashboard');
    }
    return view('auth.login');
})->name('login');

Route::post('/login', function (\Illuminate\Http\Request $request) {
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (auth()->attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->intended(route('admin.dashboard'));
    }

    return back()->withErrors([
        'email' => 'Email atau password salah.',
    ])->onlyInput('email');
})->name('login.post');

Route::post('/logout', function (\Illuminate\Http\Request $request) {
    auth()->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('home');
})->name('logout');

// ========================================
// Admin Routes — Panel Admin (dilindungi middleware)
// ========================================
Route::prefix('admin')
    ->middleware(['auth', 'admin'])
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        Route::get('/bookings', function () {
            return view('admin.bookings');
        })->name('bookings');

        Route::get('/villas', function () {
            return view('admin.villas');
        })->name('villas');

        Route::get('/calendar', function () {
            return view('admin.calendar');
        })->name('calendar');
    });
