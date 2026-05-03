<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Ruma - Solusi edukasi modern dengan teknologi voice dan gesture control untuk pengalaman belajar yang accessible dan interaktif">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ruma - Belajar Lebih Mudah, Tanpa Sentuhan</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <style>
        :root {
            --color-primary-yellow: #F8B803;
            --color-yellow-light: #FFF9E6;
            --color-yellow-dark: #E6A500;
            --color-green: #22C55E;
            --color-green-dark: #16A34A;
            --color-brown: #8B4513;
            --color-brown-dark: #654321;
            --color-brown-light: #A0522D;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            scroll-behavior: smooth;
        }
        
        .gradient-yellow {
            background: linear-gradient(135deg, #F8B803 0%, #FFD700 100%);
        }
        
        .gradient-brown {
            background: linear-gradient(135deg, #8B4513 0%, #654321 100%);
        }
        
        .hero-curve {
            position: absolute;
            bottom: 0;
            right: 0;
            width: 50%;
            height: 80%;
            background: var(--color-primary-yellow);
            border-radius: 100% 0 0 0;
            opacity: 0.3;
            z-index: 0;
        }
        
        .feature-card {
            transition: all 0.3s ease;
        }
        
        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        
        .book-shelf {
            background: linear-gradient(180deg, #654321 0%, #8B4513 100%);
        }
        
        .step-connector {
            position: absolute;
            left: 20px;
            top: 40px;
            bottom: -20px;
            width: 2px;
            background: #E5E7EB;
        }
        
        .step-item:last-child .step-connector {
            display: none;
        }
        
        .animate-fade-in {
            animation: fadeIn 0.6s ease-in;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-slide-in {
            animation: slideIn 0.8s ease-out;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }
        
        .btn-primary {
            background: var(--color-primary-yellow);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background: var(--color-yellow-dark);
            transform: scale(1.05);
            box-shadow: 0 10px 25px rgba(248, 184, 3, 0.4);
        }
        
        .btn-secondary {
            background: var(--color-green);
            transition: all 0.3s ease;
        }
        
        .btn-secondary:hover {
            background: var(--color-green-dark);
            transform: scale(1.05);
        }
        
        .nav-link {
            position: relative;
            transition: color 0.3s ease;
        }
        .nav-item {
    position: relative;
    padding: 6px 0;
    color: #374151;
    text-transform: uppercase;
    transition: all 0.3s ease;
}

.nav-item:hover {
    color: #F8B803;
}

.nav-item::after {
    content: '';
    position: absolute;
    left: 0;
    bottom: -6px;
    width: 0;
    height: 3px;
    background-color: #F8B803;
    border-radius: 4px;
    transition: width 0.3s ease;
}

.nav-item:hover::after,
.nav-item.active::after {
    width: 100%;
}

.nav-item.active {
    color: #F8B803;
}

        
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--color-primary-yellow);
            transition: width 0.3s ease;
        }
        
        .nav-link:hover::after {
            width: 100%;
        }
        
        .book-carousel {
            scroll-behavior: smooth;
            scrollbar-width: none;
        }
        
        .book-carousel::-webkit-scrollbar {
            display: none;
        }

        .mobile-menu {
            display: none;
        }

        .mobile-menu.open {
            display: block;
        }

        .rating-wrap {
            display: flex;
        }

        .rating-label {
            display: none;
        }

        .rating-stars {
            display: flex;
            flex-direction: row-reverse;
            gap: 0.25rem;
            font-size: 2rem;
            line-height: 1;
        }

        .rating-center {
            justify-content: center;
            padding: 0;
            margin: 0 0 0.5rem;
        }

        .rating-stars input {
            display: none;
        }

        .rating-stars label {
            cursor: pointer;
            color: #e5e7eb;
            transition: color 0.2s ease, transform 0.2s ease;
        }

        .rating-stars label:hover,
        .rating-stars label:hover ~ label {
            color: #f8b803;
            transform: scale(1.05);
        }

        .rating-stars input:checked ~ label {
            color: #f8b803;
        }

        .review-toast {
            position: fixed;
            right: 24px;
            top: 24px;
            background: #16a34a;
            color: #fff;
            padding: 0.9rem 1.1rem;
            border-radius: 12px;
            box-shadow: 0 10px 24px rgba(0,0,0,0.2);
            font-weight: 600;
            opacity: 0;
            pointer-events: none;
            transform: translateY(8px);
            transition: opacity 0.2s ease, transform 0.2s ease;
            z-index: 9999;
        }

        .review-toast.show {
            opacity: 1;
            transform: translateY(0);
        }
        
        @media (max-width: 768px) {
            .hero-curve {
                width: 70%;
                height: 60%;
            }
        }
    </style>
</head>
<body class="bg-white text-gray-900">
    <!-- Header Navigation -->
   <header class="fixed top-0 inset-x-0 z-50 bg-white/90 backdrop-blur-md border-b border-gray-100 transition-all">
    <nav class="container mx-auto px-6 h-20 flex items-center justify-between">

        <!-- Logo -->
        <a href="#beranda" class="flex items-center gap-3">
            <img src="{{ $appBranding->image_url }}" 
                 alt="{{ $appBranding->title }} Logo"
                 class="h-10 w-auto">
            <span class="font-extrabold text-xl tracking-wide text-gray-900">
                {{ $appBranding->title }}
            </span>
        </a>

        <!-- Navigation -->
        <ul class="hidden md:flex items-center gap-8 text-sm font-semibold tracking-wide">
            <li>
                <a href="#beranda" class="nav-item active">Beranda</a>
            </li>
            <li>
                <a href="#fitur" class="nav-item">Fitur</a>
            </li>
            <li>
                <a href="#alur" class="nav-item">Alur</a>
            </li>
            <li>
                <a href="#tentang" class="nav-item">Tentang Kami</a>
            </li>
            <li>
                <a href="{{ route('login', [], false) }}" class="nav-item">Login</a>
            </li>
        </ul>

        <button id="mobileMenuToggle" class="md:hidden inline-flex items-center justify-center w-10 h-10 rounded-lg border border-gray-200">
            <span class="sr-only">Toggle menu</span>
            <i data-lucide="menu"></i>
        </button>
    </nav>

    <div id="mobileMenu" class="mobile-menu md:hidden border-t border-gray-100 bg-white/95 backdrop-blur-md">
        <div class="px-6 py-4 flex flex-col gap-3 text-sm font-semibold tracking-wide">
            <a href="#beranda" class="nav-item">Beranda</a>
            <a href="#fitur" class="nav-item">Fitur</a>
            <a href="#alur" class="nav-item">Alur</a>
            <a href="#tentang" class="nav-item">Tentang Kami</a>
            <a href="{{ route('login', [], false) }}" class="nav-item">Login</a>
        </div>
    </div>
</header>

<section
    id="beranda"
    class="relative min-h-screen flex items-center overflow-hidden bg-white"
>
    <!-- Background kanan -->
    <div
        class="absolute top-0 right-0 w-1/2 h-full bg-gradient-to-br from-yellow-100 to-yellow-200 rounded-l-[200px]"
    ></div>

    <div class="container mx-auto px-6 relative z-10">
        <div class="grid md:grid-cols-2 items-center gap-12">

            <!-- Text -->
            <div>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold leading-tight mb-6">
                    <span class="text-yellow-500">{{ optional($hero)->title ?? 'Belajar Lebih Mudah,' }}</span><br />
                    <span class="text-gray-900">{{ optional($hero)->subtitle ?? 'Tanpa Sentuhan.' }}</span>
                </h1>

                <p class="text-base md:text-lg text-gray-600 max-w-xl mb-8 leading-relaxed">
                    {{ optional($hero)->description ?? 'Solusi edukasi modern yang menggabungkan teknologi voice dan path control untuk memberikan pengalaman belajar yang accessible, interaktif, dan efektif bagi semua siswa tanpa terkecuali.' }}
                </p>

                <a
                    href="{{ optional($hero)->button_url ?? '#fitur' }}"
                    class="inline-flex items-center px-8 py-4 bg-yellow-400 text-gray-900 font-semibold rounded-xl hover:bg-yellow-500 transition shadow-lg"
                >
                    {{ optional($hero)->button_label ?? 'Jelajahi Sekarang' }}
                </a>
            </div>

            <!-- Illustration -->
            <div class="hidden md:flex justify-center">
                <img
                    src="{{ optional($hero)->image_path ? Storage::url($hero->image_path) : asset('images/welcome.webp') }}"
                    alt="Ilustrasi Pembelajaran Ruma"
                    class="max-h-[420px] w-auto drop-shadow-xl"
                />
            </div>

        </div>
    </div>
</section>



    <!-- Features Section -->
 <section id="fitur" class="py-24 bg-white">
    <div class="container mx-auto px-6">

        <!-- Judul Section -->
        <div class="text-center max-w-3xl mx-auto mb-16">
            <h2 class="text-4xl md:text-5xl font-extrabold text-green-600 mb-4">
                {{ optional($featureHeader)->title ?? 'Fitur Unggulan' }}
            </h2>
            <p class="text-lg md:text-xl text-gray-600 leading-relaxed">
                {{ optional($featureHeader)->description ?? 'Ruma menghadirkan teknologi pembelajaran inklusif yang dirancang untuk memudahkan semua siswa belajar secara mandiri, interaktif, dan tanpa hambatan.' }}
            </p>
        </div>

        <!-- Card Fitur -->
        <div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto">
            @foreach($features as $feature)
                <div class="group bg-white border border-yellow-100 rounded-2xl p-8 shadow-md
                            transition-all duration-300 hover:bg-yellow-400 hover:-translate-y-2 hover:shadow-xl">

                    <div class="w-16 h-16 mb-6 flex items-center justify-center rounded-full
                                bg-yellow-100 group-hover:bg-white transition">
                        @php
                            $featureIcon = $feature->badge ?? (['eye', 'mic', 'accessibility'][$loop->index] ?? 'star');
                        @endphp
                        <i data-lucide="{{ $featureIcon }}" class="w-8 h-8 text-yellow-500"></i>
                    </div>

                    <h3 class="text-xl font-bold text-gray-900 mb-3">
                        {{ $feature->title }}
                    </h3>

                    <p class="text-gray-600 group-hover:text-gray-800 leading-relaxed">
                        {{ $feature->description }}
                    </p>
                </div>
            @endforeach
        </div>
    </div>
</section>

    <!-- Usage Flow Section -->
<section id="alur" class="py-24 bg-white">
    <div class="container mx-auto px-6">

        <!-- Judul -->
        <div class="text-center max-w-3xl mx-auto mb-20">
            <h2 class="text-4xl md:text-5xl font-extrabold text-green-600 mb-4">
                {{ optional($flowHeader)->title ?? 'Alur Penggunaan' }}
            </h2>
            <p class="text-lg text-gray-600 leading-relaxed">
                {{ optional($flowHeader)->description ?? 'Ikuti langkah-langkah sederhana untuk mulai belajar dengan Ruma secara mudah, inklusif, dan tanpa hambatan.' }}
            </p>
        </div>

        <!-- Steps -->
        <div class="max-w-4xl mx-auto space-y-10">
            @foreach($steps as $step)
                <div class="group relative flex items-start gap-6 step-item">
                    <span class="absolute left-6 top-14 h-full w-px bg-yellow-200 step-connector"></span>

                    <div class="flex-shrink-0 z-10">
                        <div
                            class="w-14 h-14 rounded-full bg-yellow-400 flex items-center justify-center
                                   text-gray-900 font-bold text-lg shadow-lg
                                   group-hover:scale-110 transition">
                            {{ $step->sort_order ?? $loop->iteration }}
                        </div>
                    </div>

                    <div
                        class="flex-1 bg-gray-50 p-6 rounded-2xl shadow-md
                               transition-all duration-300 group-hover:bg-yellow-50
                               group-hover:-translate-y-1 group-hover:shadow-xl">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">
                            {{ $step->title }}
                        </h3>
                        <p class="text-gray-600">
                            {{ $step->description }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

    <!-- Book Collection Section -->
        <section id="koleksi" class="py-24 bg-gradient-to-b from-yellow-50 to-white">
    <div class="container mx-auto px-6">

        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-16">
            <div>
                <h2 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-3">
                    {{ optional($collectionHeader)->title ?? 'Koleksi Rak Buku' }}
                </h2>
                <p class="text-lg text-gray-600 max-w-xl">
                    {{ optional($collectionHeader)->description ?? 'Beragam materi pembelajaran yang dapat diakses secara inklusif dan mudah untuk semua pengguna.' }}
                </p>
            </div>

            <a href="{{ optional($collectionHeader)->button_url ?? route('login', [], false) }}"
               class="inline-flex items-center gap-2 px-6 py-3
                      bg-yellow-400 text-gray-900 font-bold rounded-xl
                      hover:bg-yellow-500 transition shadow-md">
                {{ optional($collectionHeader)->button_label ?? 'Lihat Semua' }}
                <span>→</span>
            </a>
        </div>

        <!-- Carousel -->
        <div class="flex gap-8 overflow-x-auto pb-6 snap-x snap-mandatory">
            @forelse($books as $book)
                @php
                    $bookImage = $book->image_url
                        ? $book->image_url
                        : asset('images/book-shelf-' . (($loop->index % 3) + 1) . '.png');
                @endphp
                <div class="group snap-start flex-shrink-0 w-80 rounded-2xl
                            bg-white shadow-lg overflow-hidden
                            hover:-translate-y-2 hover:shadow-2xl transition-all duration-300">

                    <div class="relative h-56">
                        <img src="{{ $bookImage }}"
                             alt="{{ $book->title }}"
                             class="w-full h-full object-cover"
                             onerror="this.style.display='none';">

                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent"></div>

                        @if(!empty($book->badge))
                            <span class="absolute top-4 left-4 bg-yellow-400 text-gray-900
                                         text-sm font-bold px-3 py-1 rounded-full">
                                {{ $book->badge }}
                            </span>
                        @endif
                    </div>

                    <div class="p-6">
                        @if(!empty($book->meta))
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-gray-400 mb-3">
                                {{ $book->meta }}
                            </p>
                        @endif

                        <h3 class="text-2xl font-bold text-gray-900 mb-2">
                            {{ $book->title }}
                        </h3>
                        <p class="text-gray-600 text-sm mb-4">
                            {{ $book->description }}
                        </p>

                        <a href="{{ $book->button_url ?? route('login', [], false) }}"
                           class="inline-block w-full text-center px-4 py-3
                                  bg-gray-900 text-white font-semibold rounded-xl
                                  hover:bg-yellow-400 hover:text-gray-900 transition">
                            {{ $book->button_label ?? 'Lihat Detail' }}
                        </a>
                    </div>
                </div>
            @empty
                <div class="w-full rounded-2xl border border-dashed border-yellow-300 bg-white/80 p-10 text-center text-gray-600">
                    Belum ada materi aktif yang bisa ditampilkan di landing page.
                </div>
            @endforelse

        </div>
    </div>
</section>


 <!-- Get App Section -->
<section id="aplikasi" class="py-28 bg-white relative overflow-hidden">

    <!-- Background Shape -->
    <div class="absolute -bottom-32 left-1/2 -translate-x-1/2
                w-[700px] h-[250px] bg-yellow-200/70 rounded-full blur-2xl">
    </div>

    <div class="container mx-auto px-6 relative z-10">

        <div class="grid md:grid-cols-2 gap-16 items-center">

            <!-- Text Content -->
            <div>
                <h2 class="text-4xl md:text-5xl font-extrabold text-green-600 mb-6">
                    {{ optional($app)->title ?? 'Dapatkan Aplikasi Ruma' }}
                </h2>

                <p class="text-lg text-gray-700 mb-8 max-w-xl">
                    {{ optional($app)->description ?? 'Belajar menjadi lebih mudah dan inklusif dengan teknologi tanpa sentuhan. Ruma dapat digunakan melalui smartphone maupun web.' }}
                </p>

              <div class="flex flex-col sm:flex-row gap-4">
    <a href="{{ optional($app)->button_url ?? '#' }}"
       class="inline-flex items-center justify-center gap-3
              px-10 py-4 bg-gray-900 text-white font-bold
              rounded-xl hover:bg-yellow-400 hover:text-gray-900
              transition shadow-lg">
        <span class="text-xl">📱</span>
        {{ optional($app)->button_label ?? 'Download Sekarang' }}
    </a>
</div>
            </div>

            <!-- Mockup Image -->
            <div class="flex justify-center md:justify-end">
                <div class="relative">

                    <img src="{{ optional($app)->image_path ? Storage::url($app->image_path) : asset('images/mockup.png') }}"
                         alt="Mockup Aplikasi Ruma"
                         class="w-full max-w-xl drop-shadow-2xl
                                hover:scale-105 transition-transform duration-500"
                         onerror="this.style.display='none';">

                    <!-- Decorative dots -->
                    <div class="absolute -top-6 -right-6 w-16 h-16
                                border-4 border-yellow-400 rounded-xl"></div>
                </div>
            </div>

        </div>
    </div>
</section>


<!-- Footer -->
<footer class="bg-gradient-to-b from-[#5A3A2E] to-[#3E271E] text-white pt-12">
    <div class="container mx-auto px-6">

        <!-- Top Footer -->
        <div class="grid md:grid-cols-3 gap-14 mb-16">

            <!-- Brand & Description -->
            <div>
                <h3 class="text-3xl font-extrabold mb-4">{{ optional($footer)->title ?? 'Ruma' }}</h3>
                <p class="text-gray-200 leading-relaxed mb-6">
                    {{ optional($footer)->description ?? 'Platform pembelajaran inklusif yang membantu siswa belajar lebih mudah tanpa sentuhan, kapan pun dan di mana pun.' }}
                </p>

                <div class="text-sm text-gray-300 space-y-1">
                    <p>📍 {{ optional($footer)->meta_one ?? 'Tegal' }}</p>
                    <p>📞 {{ optional($footer)->meta_two ?? '+62 111-0000-2222' }}</p>
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h4 class="text-xl font-bold mb-6">Navigasi</h4>
                <ul class="space-y-3 text-gray-300">
                    <li>
                        <a href="#beranda" class="hover:text-yellow-400 transition">
                            Beranda
                        </a>
                    </li>
                    <li>
                        <a href="#fitur" class="hover:text-yellow-400 transition">
                            Fitur
                        </a>
                    </li>
                    <li>
                        <a href="#alur" class="hover:text-yellow-400 transition">
                            Alur Penggunaan
                        </a>
                    </li>
                    <li>
                        <a href="#tentang" class="hover:text-yellow-400 transition">
                            Tentang Kami
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Review Form -->
            <div>
                <div class="bg-yellow-50 p-8 rounded-2xl shadow-2xl">
                    <h4 class="text-2xl font-bold text-gray-900 mb-5">
                        Kirim Ulasan
                    </h4>

                    <form id="reviewForm" class="space-y-4" action="{{ route('landing.ulasan.store') }}" method="post">
                        @csrf
                        <div class="rating-stars rating-center" role="radiogroup" aria-label="Rating bintang">
                            @for($i = 5; $i >= 1; $i--)
                                <input type="radio"
                                       id="rating-{{ $i }}"
                                       name="rating"
                                       value="{{ $i }}"
                                       {{ old('rating') == $i ? 'checked' : '' }}>
                                <label for="rating-{{ $i }}" aria-label="{{ $i }} bintang">★</label>
                            @endfor
                        </div>
                        @error('rating')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <input type="text"
                               name="nama"
                               value="{{ old('nama') }}"
                               placeholder="Nama"
                               class="w-full px-4 py-3 rounded-xl
                                      border border-gray-300
                                      focus:outline-none focus:ring-2
                                      focus:ring-yellow-400 text-gray-900">
                        @error('nama')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <textarea rows="3"
                                  name="isi"
                                  placeholder="Tulis ulasan Anda..."
                                  class="w-full px-4 py-3 rounded-xl
                                         border border-gray-300
                                         focus:outline-none focus:ring-2
                                         focus:ring-yellow-400 text-gray-900
                                         resize-none">{{ old('isi') }}</textarea>
                        @error('isi')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <button type="submit"
                                class="w-full py-3 bg-yellow-400
                                       text-gray-900 font-bold rounded-xl
                                       hover:bg-yellow-500 transition">
                            Kirim Ulasan
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Bottom Footer -->
        <div class="border-t border-white/20 py-6">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-gray-300">
                <p>
                    © {{ date('Y') }} {{ optional($footer)->title ?? 'Ruma' }}. All rights reserved.
                </p>
                <p>
                    {{ optional($footer)->subtitle ?? 'Developed by Aura Pitaloka | 22090026' }}
                </p>
            </div>
        </div>

    </div>
</footer>


    <!-- Smooth Scroll Script -->
    <script>
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
        
        // Add scroll effect to header
        window.addEventListener('scroll', function() {
            const header = document.querySelector('header');
            if (window.scrollY > 100) {
                header.classList.add('shadow-lg');
            } else {
                header.classList.remove('shadow-lg');
            }
        });

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        const mobileToggle = document.getElementById('mobileMenuToggle');
        const mobileMenu = document.getElementById('mobileMenu');
        if (mobileToggle && mobileMenu) {
            mobileToggle.addEventListener('click', () => {
                mobileMenu.classList.toggle('open');
            });
        }

        const reviewForm = document.getElementById('reviewForm');
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        if (reviewForm) {
            reviewForm.addEventListener('submit', async (event) => {
                event.preventDefault();

                const formData = new FormData(reviewForm);
                try {
                    const res = await fetch(reviewForm.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: formData,
                    });

                    if (!res.ok) {
                        return;
                    }

                    const toast = document.getElementById('reviewToast');
                    if (toast) {
                        toast.classList.add('show');
                        setTimeout(() => toast.classList.remove('show'), 2500);
                    }

                    reviewForm.reset();
                } catch (err) {
                    // ignore
                }
            });
        }
    </script>

    <div id="reviewToast" class="review-toast">
        Ulasan berhasil dikirim. Terima kasih!
    </div>
</body>
</html>
