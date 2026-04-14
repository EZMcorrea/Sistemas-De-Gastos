<#
download_chromedriver.ps1
Usage: .\scripts\download_chromedriver.ps1 -Url <download_url> [-OutDir tools\chromedriver]
Example: .\scripts\download_chromedriver.ps1 -Url "https://edgedl.me.gvt1.com/edgedl/chrome/chrome-for-testing/114.0.5735.90/windows64/chrome-win64.zip"
#>
param(
    [Parameter(Mandatory=$true)][string]$Url,
    [string]$OutDir = "tools\chromedriver"
)

if (-not (Test-Path $OutDir)) { New-Item -ItemType Directory -Path $OutDir | Out-Null }
$zipPath = Join-Path $OutDir "chrome-for-testing.zip"

Write-Host "Downloading Chrome for Testing from: $Url"

try {
    Invoke-WebRequest -Uri $Url -OutFile $zipPath -UseBasicParsing -ErrorAction Stop
} catch {
    Write-Error "Download failed: $_"
    exit 1
}

Write-Host "Extracting to $OutDir"
Add-Type -AssemblyName System.IO.Compression.FileSystem
[System.IO.Compression.ZipFile]::ExtractToDirectory($zipPath, $OutDir)
Remove-Item $zipPath -Force

# find chromedriver
$driver = Get-ChildItem -Path $OutDir -Recurse -Filter 'chromedriver*.exe' -ErrorAction SilentlyContinue | Select-Object -First 1
if ($driver) {
    Write-Host "Chromedriver found at: $($driver.FullName)"
    Write-Host "Set DUSK_CHROME_DRIVER_PATH to this path in your .env or environment variables."
} else {
    Write-Warning "Chromedriver not found automatically. Inspect $OutDir to locate chromedriver.";
    exit 2
}
