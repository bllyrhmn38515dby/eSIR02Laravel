@extends('layouts.app')

@push('styles')
<style>
    /* Styling Body Khusus Login untuk efek Medical Light Glassmorphism */
    body {
        background-color: var(--medical-bg);
        background-image: 
            radial-gradient(at 0% 0%, hsla(225,100%,90%,1) 0, transparent 50%), 
            radial-gradient(at 100% 100%, hsla(160,100%,95%,1) 0, transparent 50%);
        background-attachment: fixed;
        min-height: 100vh;
    }
    
    .login-container {
        min-height: 90vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }
    
    .glass-card {
        background: rgba(255, 255, 255, 0.7); /* Transparansi Kaca Terang */
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.1);
        border-radius: 24px;
        color: #2b2d42;
        padding: 3rem;
        width: 100%;
        max-width: 480px;
        transition: transform 0.4s ease, box-shadow 0.4s ease;
    }
    
    .glass-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 40px 0 rgba(31, 38, 135, 0.15);
    }
    
    .form-control {
        background: rgba(255, 255, 255, 0.5);
        border: 1px solid rgba(67, 97, 238, 0.1);
        color: #2b2d42;
        border-radius: 12px;
        padding: 12px 15px;
    }
    
    .form-control:focus {
        background: #fff;
        color: #2b2d42;
        border-color: var(--medical-primary);
        box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.15);
    }
    
    .form-control::placeholder {
        color: #adb5bd;
    }
    
    .btn-login {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        border: none;
        border-radius: 12px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        padding: 14px;
        color: #fff;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0, 242, 254, 0.3);
    }
    
    .btn-login:hover {
        background: linear-gradient(135deg, #00f2fe 0%, #4facfe 100%);
        box-shadow: 0 8px 25px rgba(0, 242, 254, 0.5);
        transform: scale(1.02);
    }
    
    .logo-container i {
        font-size: 3.5rem;
        background: linear-gradient(135deg, #4361ee 0%, #4895ef 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        filter: drop-shadow(0 2px 5px rgba(0,0,0,0.05));
    }

    .input-group-text {
        background: rgba(255, 255, 255, 0.5);
        border: 1px solid rgba(67, 97, 238, 0.1);
    }

    .login-footer {
        margin-top: 3rem;
        opacity: 0.6;
        font-size: 0.85rem;
    }
</style>
@endpush

@section('content')
<div class="container login-container">
    <div class="glass-card animate__animated animate__fadeInUp">
        <div class="text-center logo-container mb-5">
            <div class="mb-2">
                <i class="bi bi-hospital-fill"></i>
            </div>
            <h2 class="fw-bold mt-2 mb-0" style="letter-spacing: -1px; color: var(--medical-primary);">eSIR 2.1</h2>
            <p class="text-muted small mt-1 text-uppercase ls-1 fw-bold opacity-75">Portal Akses Rujukan Medik</p>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-4">
                <label for="email" class="form-label fw-bold text-muted small text-uppercase ls-1"><i class="bi bi-person-badge me-2"></i>Email Identitas</label>
                <div class="input-group">
                    <span class="input-group-text border-end-0" style="border-radius: 12px 0 0 12px;"><i class="bi bi-envelope text-primary"></i></span>
                    <input id="email" type="email" class="form-control border-start-0 @error('email') is-invalid @enderror" style="border-radius: 0 12px 12px 0;" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="admin@esir.id">
                </div>
                
                @error('email')
                    <span class="invalid-feedback d-block text-danger small mt-2" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="form-label fw-bold text-muted small text-uppercase ls-1"><i class="bi bi-shield-lock me-2"></i>Kode Verifikasi (Sandi)</label>
                <div class="input-group">
                    <span class="input-group-text border-end-0" style="border-radius: 12px 0 0 12px;"><i class="bi bi-key text-primary"></i></span>
                    <input id="password" type="password" class="form-control border-start-0 border-end-0 @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="••••••••">
                    <button class="btn btn-outline-light border-start-0 bg-white bg-opacity-50 text-muted" type="button" id="togglePassword" style="border: 1px solid rgba(67, 97, 238, 0.1); border-radius: 0 12px 12px 0;">
                        <i class="bi bi-eye" id="toggleIcon"></i>
                    </button>
                </div>

                @error('password')
                    <span class="invalid-feedback d-block text-danger small mt-2" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="d-flex justify-content-between align-items-center mb-5">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label text-muted small" for="remember">
                        Ingat Sesi Saya
                    </label>
                </div>
                @if (Route::has('password.request'))
                    <a class="text-primary text-decoration-none small fw-bold" href="{{ route('password.request') }}">
                        Lupa Akses?
                    </a>
                @endif
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-login py-3">
                    Buka Portal Layanan <i class="bi bi-arrow-right-short ms-1"></i>
                </button>
            </div>
            
            <div class="text-center login-footer">
                &copy; {{ date('Y') }} eSIR Project - Enterprise Health Referral System.
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Menyembunyikan Navbar bawaan di halaman Login agar Layout Bebas Penuh (Immersive)
        const navbar = document.querySelector('.navbar');
        if (navbar) {
            navbar.classList.add('d-none');
        }

        // Toggle Lihat Password
        const togglePassword = document.getElementById('togglePassword');
        const passwordField = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');

        if (togglePassword) {
            togglePassword.addEventListener('click', function () {
                const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordField.setAttribute('type', type);
                toggleIcon.classList.toggle('bi-eye');
                toggleIcon.classList.toggle('bi-eye-slash');
            });
        }
    });
</script>
@endpush
@endsection
