#!/bin/bash
set -e

echo "🚀 Iniciando aplicación Laravel..."

# Esperar a que la base de datos esté lista
echo "⏳ Esperando base de datos..."
sleep 5

# Limpiar cache
echo "🧹 Limpiando cache..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Ejecutar migraciones
echo "🗄️  Ejecutando migraciones..."
php artisan migrate --force

# Ejecutar seeders (solo primera vez)
if [ ! -f /var/www/html/storage/.seeded ]; then
    echo "🌱 Ejecutando seeders..."
    php artisan db:seed --force
    touch /var/www/html/storage/.seeded
fi

# Optimizar para producción
echo "⚡ Optimizando..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Crear enlace simbólico para storage
php artisan storage:link || true

echo "✅ Aplicación lista!"

# Ejecutar comando de Apache
exec "$@"
