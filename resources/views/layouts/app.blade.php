<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts & Icons -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- PWA Installation Requirements -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#4361ee">
    <link rel="apple-touch-icon" href="https://cdn-icons-png.flaticon.com/512/2966/2966327.png">

    <!-- Dark Mode Prevents FOUC -->
    <script>
        const getStoredTheme = () => localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-bs-theme', getStoredTheme());
    </script>

    <style>
        :root {
            --medical-primary: #4361ee;
            --medical-secondary: #4895ef;
            --medical-success: #06d6a0;
            --medical-danger: #ef476f;
            --medical-warning: #ffd166;
            --medical-bg: #f8faff;
            --glass-bg: rgba(255, 255, 255, 0.7);
            --glass-border: rgba(255, 255, 255, 0.3);
        }

        body {
            background-color: var(--medical-bg);
            background-image: 
                radial-gradient(at 0% 0%, hsla(225,100%,90%,1) 0, transparent 50%), 
                radial-gradient(at 100% 100%, hsla(160,100%,95%,1) 0, transparent 50%);
            background-attachment: fixed;
            min-height: 100vh;
            color: #2b2d42;
        }

        .navbar {
            background: var(--glass-bg) !important;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--glass-border);
            padding: 1rem 0;
            transition: all 0.3s ease;
        }

        .navbar-brand {
            font-weight: 800;
            color: var(--medical-primary) !important;
            letter-spacing: -0.5px;
        }

        .nav-link {
            font-weight: 600;
            color: #4a4e69 !important;
            transition: color 0.2s ease;
        }

        .nav-link:hover {
            color: var(--medical-primary) !important;
        }

        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid var(--glass-border);
            border-radius: 16px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.07);
        }

        .btn-primary {
            background-color: var(--medical-primary);
            border: none;
            box-shadow: 0 4px 14px 0 rgba(67, 97, 238, 0.3);
            border-radius: 10px;
            padding: 0.6rem 1.5rem;
            font-weight: 600;
        }

        .btn-primary:hover {
            background-color: var(--medical-secondary);
            transform: translateY(-1px);
        }

        /* Standardize Card Styles */
        .card {
            border-radius: 16px;
            border: none;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.05) !important;
        }
    </style>
    @stack('styles')
    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        @auth
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                            </li>
                            @if(auth()->user()->role === 'admin_pusat')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('faskes.index') }}">Faskes</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('users.index') }}">Pengguna</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-primary fw-bold" href="{{ route('internal-testing.index') }}">
                                    <i class="bi bi-shield-check"></i> Uji Internal (Live)
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-info fw-bold" href="{{ route('uat-sandbox.index') }}">
                                    <i class="bi bi-file-earmark-check"></i> UAT Sandbox
                                </a>
                            </li>
                            @endif
                            @if(in_array(auth()->user()->role, ['admin_pusat', 'admin_faskes']))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('patients.index') }}">Pasien</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('bed-capacities.index') }}">Tempat Tidur</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('ambulances.index') }}">Armada Ambulans</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('drivers.index') }}">Sopir</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('referrals.index') }}">Rujukan</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('reports.index') }}">Laporan</a>
                            </li>
                            @endif
                        @endauth
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto align-items-center">
                        <li class="nav-item me-3">
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input mt-2" type="checkbox" role="switch" id="theme-toggle">
                                <label class="form-check-label text-muted ms-1 mt-1" for="theme-toggle" id="theme-icon">🌙</label>
                            </div>
                        </li>
                        <!-- Authentication Links -->
                        <!-- Notifications Dropdown -->
                        @auth
                        <li class="nav-item dropdown me-3">
                            <a id="navbarDropdownNotif" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                🔔 <span class="badge bg-danger rounded-pill">{{ auth()->user()->unreadNotifications->count() ?: '' }}</span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-end p-0 border-0 shadow" aria-labelledby="navbarDropdownNotif" style="width: 320px; border-radius: 12px; overflow: hidden;">
                                <div class="bg-primary text-white p-3 d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 fw-bold"><i class="bi bi-bell-fill me-2"></i>Notifikasi</h6>
                                    @if(auth()->user()->unreadNotifications->count() > 0)
                                        <span class="badge bg-light text-primary rounded-pill">{{ auth()->user()->unreadNotifications->count() }} Baru</span>
                                    @endif
                                </div>
                                <div class="list-group list-group-flush" style="max-height: 350px; overflow-y: auto;">
                                    @forelse(auth()->user()->unreadNotifications as $notification)
                                        @php
                                            $url = isset($notification->data['url']) ? $notification->data['url'] : (isset($notification->data['referral_id']) ? route('referrals.edit', $notification->data['referral_id']) : '#');
                                        @endphp
                                        <a href="{{ $url }}" class="list-group-item list-group-item-action p-3 border-bottom {{ $notification->read_at ? '' : 'bg-light' }}">
                                            <div class="d-flex w-100 justify-content-between align-items-center mb-1">
                                                <small class="text-primary fw-bold">Pembaruan Status</small>
                                                <small class="text-muted" style="font-size: 0.75rem;">{{ $notification->created_at->diffForHumans() }}</small>
                                            </div>
                                            <p class="mb-0 text-dark small">{{ $notification->data['message'] ?? 'Ada pembaruan status rujukan.' }}</p>
                                        </a>
                                    @empty
                                        <div class="p-4 text-center text-muted">
                                            <i class="bi bi-bell-slash fs-2 d-block mb-2 text-secondary opacity-50"></i>
                                            <small>Tidak ada notifikasi baru</small>
                                        </div>
                                    @endforelse
                                </div>
                                @if(auth()->user()->unreadNotifications->count() > 0)
                                    <div class="p-2 bg-light border-top text-center">
                                        <form action="{{ route('notifications.mark-all-read') }}" method="POST" class="m-0">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-link text-decoration-none w-100 text-primary fw-bold py-2">
                                                <i class="bi bi-check2-all me-1"></i> Tandai Semua Dibaca
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </li>
                        @endauth
                        
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>

        <!-- Notification Sound -->
        <audio id="notification-sound" preload="auto">
            <source src="https://assets.mixkit.co/active_storage/sfx/2358/2358-preview.mp3" type="audio/mpeg">
        </audio>
    </div>
    
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const themeToggle = document.getElementById('theme-toggle');
            const themeIcon = document.getElementById('theme-icon');
            const setStoredTheme = theme => localStorage.setItem('theme', theme);
            const setTheme = theme => document.documentElement.setAttribute('data-bs-theme', theme);

            if (themeToggle) {
                if (getStoredTheme() === 'dark') {
                    themeToggle.checked = true;
                    themeIcon.textContent = '☀️';
                }
                
                themeToggle.addEventListener('change', () => {
                    const newTheme = themeToggle.checked ? 'dark' : 'light';
                    themeIcon.textContent = themeToggle.checked ? '☀️' : '🌙';
                    setStoredTheme(newTheme);
                    setTheme(newTheme);
                });
            }
        });
    </script>

    <!-- PWA Service Worker Registration -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/sw.js').then(function(registration) {
                    console.log('PWA ServiceWorker registered');
                }, function(err) {
                    console.log('PWA ServiceWorker registration failed: ', err);
                });
            });
        }
    </script>
    
    @stack('scripts')

    @auth
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            // Minta Izin OS Native Push Notification
            if ("Notification" in window && Notification.permission === "default") {
                Notification.requestPermission();
            }

            // Dengarkan channel Broadcast dari Notifikasi Laravel via Reverb
            setTimeout(() => {
                if (window.Echo) {
                    window.Echo.private('App.Models.User.{{ auth()->id() }}')
                        .notification((notification) => {
                            // Mainkan suara notifikasi
                            const audio = document.getElementById('notification-sound');
                            if(audio) {
                                audio.play().catch(e => console.log('Audio autoplay blocked by browser', e));
                            }

                            // Cetuskan Push Notification OS Native
                            if ("Notification" in window && Notification.permission === "granted") {
                                const pushInfo = new Notification(notification.title || "Peringatan Darurat eSIR", {
                                    body: notification.body || notification.message,
                                    icon: 'https://cdn-icons-png.flaticon.com/512/2966/2966327.png',
                                    badge: 'https://cdn-icons-png.flaticon.com/512/2966/2966327.png',
                                    vibrate: [300, 100, 300, 100, 300]
                                });

                                pushInfo.onclick = function() {
                                    window.focus();
                                    if(notification.url) {
                                        window.location.href = notification.url;
                                    }
                                    this.close();
                                };
                            } else {
                                // Fallback jika browser menolak notifikasi push
                                alert((notification.title || "Notifikasi") + "\n" + (notification.body || notification.message));
                            }
                        });
                }
            }, 1000); // Tunggu Reverb terhubung
        });
    </script>
    @endauth
</body>
</html>
