# Script para habilitar SQLite en PHP para Windows
# Ejecutar como Administrador

Write-Host "🔧 Habilitando extensión SQLite en PHP..." -ForegroundColor Cyan
Write-Host ""

# Obtener ruta del php.ini
$phpIniPath = php --ini | Select-String "Loaded Configuration File" | ForEach-Object { $_.ToString().Split(":")[1].Trim() }

if (-not $phpIniPath -or -not (Test-Path $phpIniPath)) {
    Write-Host "❌ No se pudo encontrar el archivo php.ini" -ForegroundColor Red
    Write-Host "Por favor, edita manualmente: $phpIniPath" -ForegroundColor Yellow
    exit 1
}

Write-Host "📄 Archivo php.ini encontrado en:" -ForegroundColor Green
Write-Host "   $phpIniPath" -ForegroundColor White
Write-Host ""

# Crear backup
$backupPath = "$phpIniPath.backup_$(Get-Date -Format 'yyyyMMdd_HHmmss')"
Write-Host "💾 Creando backup en:" -ForegroundColor Cyan
Write-Host "   $backupPath" -ForegroundColor White
Copy-Item $phpIniPath $backupPath
Write-Host "   ✅ Backup creado" -ForegroundColor Green
Write-Host ""

# Leer contenido
$content = Get-Content $phpIniPath

# Verificar si ya está habilitado
$alreadyEnabled = $false
foreach ($line in $content) {
    if ($line -match "^\s*extension\s*=\s*pdo_sqlite" -or $line -match "^\s*extension\s*=\s*sqlite3") {
        $alreadyEnabled = $true
        break
    }
}

if ($alreadyEnabled) {
    Write-Host "✅ SQLite ya está habilitado en php.ini" -ForegroundColor Green
} else {
    # Modificar contenido
    $newContent = @()
    $modified = $false

    foreach ($line in $content) {
        # Descomentar pdo_sqlite
        if ($line -match "^;\s*extension\s*=\s*pdo_sqlite") {
            $newContent += $line -replace "^;\s*", ""
            $modified = $true
            Write-Host "✅ Habilitado: extension=pdo_sqlite" -ForegroundColor Green
        }
        # Descomentar sqlite3
        elseif ($line -match "^;\s*extension\s*=\s*sqlite3") {
            $newContent += $line -replace "^;\s*", ""
            $modified = $true
            Write-Host "✅ Habilitado: extension=sqlite3" -ForegroundColor Green
        }
        else {
            $newContent += $line
        }
    }

    if (-not $modified) {
        # Si no se encontraron las líneas comentadas, agregarlas
        Write-Host "ℹ️  No se encontraron las extensiones comentadas, agregándolas..." -ForegroundColor Yellow
        $newContent += ""
        $newContent += "; Extensiones SQLite habilitadas automáticamente"
        $newContent += "extension=pdo_sqlite"
        $newContent += "extension=sqlite3"
        Write-Host "✅ Extensiones agregadas al final del archivo" -ForegroundColor Green
    }

    # Guardar cambios
    try {
        $newContent | Set-Content $phpIniPath -Encoding UTF8
        Write-Host ""
        Write-Host "✅ Archivo php.ini actualizado exitosamente" -ForegroundColor Green
    }
    catch {
        Write-Host ""
        Write-Host "❌ Error al guardar cambios (¿permisos?)" -ForegroundColor Red
        Write-Host "   Ejecuta PowerShell como Administrador" -ForegroundColor Yellow
        exit 1
    }
}

Write-Host ""
Write-Host "🔍 Verificando instalación..." -ForegroundColor Cyan

# Verificar
$verification = php -m | Select-String -Pattern "pdo_sqlite|sqlite3"

if ($verification) {
    Write-Host ""
    Write-Host "✅ ¡SQLite habilitado correctamente!" -ForegroundColor Green
    Write-Host ""
    Write-Host "Extensiones encontradas:" -ForegroundColor Cyan
    $verification | ForEach-Object { Write-Host "   • $_" -ForegroundColor White }
    Write-Host ""
    Write-Host "🚀 Ahora puedes continuar con la instalación:" -ForegroundColor Green
    Write-Host "   1. composer install" -ForegroundColor White
    Write-Host "   2. npm install" -ForegroundColor White
    Write-Host "   3. cp .env.example .env" -ForegroundColor White
    Write-Host "   4. php artisan key:generate" -ForegroundColor White
    Write-Host "   5. New-Item -ItemType File database\database.sqlite" -ForegroundColor White
    Write-Host "   6. php artisan migrate:fresh --seed" -ForegroundColor White
    Write-Host "   7. npm run build" -ForegroundColor White
    Write-Host "   8. php artisan serve" -ForegroundColor White
} else {
    Write-Host ""
    Write-Host "⚠️  SQLite no se detecta aún" -ForegroundColor Yellow
    Write-Host "   Es posible que necesites reiniciar la terminal o el PC" -ForegroundColor Yellow
    Write-Host ""
    Write-Host "Para verificar manualmente:" -ForegroundColor Cyan
    Write-Host "   php -m | Select-String sqlite" -ForegroundColor White
}

Write-Host ""
Write-Host "═══════════════════════════════════════════════════════" -ForegroundColor Cyan
