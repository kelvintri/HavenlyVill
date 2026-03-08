<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Temukan dan booking villa impian Anda. Villa premium dengan fasilitas lengkap di lokasi terbaik Bali.">
    <title>{{ $title ?? 'Kawi Resort' }} — Sebuah Tempat Perlindungan Spiritual</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@100..700,0..1&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        .glass-effect {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
        }
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-slate-100 font-display antialiased">
    <!-- Header / Navigation -->
    <header class="fixed top-0 w-full z-50 backdrop-blur-md border-b border-primary/10 bg-primary">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}">
                    <img alt="Kawi Resort Logo" class="h-16 py-1" src="{{ asset('assets/logo-kawi2.png') }}" onerror="this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\'></svg>'"/>
                </a>
            </div>
            
            <!-- Nav Links -->
            <nav class="hidden md:flex items-center gap-10">
                <a class="text-sm font-medium transition-colors text-white hover:text-white/80" href="{{ route('home') }}#about">About</a>
                <a class="text-sm font-medium transition-colors text-white hover:text-white/80" href="{{ route('home') }}#accommodations">Accommodations</a>
                <a class="text-sm font-medium transition-colors text-white hover:text-white/80" href="{{ route('home') }}#experiences">Experiences</a>
                <a class="text-sm font-medium transition-colors text-white hover:text-white/80" href="{{ route('home') }}#offers">Offers</a>
                <a class="text-sm font-medium transition-colors text-white hover:text-white/80" href="{{ route('booking.status') }}">Cek Booking</a>
            </nav>
            
            <div class="flex items-center gap-4">
                @auth
                    <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 rounded-lg text-sm font-medium transition-all text-white bg-black/20 hover:bg-black/40">
                        Admin Panel
                    </a>
                @endauth
                <a href="{{ route('home') }}#accommodations" class="px-6 py-2.5 rounded-lg text-sm font-bold hover:bg-white/90 transition-all shadow-lg shadow-black/10 bg-white text-primary">
                    Book Now
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="min-h-screen">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-background-light dark:bg-background-dark border-t border-primary/10 pt-24 pb-12">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid md:grid-cols-4 gap-12 mb-20">
                <div class="col-span-2 space-y-6">
                    <div class="flex items-center gap-3">
                        <img alt="Logo" class="h-10" src="{{ asset('assets/logo-kawi2.png') }}" onerror="this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\'></svg>'"/>
                    </div>
                    <p class="text-slate-500 dark:text-slate-400 max-w-sm">Tampaksiring, Gianyar, Bali. A sanctuary where the soul meets nature.</p>
                    <div class="flex gap-4">
                        <a class="size-10 rounded-full bg-primary/5 dark:bg-white/5 flex items-center justify-center hover:bg-primary hover:text-white transition-all text-primary dark:text-white" href="#">
                            <span class="material-symbols-outlined">public</span>
                        </a>
                        <a class="size-10 rounded-full bg-primary/5 dark:bg-white/5 flex items-center justify-center hover:bg-primary hover:text-white transition-all text-primary dark:text-white" href="#">
                            <span class="material-symbols-outlined">share</span>
                        </a>
                        <a class="size-10 rounded-full bg-primary/5 dark:bg-white/5 flex items-center justify-center hover:bg-primary hover:text-white transition-all text-primary dark:text-white" href="#">
                            <span class="material-symbols-outlined">alternate_email</span>
                        </a>
                    </div>
                </div>
                <div>
                    <h4 class="font-bold mb-6 text-slate-900 dark:text-white">Quick Links</h4>
                    <ul class="space-y-4 text-slate-500 dark:text-slate-400">
                        <li><a class="hover:text-primary transition-colors" href="#">Privacy Policy</a></li>
                        <li><a class="hover:text-primary transition-colors" href="#">Terms of Service</a></li>
                        <li><a class="hover:text-primary transition-colors" href="#">Sustainability</a></li>
                        <li><a class="hover:text-primary transition-colors" href="#">Careers</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-6 text-slate-900 dark:text-white">Contact Us</h4>
                    <ul class="space-y-4 text-slate-500 dark:text-slate-400">
                        <li class="flex items-center gap-2"><span class="material-symbols-outlined text-sm">call</span> +62 361 123 4567</li>
                        <li class="flex items-center gap-2"><span class="material-symbols-outlined text-sm">mail</span> info@kawiresort.com</li>
                        <li class="flex items-start gap-2"><span class="material-symbols-outlined text-sm">location_on</span> Jl. Tirta Empul, Tampaksiring, <br/>Gianyar, Bali 80552</li>
                    </ul>
                </div>
            </div>
            
            <div class="w-full h-64 rounded-2xl overflow-hidden mb-12 shadow-inner border border-primary/10">
                <img alt="Location Map" class="w-full h-full object-cover grayscale opacity-50" data-alt="Stylized map showing resort location in Tampaksiring" src="https://lh3.googleusercontent.com/aida-public/AB6AXuB0qfCIGh3zSGGUy02bZT0R5oWJJ5mGHft8rWChHluSmxJHJ1bhOiN59J2K0Ab_9RM4dm_Ecnyix0q5PHQbvxtqR6YCIRt3weXBN_tzp3TUWgBgD3F-iyugF6i3vHDdt8qbPCknvfHOr25qDU32_rVp6grkVApsaSZ8RUoh-hCEBnYqWqRsL2m8xpVVxvCjSiUIqJsP6GpQRt2NDagVy4ERYN0YW3rZRQyJJSF7V34dnfBvrz-xuX0_toj7-7w9aESVDR5Rx604HDM"/>
            </div>
            
            <div class="border-t border-primary/5 pt-12 text-center text-slate-400 text-sm">
                <p>&copy; {{ date('Y') }} Kawi Resort Bali. All rights reserved.</p>
            </div>
        </div>
    </footer>
    @livewireScripts
</body>
</html>
