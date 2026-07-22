# 🚀 Guía de Despliegue en Render

## Sistema de Gestión de Granja Avícola - MVP

Este documento explica cómo desplegar la aplicación en Render.com.

## 📋 Requisitos Previos

- Cuenta en [Render.com](https://render.com) (gratuita)
- Repositorio Git (GitHub, GitLab o Bitbucket)
- Código del proyecto subido al repositorio

## 🔧 Configuración Inicial

### 1. Preparar el Repositorio

Asegúrate de que estos archivos estén en tu repositorio:
- `render.yaml` - Configuración de servicios
- `render-build.sh` - Script de construcción
- `Procfile` - Comandos de inicio
- `.env.render` - Variables de entorno de ejemplo

### 2. Hacer el Script Ejecutable

En tu máquina local, ejecuta:

```bash
git update-index --chmod=+x render-build.sh
git add render-build.sh
git commit -m "Make render-build.sh executable"
git push
```

### 3. Crear Servicios en Render

#### Opción A: Despliegue Automático (Recomendado)

1. Ve a tu [Dashboard de Render](https://dashboard.render.com/)
2. Click en **"New +"** → **"Blueprint"**
3. Conecta tu repositorio Git
4. Render detectará automáticamente el archivo `render.yaml`
5. Configura las variables de entorno (ver sección siguiente)
6. Click en **"Apply"**

#### Opción B: Despliegue Manual

**Crear Base de Datos:**
1. Click en **"New +"** → **"PostgreSQL"**
2. Nombre: `granja-avicola-db`
3. Database: `granja_avicola`
4. Plan: Free
5. Click en **"Create Database"**

**Crear Web Service:**
1. Click en **"New +"** → **"Web Service"**
2. Conecta tu repositorio
3. Configuración:
   - **Name:** granja-avicola
   - **Environment:** PHP
   - **Build Command:** `./render-build.sh`
   - **Start Command:** `php artisan serve --host=0.0.0.0 --port=$PORT`
4. Agrega las variables de entorno (ver abajo)
5. Click en **"Create Web Service"**

## 🔑 Variables de Entorno Requeridas

En el panel de Render, configura estas variables de entorno:

### Variables Básicas
```
APP_NAME=Granja Avícola
APP_ENV=production
APP_DEBUG=false
APP_TIMEZONE=America/Lima
```

### Application Key
```bash
# Genera una key localmente y cópiala:
php artisan key:generate --show
# Ejemplo: base64:xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
```

Luego en Render:
```
APP_KEY=base64:xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
```

### Base de Datos (Auto-configuradas si usas Blueprint)
```
DB_CONNECTION=pgsql
DB_HOST=[desde la base de datos Render]
DB_PORT=5432
DB_DATABASE=[desde la base de datos Render]
DB_USERNAME=[desde la base de datos Render]
DB_PASSWORD=[desde la base de datos Render]
```

### Sesión y Cache
```
SESSION_DRIVER=database
CACHE_DRIVER=database
QUEUE_CONNECTION=database
```

## 📦 Primer Despliegue

El script `render-build.sh` ejecutará automáticamente:

1. ✅ Instalación de dependencias PHP (Composer)
2. ✅ Instalación de dependencias Node (NPM)
3. ✅ Compilación de assets (Vite)
4. ✅ Creación de directorios necesarios
5. ✅ Ejecución de migraciones
6. ✅ Carga de datos iniciales (seeders)
7. ✅ Optimización de cache

**⚠️ IMPORTANTE:** Después del primer despliegue exitoso, comenta estas líneas en `render-build.sh`:

```bash
# echo "🌱 Ejecutando seeders (solo primer deploy)..."
# php artisan db:seed --force
```

Esto evitará duplicar datos en despliegues futuros.

## ⏰ Configurar Tareas Programadas (Scheduler)

Laravel Scheduler necesita ejecutarse cada minuto. Tienes 3 opciones:

### Opción 1: Cron-Job.org (Recomendado - Gratuito)

1. Crea cuenta en [Cron-Job.org](https://cron-job.org)
2. Crea un nuevo Cron Job:
   - **URL:** `https://tu-app.onrender.com/artisan/schedule`
   - **Interval:** Every 1 minute
   - **Timeout:** 30 seconds

3. Agrega esta ruta en `routes/web.php`:

```php
Route::get('/artisan/schedule', function() {
    Artisan::call('schedule:run');
    return response()->json([
        'success' => true,
        'message' => 'Scheduler ejecutado',
        'time' => now()->toDateTimeString()
    ]);
})->middleware('throttle:120,1'); // Máximo 120 requests por minuto
```

### Opción 2: Background Worker en Render (Requiere Plan Pagado)

Crea un nuevo **Background Worker** con:
```bash
while true; do php artisan schedule:run --verbose --no-interaction & sleep 60; done
```

### Opción 3: EasyCron (Alternativa Gratuita)

Similar a Cron-Job.org: [EasyCron.com](https://www.easycron.com)

## 🔍 Comandos Disponibles

Ejecuta manualmente desde Render Shell:

```bash
# Actualizar ciclos de pollos (120 días)
php artisan pollos:actualizar-ciclos

# Verificar galpones para descarte (36 meses)
php artisan galpones:verificar-descarte

# Verificar stock de alimentos (< 2 quintales)
php artisan alimentos:verificar-stock

# Ver todas las tareas programadas
php artisan schedule:list
```

## 📊 Datos Iniciales

El sistema incluye datos de prueba:
- 4 galpones (3 activos, 1 en descarte)
- 495 ponedoras activas
- 2 tipos de alimento (postura con alerta, engorde OK)
- 3 operarios con historial de asistencia
- 5 lotes de pollos en diferentes estados del ciclo

## 🐛 Solución de Problemas

### Error: "Permission denied: ./render-build.sh"
```bash
git update-index --chmod=+x render-build.sh
git commit -m "Fix permissions"
git push
```

### Error: "APP_KEY not set"
Genera y configura la APP_KEY en las variables de entorno de Render.

### Error de Base de Datos
Verifica que las variables DB_* estén correctamente configuradas desde la base de datos Render.

### Assets no se cargan
Verifica que `npm run build` se ejecute sin errores en los logs de build.

### Scheduler no funciona
Configura el cron externo (Cron-Job.org) o verifica que el Background Worker esté activo.

## 🔄 Redespliegue

Para redesplegar después de cambios:

1. Haz push al repositorio:
```bash
git add .
git commit -m "Descripción de cambios"
git push
```

2. Render detectará los cambios automáticamente y redesplegará

## 📱 Acceso a la Aplicación

Una vez desplegado:
- URL: `https://granja-avicola-xxxx.onrender.com`
- La primera carga puede tardar ~30 segundos (plan gratuito)
- El sistema está optimizado para Mobile First

## 🎯 Características del Sistema

### Requerimientos Funcionales Implementados:
- ✅ **RF-01:** Control de inventario con merma automática del 10%
- ✅ **RF-02:** Conversión automática de alimento (120g/ave, quintales)
- ✅ **RF-03:** Tarificador de ventas (S/35 hasta 12, S/30 más de 12)
- ✅ **RF-04:** Control de ciclo 120 días para pollos de engorde
- ✅ **RF-05:** Alerta de descarte a los 36 meses
- ✅ **RF-06:** Control de jornales (S/80 por día)

### Alertas Automáticas:
- 🚨 Galpones próximos a 36 meses
- 🚨 Stock de alimento menor a 2 quintales
- 🚨 Pollos que completaron 120 días
- 🚨 Pollos próximos a completar ciclo (7 días)

## 📞 Soporte

Para problemas o dudas:
1. Revisa los logs en Render Dashboard
2. Verifica las variables de entorno
3. Consulta la documentación de Laravel

---

**Versión:** MVP 1.0  
**Última actualización:** 2026-07-21  
**Stack:** Laravel 11, Tailwind CSS 4, PostgreSQL
