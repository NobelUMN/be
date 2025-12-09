# PowerShell helper: test webhook POST to backend
# Usage:
# 1) Edit $Uri or pass as param: .\test-webhook.ps1 -Uri 'http://127.0.0.1:8000/api/hardware/webhook' -Key 's3cr3t123'
# 2) Run: PowerShell (paste) or: powershell -ExecutionPolicy Bypass -File .\backend\scripts\test-webhook.ps1

param(
    [string]$Uri = 'http://127.0.0.1:8000/api/hardware/webhook',
    [string]$Key = 's3cr3t123'
)

$headers = @{
    'X-DEVICE-KEY' = $Key
    'Content-Type'  = 'application/json'
}

$body = @{
    event = 'status'
    data  = @{
        temp = 24.5
    }
}

Write-Host "POSTing to: $Uri" -ForegroundColor Cyan
Write-Host "Using device key: $Key" -ForegroundColor Cyan

try {
    $resp = Invoke-RestMethod -Uri $Uri -Method Post -Headers $headers -Body ($body | ConvertTo-Json -Depth 5) -ErrorAction Stop
    Write-Host "Response:" -ForegroundColor Green
    $resp | ConvertTo-Json -Depth 5
}
catch {
    Write-Host "Request failed:`n$($_.Exception.Message)" -ForegroundColor Red
    exit 1
}

Write-Host "Done." -ForegroundColor Green
