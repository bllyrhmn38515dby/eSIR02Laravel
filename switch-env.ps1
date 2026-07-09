# ============================================================
#  switch-env.ps1
#  Script untuk beralih mode koneksi eSIR2.1
#  Penggunaan:
#    .\switch-env.ps1 local       → Mode WiFi Lokal
#    .\switch-env.ps1 cloudflare  → Mode Cloudflare Tunnel (publik)
# ============================================================

param(
    [Parameter(Mandatory=$false)]
    [ValidateSet("local","cloudflare","status")]
    [string]$Mode
)

$envFile       = Join-Path $PSScriptRoot ".env"
$envLocal      = Join-Path $PSScriptRoot ".env.local"
$envCloudflare = Join-Path $PSScriptRoot ".env.cloudflare"

# --- Fungsi Bantuan ---
function Show-Banner {
    Write-Host ""
    Write-Host "============================================" -ForegroundColor Cyan
    Write-Host "  eSIR2.1 — Environment Switcher" -ForegroundColor Cyan
    Write-Host "============================================" -ForegroundColor Cyan
    Write-Host ""
}

function Get-CurrentMode {
    if (!(Test-Path $envFile)) { return "unknown" }
    $content = Get-Content $envFile -Raw
    if ($content -match "MODE: CLOUDFLARE") { return "cloudflare" }
    if ($content -match "MODE: LOCAL")      { return "local" }
    return "unknown"
}

function Show-Status {
    $current = Get-CurrentMode
    Write-Host "Mode aktif saat ini: " -NoNewline
    if ($current -eq "cloudflare") {
        Write-Host "☁️  CLOUDFLARE (Publik)" -ForegroundColor Green
        # Tampilkan URL yang aktif
        $appUrl = (Get-Content $envFile | Select-String "^APP_URL=").ToString().Replace("APP_URL=","")
        $revUrl = (Get-Content $envFile | Select-String "^REVERB_HOST=").ToString().Replace('REVERB_HOST=','').Replace('"','')
        Write-Host "  Laravel  : $appUrl" -ForegroundColor Yellow
        Write-Host "  Reverb   : $revUrl" -ForegroundColor Yellow
    } elseif ($current -eq "local") {
        Write-Host "🏠 LOCAL (WiFi sama)" -ForegroundColor Blue
        Write-Host "  Akses    : http://192.168.1.15:8000" -ForegroundColor Yellow
    } else {
        Write-Host "❓ Tidak diketahui" -ForegroundColor Red
    }
    Write-Host ""
}

function Switch-ToLocal {
    Write-Host "⏳ Mengganti ke mode LOCAL..." -ForegroundColor Yellow
    Copy-Item $envLocal $envFile -Force
    Write-Host "✅ .env diganti ke mode LOCAL" -ForegroundColor Green
    Write-Host ""
    Write-Host "  Laravel  : http://192.168.1.15:8000" -ForegroundColor Cyan
    Write-Host "  Reverb   : ws://192.168.1.15:8081" -ForegroundColor Cyan
    Write-Host ""
    # Clear Laravel config cache
    php artisan config:clear 2>$null
    php artisan cache:clear 2>$null
    Write-Host "✅ Config cache dihapus" -ForegroundColor Green
    Write-Host ""
    Write-Host "⚠️  INGAT: Jalankan 'npm run build' jika ada perubahan Vite." -ForegroundColor Yellow
}

function Switch-ToCloudflare {
    Write-Host ""
    Write-Host "🌐 MODE CLOUDFLARE TUNNEL" -ForegroundColor Cyan
    Write-Host "─────────────────────────────────────────────" -ForegroundColor DarkGray
    Write-Host ""
    Write-Host "Pastikan dua tunnel sudah berjalan di terminal:" -ForegroundColor White
    Write-Host "  Terminal 1: cloudflared tunnel --url http://localhost:8000  (Laravel)" -ForegroundColor DarkGray
    Write-Host "  Terminal 2: cloudflared tunnel --url http://localhost:8081  (Reverb)" -ForegroundColor DarkGray
    Write-Host ""

    $laravelUrl = Read-Host "Masukkan URL tunnel LARAVEL (contoh: molecular-xyz.trycloudflare.com)"
    $reverbUrl  = Read-Host "Masukkan URL tunnel REVERB  (contoh: cia-beneath-xyz.trycloudflare.com)"

    # Hapus https:// jika user mengetik lengkap
    $laravelUrl = $laravelUrl -replace "https://", "" -replace "http://", ""
    $reverbUrl  = $reverbUrl  -replace "https://", "" -replace "http://", ""

    if ([string]::IsNullOrWhiteSpace($laravelUrl) -or [string]::IsNullOrWhiteSpace($reverbUrl)) {
        Write-Host "❌ URL tidak boleh kosong. Dibatalkan." -ForegroundColor Red
        return
    }

    # Salin template ke .env
    Copy-Item $envCloudflare $envFile -Force

    # Ganti placeholder dengan URL asli
    (Get-Content $envFile) `
        -replace "LARAVEL_TUNNEL_URL_HERE", $laravelUrl `
        -replace "REVERB_TUNNEL_URL_HERE",  $reverbUrl |
    Set-Content $envFile

    Write-Host ""
    Write-Host "✅ .env diganti ke mode CLOUDFLARE" -ForegroundColor Green
    Write-Host ""
    Write-Host "  Laravel  : https://$laravelUrl" -ForegroundColor Cyan
    Write-Host "  Reverb   : wss://$reverbUrl:443" -ForegroundColor Cyan
    Write-Host ""

    # Clear config & rebuild Vite
    php artisan config:clear 2>$null
    php artisan cache:clear 2>$null
    Write-Host "✅ Config cache dihapus" -ForegroundColor Green
    Write-Host ""
    Write-Host "🔨 Membangun ulang Vite assets (npm run build)..." -ForegroundColor Yellow
    npm run build
    Write-Host ""
    Write-Host "✅ Selesai! Aplikasi siap diakses dari luar jaringan." -ForegroundColor Green
    Write-Host ""
    Write-Host "  🔗 Buka di HP: https://$laravelUrl" -ForegroundColor White
}

# --- Main ---
Show-Banner

if (-not $Mode) {
    Show-Status
    Write-Host "Penggunaan:" -ForegroundColor White
    Write-Host "  .\switch-env.ps1 local       → Beralih ke mode WiFi Lokal" -ForegroundColor DarkGray
    Write-Host "  .\switch-env.ps1 cloudflare  → Beralih ke mode Cloudflare (publik)" -ForegroundColor DarkGray
    Write-Host "  .\switch-env.ps1 status      → Lihat mode aktif" -ForegroundColor DarkGray
    Write-Host ""
    exit 0
}

switch ($Mode) {
    "status"     { Show-Status }
    "local"      { Switch-ToLocal }
    "cloudflare" { Switch-ToCloudflare }
}
