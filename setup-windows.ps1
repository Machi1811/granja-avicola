# Script de instalacion para desarrollo local - Windows
# Sistema de Gestion Granja Avicola

$ErrorActionPreference = "Stop"

Write-Host ""
Write-Host "============================================" -ForegroundColor Green
Write-Host " Sistema de Gestion de Granja Avicola - MVP" -ForegroundColor Green
Write-Host " Instalacion para Desarrollo Local" -ForegroundColor Green
Write-Host "============================================" -ForegroundColor Green
Write-Host ""

# Verificar PHP
Write-Host "Verificando requisitos..." -ForegroundColor Cyan
try {
    php -v | Select-Object -First 1
    Write-Host "OK: PHP instalado" -ForegroundColor Green
} catch {
    Write-Host "ERROR: PHP no encontrado" -ForegroundColor Red
    exit 1
}

# Verificar Composer
try {
    composer --version | Select-Object -First 1
    Write-Host "OK: Composer instalado" -ForegroundColor Green
} catch {
    Write-Host "ERROR: Composer no encontrado" -ForegroundColor Red
    exit 1
}

# Verificar Node
try {
    node --version
    Write-Host "OK: Node.js instalado" -ForegroundColor Green
} catch {
    Write-Host "ERROR: Node.js no encontrado" -ForegroundColor Red
    exit 1
}

Write-Host ""

# Instalar dependencias PHP
Write-Host "Instalando dependencias PHP..." -ForegroundColor Cyan
composer install --no-interaction
Write-Host "OK: Dependencias PHP instaladas" -ForegroundColor Green
Write-Host ""

# Instalar dependencias Node
Write-Host "Instalando dependencias Node..." -ForegroundColor Cyan
npm install
Write-Host "OK: Dependencias Node instaladas" -ForegroundColor Green
Write-Host ""

# Configurar .env
if (-not (Test-Path ".env")) {
    Write-Host "Creando archivo .env..." -ForegroundColor Cyan
    Copy-Item ".env.example" ".env"
    Write-Host "OK: Archivo .env creado" -ForegroundColor Green
} else {
    Write-Host "AVISO: .env ya existe" -ForegroundColor Yellow
}
Write-Host ""

# Generar key
Write-Host "Generando Application Key..." -ForegroundColor Cyan
php artisan key:generate
Write-Host ""

# Configurar MySQL
Write-Host "============================================" -ForegroundColor Yellow
Write-Host " CONFIGURACION DE BASE DE DATOS" -ForegroundColor Yellow
Write-Host "============================================" -ForegroundColor Yellow
Write-Host ""
Write-Host "Opciones:" -ForegroundColor Cyan
Write-Host "1. MySQL (recomendado - ya tienes pdo_mysql)" -ForegroundColor White
Write-Host "2. SQLite (requiere habilitarlo en php.ini)" -ForegroundColor White
Write-Host ""
$opcion = Read-Host "Selecciona opcion (1 o 2)"

if ($opcion -eq "2") {
    Write-Host ""
    Write-Host "Para habilitar SQLite:" -ForegroundColor Yellow
    Write-Host "1. Abre: C:\Windows\php-8.4.2\php.ini" -ForegroundColor White
    Write-Host "2. Busca y descomenta:" -ForegroundColor White
    Write-Host "   ;extension=pdo_sqlite" -ForegroundColor Gray
    Write-Host "   ;extension=sqlite3" -ForegroundColor Gray
    Write-Host "3. Guarda el archivo" -ForegroundColor White
    Write-Host "4. Ejecuta este script nuevamente" -ForegroundColor White
    Write-Host ""
    exit 0
}

# Configurar MySQL
Write-Host ""
Write-Host "Configurando MySQL..." -ForegroundColor Cyan
Write-Host "Crea la base de datos primero:" -ForegroundColor Yellow
Write-Host "  mysql -u root -p" -ForegroundColor Gray
Write-Host "  CREATE DATABASE granja_avicola;" -ForegroundColor Gray
Write-Host ""
$continuar = Read-Host "Ya creaste la base de datos? (s/n)"

if ($continuar -ne "s") {
    Write-Host "Ejecuta primero:" -ForegroundColor Yellow
    Write-Host "  mysql -u root -p" -ForegroundColor White
    Write-Host "  CREATE DATABASE granja_avicola;" -ForegroundColor White
    Write-Host ""
    Write-Host "Luego ejecuta este script nuevamente" -ForegroundColor Cyan
    exit 0
}

# Ejecutar migraciones
Write-Host ""
Write-Host "Ejecutando migraciones..." -ForegroundColor Cyan
Write-Host "(Esto puede tardar un momento...)" -ForegroundColor Gray
try {
    php artisan migrate:fresh --seed --force
    Write-Host "OK: Base de datos inicializada" -ForegroundColor Green
} catch {
    Write-Host "ERROR: Fallo al migrar" -ForegroundColor Red
    Write-Host "Verifica tu configuracion en .env" -ForegroundColor Yellow
    exit 1
}
Write-Host ""

# Compilar assets
Write-Host "Compilando assets..." -ForegroundColor Cyan
npm run build
Write-Host "OK: Assets compilados" -ForegroundColor Green
Write-Host ""

# Limpiar cache
Write-Host "Limpiando cache..." -ForegroundColor Cyan
php artisan config:clear | Out-Null
php artisan cache:clear | Out-Null
php artisan view:clear | Out-Null
Write-Host "OK: Cache limpio" -ForegroundColor Green
Write-Host ""

# Resumen
Write-Host "============================================" -ForegroundColor Green
Write-Host " INSTALACION COMPLETADA" -ForegroundColor Green
Write-Host "============================================" -ForegroundColor Green
Write-Host ""
Write-Host "Datos cargados:" -ForegroundColor Cyan
Write-Host "  - 4 galpones" -ForegroundColor White
Write-Host "  - 495 ponedoras activas" -ForegroundColor White
Write-Host "  - 2 tipos de alimento (1 con alerta)" -ForegroundColor White
Write-Host "  - 3 operarios con historial" -ForegroundColor White
Write-Host "  - 5 lotes de pollos" -ForegroundColor White
Write-Host ""
Write-Host "Para iniciar:" -ForegroundColor Green
Write-Host "  php artisan serve" -ForegroundColor White
Write-Host ""
Write-Host "Accede a:" -ForegroundColor Green
Write-Host "  http://localhost:8000" -ForegroundColor Cyan
Write-Host ""
Write-Host "============================================" -ForegroundColor Green
