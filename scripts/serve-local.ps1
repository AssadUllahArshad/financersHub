param([int]$Port = 8000)
$projectRoot = Split-Path -Parent $PSScriptRoot
$runtimeDirectory = Join-Path $projectRoot '.browser-runtime'
New-Item -ItemType Directory -Force -Path $runtimeDirectory | Out-Null
$previousTemp = $env:TEMP
$previousTmp = $env:TMP
try {
    $env:TEMP = $runtimeDirectory
    $env:TMP = $runtimeDirectory
    Push-Location -LiteralPath (Join-Path $projectRoot 'public')
    try {
        $router = Join-Path $projectRoot 'vendor/laravel/framework/src/Illuminate/Foundation/resources/server.php'
        Write-Host "FinancersHub: http://localhost:$Port (temporary files: $runtimeDirectory)"
        & php -d "upload_tmp_dir=$runtimeDirectory" -d "sys_temp_dir=$runtimeDirectory" -S "127.0.0.1:$Port" $router
    } finally { Pop-Location }
} finally {
    $env:TEMP = $previousTemp
    $env:TMP = $previousTmp
}
