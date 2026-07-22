#!/usr/bin/env bash
# Exit on error
set -o errexit

echo "🚀 Iniciando build para Render..."

# Instalar dependencias de Composer
echo "📦 Instalando dependencias de PHP..."
composer install --no-dev --optimize-autoloader --no-interaction

# Instalar dependencias de NPM y construir assets
echo "📦 Instalando dependencias de Node..."
npm ci

echo "🎨 Compilando assets con Vite..."
npm run build

# Crear directorios necesarios si no existen
echo "📁 Creando directorios de storage..."
mkdir -p storage/app/public
mkdir -p storage/framework/{sessions,views,cache}
mkdir -p storage/logs
mkdir -p bootstrap/cache

# Configurar permisos
echo "🔐 Configurando permisos..."
chmod -R 775 storage bootstrap/cache

# Generar key si no existe
if [ -z "$APP_KEY" ]; then
    echo "🔑 Generando Application Key..."
    php artisan key:generate --force
fi

# Limpiar cache
echo "🧹 Limpiando cache..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Ejecutar migraciones
echo "🗄️  Ejecutando migraciones de base de datos..."
php artisan migrate --force

# Ejecutar seeders solo en primer despliegue
# Comentar después del primer deploy para evitar duplicados
echo "🌱 Ejecutando seeders (solo primer deploy)..."
php artisan db:seed --force

# Optimizar para producción
echo "⚡ Optimizando para producción..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Crear enlace simbólico para storage público
echo "🔗 Creando enlace simbólico de storage..."
php artisan storage:link || true

echo "✅ Build completado exitosamente!"
