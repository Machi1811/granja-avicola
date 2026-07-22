#!/usr/bin/env bash
# Script de configuración rápida para desarrollo local

set -e

echo "🐔 ======================================"
echo "   Sistema de Gestión Granja Avícola"
echo "   Setup de Desarrollo Local"
echo "========================================"
echo ""

# Verificar requisitos
echo "🔍 Verificando requisitos..."

if ! command -v php &> /dev/null; then
    echo "❌ PHP no está instalado. Por favor instala PHP 8.2+"
    exit 1
fi

if ! command -v composer &> /dev/null; then
    echo "❌ Composer no está instalado. Por favor instala Composer"
    exit 1
fi

if ! command -v npm &> /dev/null; then
    echo "❌ NPM no está instalado. Por favor instala Node.js 18+"
    exit 1
fi

echo "✅ Requisitos verificados"
echo ""

# Instalar dependencias PHP
echo "📦 Instalando dependencias de PHP (Composer)..."
composer install
echo ""

# Instalar dependencias Node
echo "📦 Instalando dependencias de Node (NPM)..."
npm install
echo ""

# Configurar entorno
if [ ! -f .env ]; then
    echo "📝 Creando archivo .env..."
    cp .env.example .env
    echo "✅ Archivo .env creado"
else
    echo "⚠️  El archivo .env ya existe, no se sobrescribirá"
fi
echo ""

# Generar key
echo "🔑 Generando Application Key..."
php artisan key:generate
echo ""

# Configurar base de datos SQLite
echo "🗄️  Configurando base de datos SQLite..."
touch database/database.sqlite
echo "✅ Base de datos SQLite creada"
echo ""

# Ejecutar migraciones y seeders
echo "📊 Ejecutando migraciones y seeders..."
php artisan migrate:fresh --seed
echo ""

# Compilar assets
echo "🎨 Compilando assets con Vite..."
npm run build
echo ""

# Limpiar cache
echo "🧹 Limpiando cache..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
echo ""

echo "✅ ======================================"
echo "   ¡Configuración completada!"
echo "========================================"
echo ""
echo "📊 Datos de prueba cargados:"
echo "   • 4 galpones (3 activos, 1 en descarte)"
echo "   • 495 ponedoras activas"
echo "   • 2 tipos de alimento (postura: ALERTA, engorde: OK)"
echo "   • 3 operarios con historial de asistencia"
echo "   • 5 lotes de pollos en diferentes estados"
echo ""
echo "🚀 Para iniciar el servidor de desarrollo:"
echo "   php artisan serve"
echo ""
echo "🌐 Luego accede a: http://localhost:8000"
echo ""
echo "⏰ Para probar las alertas automáticas:"
echo "   php artisan pollos:actualizar-ciclos"
echo "   php artisan galpones:verificar-descarte"
echo "   php artisan alimentos:verificar-stock"
echo ""
echo "📖 Ver documentación completa en README.md"
echo "========================================"
