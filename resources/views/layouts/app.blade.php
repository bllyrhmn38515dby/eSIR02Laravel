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
                                    <i class="bi bi-shield-check"></i> Uji Internal
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
                                🔔 <span class="badge bg-danger">{{ auth()->user()->unreadNotifications->count() ?: '' }}</span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownNotif">
                                @forelse(auth()->user()->unreadNotifications as $notification)
                                    <a class="dropdown-item text-wrap" style="width: 300px; border-bottom: 1px solid #eee;" href="#">
                                        <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small><br>
                                        {{ $notification->data['message'] }}
                                    </a>
                                @empty
                                    <a class="dropdown-item text-muted" href="#">Tidak ada notifikasi baru</a>
                                @endforelse
                                @if(auth()->user()->unreadNotifications->count() > 0)
                                    <div class="dropdown-divider"></div>
                                    <form action="{{ route('notifications.mark-all-read') }}" method="POST" class="px-2">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-primary w-100 rounded-pill">
                                            Tandai Semua Dibaca
                                        </button>
                                    </form>
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
