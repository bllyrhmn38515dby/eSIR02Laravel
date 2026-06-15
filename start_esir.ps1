# eSIR 2.1 Launcher
$ProjectDir = Get-Location

Write-Host "Starting eSIR 2.1 Project Stack..."

# 0. MySQL
$mysqlProcess = Get-Process mysqld -ErrorAction SilentlyContinue
if ($mysqlProcess) {
    Write-Host "MySQL is already running."
} else {
    $xamppPaths = @("C:\xampp\mysql\bin\mysqld.exe", "D:\xampp\mysql\bin\mysqld.exe")
    $foundPath = $null
    foreach ($path in $xamppPaths) {
        if (Test-Path $path) { $foundPath = $path; break }
    }
    if ($foundPath) {
        Start-Process $foundPath --ArgumentList "--console" -WindowStyle Hidden
        Start-Sleep -Seconds 2
    }
}

# 1. NPM Run Dev
Start-Process powershell -ArgumentList "-NoExit", "-Command", "Set-Location -Path '$ProjectDir'; npm run dev"
Start-Sleep -Seconds 5

# 2. Cloudflare Tunnels
Start-Process powershell -ArgumentList "-NoExit", "-Command", "Set-Location -Path '$ProjectDir'; ./cloudflared.exe tunnel --url http://localhost:8080"
Start-Process powershell -ArgumentList "-NoExit", "-Command", "Set-Location -Path '$ProjectDir'; ./cloudflared.exe tunnel --url http://localhost:8081"

Write-Host "Started. Press Enter to close."
Read-Host
