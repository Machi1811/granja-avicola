# 🛠️ Guía de Instalación y Configuración

## Requisitos del Sistema

### PHP 8.2 o superior
Verificar instalación:
```bash
php -v
```

### Extensiones PHP Requeridas

El proyecto requiere las siguientes extensiones de PHP:

- ✅ OpenSSL
- ✅ PDO
- ✅ **PDO SQLite** (para desarrollo local)
- ✅ **PDO PostgreSQL** (para producción en Render)
- ✅ Mbstring
- ✅ Tokenizer
- ✅ XML
- ✅ Ctype
- ✅ JSON
- ✅ BCMath
- ✅ Fileinfo

### Verificar Extensiones Instaladas

```bash
php -m | grep -i pdo
php -m | grep -i sqlite
php -m | grep -i pgsql
```

## 🔧 Habilitar Extensión SQLite en Windows

Si ves el error `could not find driver` al ejecutar migraciones, necesitas habilitar SQLite:

### Método 1: Editar php.ini

1. Localiza tu archivo `php.ini`:
```bash
php --ini
```

2. Abre el archivo `php.ini` con un editor de texto

3. Busca y descomenta (quitar `;`) estas líneas:
```ini
extension=pdo_sqlite
extension=sqlite3
```

4. Guarda el archivo y reinicia el servidor

5. Verifica que esté habilitado:
```bash
php -m | findstr sqlite
```

Deberías ver:
```
pdo_sqlite
sqlite3
```

### Método 2: Usar PostgreSQL en Local (Alternativa)

Si prefieres no configurar SQLite, puedes usar PostgreSQL localmente:

1. Instalar PostgreSQL: https://www.postgresql.org/download/

2. Crear base de datos:
```sql
CREATE DATABASE granja_avicola;
CREATE USER granja_user WITH PASSWORD 'password';
GRANT ALL PRIVILEGES ON DATABASE granja_avicola TO granja_user;
```

3. Actualizar `.env`:
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=granja_avicola
DB_USERNAME=granja_user
DB_PASSWORD=password
```

## 📦 Instalación Paso a Paso

### 1. Clonar el Repositorio
```bash
git clone <url-repositorio>
cd granja-avicola
```

### 2. Instalar Dependencias

#### Composer (PHP)
```bash
composer install
```

Si hay error de memoria:
```bash
php -d memory_limit=-1 "$(which composer)" install
```

#### NPM (Node.js)
```bash
npm install
```

Si hay error, intenta:
```bash
npm ci
```

### 3. Configurar Entorno

```bash
# Copiar archivo de entorno
cp .env.example .env

# Generar key de aplicación
php artisan key:generate
```

### 4. Configurar Base de Datos

#### Opción A: SQLite (Desarrollo - Más Simple)
```bash
# Crear archivo de base de datos
touch database/database.sqlite

# En Windows PowerShell:
New-Item -ItemType File -Path database\database.sqlite
```

Asegúrate que `.env` tenga:
```env
DB_CONNECTION=sqlite
```

#### Opción B: PostgreSQL (Desarrollo - Producción-like)
Ver sección anterior sobre configuración de PostgreSQL

### 5. Ejecutar Migraciones y Seeders

```bash
php artisan migrate:fresh --seed
```

Esto creará:
- ✅ 9 tablas con estructura completa
- ✅ 4 galpones de ejemplo
- ✅ 495 ponedoras activas
- ✅ 2 tipos de alimento
- ✅ 3 operarios con historial
- ✅ 5 lotes de pollos en diferentes estados

### 6. Compilar Assets

```bash
# Desarrollo
npm run dev

# Producción
npm run build
```

### 7. Iniciar Servidor

```bash
php artisan serve
```

Acceder a: http://localhost:8000

## 🚀 Script de Setup Automático

Para Windows (PowerShell), ejecuta:
```powershell
# Hacer ejecutable
Set-ExecutionPolicy -Scope Process -ExecutionPolicy Bypass

# Ejecutar (cuando esté disponible)
# .\setup.ps1
```

Para Linux/Mac:
```bash
chmod +x setup.sh
./setup.sh
```

## 🔍 Verificar Instalación

### Verificar Configuración
```bash
php artisan about
```

### Verificar Base de Datos
```bash
php artisan db:show
```

### Probar Comandos Personalizados
```bash
php artisan pollos:actualizar-ciclos
php artisan galpones:verificar-descarte
php artisan alimentos:verificar-stock
```

### Verificar Rutas
```bash
php artisan route:list
```

## ⚠️ Problemas Comunes

### Error: "could not find driver"
**Causa:** Extensión PDO SQLite no habilitada  
**Solución:** Ver sección "Habilitar Extensión SQLite en Windows"

### Error: "Class not found"
```bash
composer dump-autoload
php artisan config:clear
```

### Error: "Permission denied" (Linux/Mac)
```bash
chmod -R 775 storage bootstrap/cache
```

### Error: "NPM install failed"
```bash
rm -rf node_modules package-lock.json
npm cache clean --force
npm install
```

### Error: "Composer memory limit"
```bash
php -d memory_limit=-1 "$(which composer)" install
```

### Assets no se cargan
```bash
npm run build
php artisan view:clear
php artisan config:clear
```

## 📊 Datos de Prueba Incluidos

Después de ejecutar los seeders, tendrás:

### Galpones (4)
- 3 activos (20, 34, 10 meses de vida)
- 1 en descarte (38 meses)

### Ponedoras (495 activas)
- Galpón A: 245 ponedoras
- Galpón B: 150 ponedoras  
- Galpón C: 100 ponedoras

### Alimentos (2 tipos)
- **Postura:** 1.5 quintales (⚠️ ALERTA - Menos de 2qq)
- **Engorde:** 8.0 quintales (✅ Stock OK)

### Operarios (3)
- Juan Pérez García
- María López Torres
- Carlos Rodríguez Sánchez
- Con historial de asistencia de los últimos 7 días

### Pollos de Engorde (5 lotes)
- LOTE-0001: 45 días (crecimiento)
- LOTE-0002: 115 días (próximo a completar)
- LOTE-0003: 120 días (✅ listo para venta)
- LOTE-0004: 20 días (reciente)
- LOTE-0005: 130 días (vendido hace 10 días)

### Producción Diaria
- Últimos 5 días para Galpón A
- Últimos 3 días para Galpón B
- Con cálculo automático de merma del 10%

## 🎯 Siguiente Paso

Una vez instalado, accede al dashboard en http://localhost:8000 y explora:

1. 📊 **Dashboard** - Ver alertas y estadísticas
2. ⚡ **Registro Rápido** - Probar formularios Mobile First
3. 🏠 **Galpones** - Ver alerta de 34 meses
4. 🌾 **Alimentos** - Ver alerta de stock bajo
5. 🐥 **Pollos** - Ver lote listo para venta

## 📚 Documentación Adicional

- [README.md](README.md) - Descripción general del proyecto
- [DEPLOY.md](DEPLOY.md) - Guía de despliegue en Render
- [COMANDOS.md](COMANDOS.md) - Comandos útiles para desarrollo

## 💡 Tips

- Usa `php artisan tinker` para interactuar con la base de datos
- Los logs están en `storage/logs/laravel.log`
- Ejecuta `php artisan schedule:list` para ver tareas programadas
- El sistema está optimizado para uso móvil (prueba con DevTools)

---

**¿Necesitas ayuda?** Revisa la sección de troubleshooting o consulta los logs del sistema.
