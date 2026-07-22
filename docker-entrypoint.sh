#!/bin/bash
set -e

echo "🚀 Iniciando aplicación Laravel..."

# Esperar a que la base de datos esté lista
echo "⏳ Esperando base de datos..."
sleep 5

# Limpiar configuración solamente (sin tocar cache de BD que aún no existe)
echo "🧹 Limpiando configuración..."
php artisan config:clear
php artisan view:clear
php artisan route:clear

# Ejecutar migraciones PRIMERO (para crear las tablas)
echo "🗄️  Ejecutando migraciones..."
php artisan migrate --force

# Ejecutar seeders (solo primera vez)
if [ ! -f /var/www/html/storage/.seeded ]; then
    echo "🌱 Ejecutando seeders..."
    php artisan db:seed --force
    touch /var/www/html/storage/.seeded
fi

# AHORA sí limpiar cache (después de que existan las tablas)
echo "🧹 Limpiando cache de base de datos..."
php artisan cache:clear

# Optimizar para producción
echo "⚡ Optimizando..."
php artisan config:cache
# php artisan route:cache  # DESHABILITADO temporalmente hasta que todo funcione
# php artisan view:cache  # DESHABILITADO: causa problemas con vistas actualizadas

# Crear enlace simbólico para storage
php artisan storage:link || true

echo "✅ Aplicación lista!"

# Mostrar últimas líneas del log de Laravel si existe un error
echo "📋 Verificando logs..."
if [ -f /var/www/html/storage/logs/laravel.log ]; then
    tail -n 20 /var/www/html/storage/logs/laravel.log || true
fi

# Ejecutar comando de Apache
exec "$@"
