# Script de instalación para desarrollo local - Windows
# Sistema de Gestión Granja Avícola

$ErrorActionPreference = "Stop"

Write-Host ""
Write-Host "🐔 ═══════════════════════════════════════════════════" -ForegroundColor Green
Write-Host "   Sistema de Gestión de Granja Avícola - MVP" -ForegroundColor Green
Write-Host "   Instalación para Desarrollo Local" -ForegroundColor Green
Write-Host "═══════════════════════════════════════════════════" -ForegroundColor Green
Write-Host ""

# Verificar requisitos
Write-Host "🔍 Verificando requisitos del sistema..." -ForegroundColor Cyan
Write-Host ""

# PHP
try {
    $phpVersion = php -v | Select-Object -First 1
    Write-Host "✅ PHP:" -ForegroundColor Green -NoNewline
    Write-Host " $phpVersion" -ForegroundColor White
} catch {
    Write-Host "❌ PHP no está instalado o no está en PATH" -ForegroundColor Red
    exit 1
}

# Composer
try {
    $composerVersion = composer --version 2>$null | Select-Object -First 1
    Write-Host "✅ Composer:" -ForegroundColor Green -NoNewline
    Write-Host " $composerVersion" -ForegroundColor White
} catch {
    Write-Host "❌ Composer no está instalado" -ForegroundColor Red
    Write-Host "   Descarga desde: https://getcomposer.org/" -ForegroundColor Yellow
    exit 1
}

# Node/NPM
try {
    $nodeVersion = node --version 2>$null
    $npmVersion = npm --version 2>$null
    Write-Host "✅ Node.js:" -ForegroundColor Green -NoNewline
    Write-Host " $nodeVersion" -ForegroundColor White
    Write-Host "✅ NPM:" -ForegroundColor Green -NoNewline
    Write-Host " v$npmVersion" -ForegroundColor White
} catch {
    Write-Host "❌ Node.js no está instalado" -ForegroundColor Red
    Write-Host "   Descarga desde: https://nodejs.org/" -ForegroundColor Yellow
    exit 1
}

Write-Host ""
Write-Host "✅ Todos los requisitos están instalados" -ForegroundColor Green
Write-Host ""

# Instalar dependencias PHP
Write-Host "📦 Instalando dependencias de PHP (Composer)..." -ForegroundColor Cyan
try {
    composer install --no-interaction
    Write-Host "✅ Dependencias PHP instaladas" -ForegroundColor Green
} catch {
    Write-Host "❌ Error al instalar dependencias PHP" -ForegroundColor Red
    exit 1
}
Write-Host ""

# Instalar dependencias Node
Write-Host "📦 Instalando dependencias de Node (NPM)..." -ForegroundColor Cyan
try {
    npm install
    Write-Host "✅ Dependencias Node instaladas" -ForegroundColor Green
} catch {
    Write-Host "❌ Error al instalar dependencias Node" -ForegroundColor Red
    exit 1
}
Write-Host ""

# Configurar .env
if (-not (Test-Path ".env")) {
    Write-Host "📝 Creando archivo .env..." -ForegroundColor Cyan
    Copy-Item ".env.example" ".env"
    Write-Host "✅ Archivo .env creado" -ForegroundColor Green
} else {
    Write-Host "⚠️  El archivo .env ya existe, no se sobrescribirá" -ForegroundColor Yellow
}
Write-Host ""

# Generar Application Key
Write-Host "🔑 Generando Application Key..." -ForegroundColor Cyan
php artisan key:generate
Write-Host ""

# Detectar base de datos disponible
Write-Host "🗄️  Configurando base de datos..." -ForegroundColor Cyan
$sqliteEnabled = (php -m | Select-String -Pattern "pdo_sqlite|sqlite3") -ne $null
$mysqlEnabled = (php -m | Select-String -Pattern "pdo_mysql") -ne $null

if ($sqliteEnabled) {
    Write-Host "✅ SQLite detectado - Creando base de datos..." -ForegroundColor Green
    
    if (-not (Test-Path "database\database.sqlite")) {
        New-Item -ItemType File -Path "database\database.sqlite" -Force | Out-Null
        Write-Host "✅ Base de datos SQLite creada" -ForegroundColor Green
    } else {
        Write-Host "   Base de datos SQLite ya existe" -ForegroundColor Yellow
    }
    
    # Asegurar que .env use SQLite
    $envContent = Get-Content ".env" -Raw
    if ($envContent -notmatch "DB_CONNECTION=sqlite") {
        $envContent = $envContent -replace "DB_CONNECTION=.*", "DB_CONNECTION=sqlite"
        $envContent | Set-Content ".env" -NoNewline
        Write-Host "✅ Configurado para usar SQLite" -ForegroundColor Green
    }
} elseif ($mysqlEnabled) {
    Write-Host "⚠️  SQLite no disponible, usando MySQL..." -ForegroundColor Yellow
    Write-Host ""
    Write-Host "Por favor, crea la base de datos manualmente:" -ForegroundColor Cyan
    Write-Host "   1. Abre MySQL:" -ForegroundColor White
    Write-Host "      mysql -u root -p" -ForegroundColor Gray
    Write-Host "   2. Ejecuta:" -ForegroundColor White
    Write-Host "      CREATE DATABASE granja_avicola;" -ForegroundColor Gray
    Write-Host "   3. Presiona Enter cuando esté lista..." -ForegroundColor White
    Write-Host ""
    
    # Esperar confirmación
    $null = Read-Host "Presiona Enter para continuar"
    
    Write-Host "✅ Continuando con MySQL..." -ForegroundColor Green
} else {
    Write-Host "❌ No se detectó ni SQLite ni MySQL" -ForegroundColor Red
    Write-Host ""
    Write-Host "Opciones:" -ForegroundColor Yellow
    Write-Host "1. Habilita SQLite ejecutando (como Admin):" -ForegroundColor White
    Write-Host "   .\habilitar-sqlite.ps1" -ForegroundColor Gray
    Write-Host "2. O instala MySQL/MariaDB" -ForegroundColor White
    exit 1
}
Write-Host ""

# Ejecutar migraciones
Write-Host "📊 Ejecutando migraciones y cargando datos de prueba..." -ForegroundColor Cyan
Write-Host "   (Esto puede tardar un momento...)" -ForegroundColor Gray
try {
    php artisan migrate:fresh --seed --force
    Write-Host "✅ Base de datos inicializada con datos de prueba" -ForegroundColor Green
} catch {
    Write-Host "❌ Error al ejecutar migraciones" -ForegroundColor Red
    Write-Host "   Verifica que la base de datos esté correctamente configurada" -ForegroundColor Yellow
    exit 1
}
Write-Host ""

# Compilar assets
Write-Host "🎨 Compilando assets con Vite..." -ForegroundColor Cyan
try {
    npm run build
    Write-Host "✅ Assets compilados" -ForegroundColor Green
} catch {
    Write-Host "⚠️  Error al compilar assets (no crítico)" -ForegroundColor Yellow
}
Write-Host ""

# Limpiar cache
Write-Host "🧹 Limpiando cache..." -ForegroundColor Cyan
php artisan config:clear | Out-Null
php artisan cache:clear | Out-Null
php artisan view:clear | Out-Null
Write-Host "✅ Cache limpio" -ForegroundColor Green
Write-Host ""

# Resumen final
Write-Host "✅ ═══════════════════════════════════════════════════" -ForegroundColor Green
Write-Host "   ¡Instalación completada exitosamente!" -ForegroundColor Green
Write-Host "═══════════════════════════════════════════════════" -ForegroundColor Green
Write-Host ""
Write-Host "📊 Datos de prueba cargados:" -ForegroundColor Cyan
Write-Host "   • 4 galpones (3 activos, 1 en descarte)" -ForegroundColor White
Write-Host "   • 495 ponedoras activas distribuidas" -ForegroundColor White
Write-Host "   • 2 tipos de alimento (postura: ALERTA, engorde: OK)" -ForegroundColor White
Write-Host "   • 3 operarios con historial de asistencia" -ForegroundColor White
Write-Host "   • 5 lotes de pollos en diferentes estados" -ForegroundColor White
Write-Host ""
Write-Host "🚀 Para iniciar el servidor:" -ForegroundColor Green
Write-Host "   php artisan serve" -ForegroundColor White
Write-Host ""
Write-Host "🌐 Luego accede a:" -ForegroundColor Green
Write-Host "   http://localhost:8000" -ForegroundColor Cyan
Write-Host ""
Write-Host "⏰ Para probar las alertas automáticas:" -ForegroundColor Yellow
Write-Host "   php artisan pollos:actualizar-ciclos" -ForegroundColor White
Write-Host "   php artisan galpones:verificar-descarte" -ForegroundColor White
Write-Host "   php artisan alimentos:verificar-stock" -ForegroundColor White
Write-Host ""
Write-Host "📖 Ver documentación completa:" -ForegroundColor Cyan
Write-Host "   README.md, INSTALACION.md, COMANDOS.md" -ForegroundColor White
Write-Host ""
Write-Host "═══════════════════════════════════════════════════" -ForegroundColor Green
