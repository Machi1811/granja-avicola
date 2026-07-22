# 📋 Comandos Útiles - Granja Avícola

Referencia rápida de comandos para el desarrollo y operación del sistema.

## 🚀 Desarrollo Local

### Inicialización del Proyecto
```bash
# Setup completo (primera vez)
bash setup.sh

# O manualmente:
composer install
npm install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate:fresh --seed
npm run build
```

### Servidor de Desarrollo
```bash
# Iniciar servidor Laravel
php artisan serve

# Iniciar Vite (desarrollo con hot reload)
npm run dev

# Compilar assets para producción
npm run build
```

### Base de Datos
```bash
# Ejecutar migraciones
php artisan migrate

# Revertir última migración
php artisan migrate:rollback

# Refrescar BD y cargar datos de prueba
php artisan migrate:fresh --seed

# Solo ejecutar seeders
php artisan db:seed

# Ejecutar un seeder específico
php artisan db:seed --class=GalponSeeder
```

### Cache
```bash
# Limpiar todo el cache
php artisan optimize:clear

# Limpiar cache específico
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Crear cache (producción)
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

## ⏰ Comandos de Alertas Automáticas

### Actualizar Ciclos de Pollos (RF-04)
```bash
# Actualiza días transcurridos y marca como "Listo para Venta" 
# los lotes que completen 120 días
php artisan pollos:actualizar-ciclos
```

### Verificar Galpones para Descarte (RF-05)
```bash
# Verifica galpones que superen 36 meses de vida
# Los marca como "descarte" y bloquea producción
php artisan galpones:verificar-descarte
```

### Verificar Stock de Alimentos (RF-02)
```bash
# Alerta cuando el stock es menor a 2 quintales
php artisan alimentos:verificar-stock
```

### Scheduler
```bash
# Ver todas las tareas programadas
php artisan schedule:list

# Ejecutar el scheduler manualmente (todas las tareas)
php artisan schedule:run

# Ejecutar una tarea específica
php artisan schedule:test
```

## 🔧 Mantenimiento

### Generar Clases
```bash
# Crear modelo
php artisan make:model NombreModelo

# Crear controlador
php artisan make:controller NombreController

# Crear migración
php artisan make:migration create_nombre_table

# Crear seeder
php artisan make:seeder NombreSeeder

# Crear comando
php artisan make:command NombreComando
```

### Información del Sistema
```bash
# Ver versión de Laravel
php artisan --version

# Ver todas las rutas
php artisan route:list

# Ver rutas de un controlador específico
php artisan route:list --name=pollos

# Ver información del entorno
php artisan about

# Ver lista de comandos disponibles
php artisan list
```

### Logs y Depuración
```bash
# Ver logs en tiempo real
tail -f storage/logs/laravel.log

# Limpiar logs
> storage/logs/laravel.log

# Modo de mantenimiento
php artisan down
php artisan up
```

## 🧪 Testing

### Ejecutar Tests
```bash
# Ejecutar todos los tests
php artisan test

# Ejecutar tests con cobertura
php artisan test --coverage

# Ejecutar un test específico
php artisan test --filter=NombreTest
```

## 📊 Consultas Rápidas en Base de Datos

### Usando Tinker (REPL de Laravel)
```bash
php artisan tinker

# Dentro de tinker:
>>> App\Models\Galpon::count()
>>> App\Models\Ponedora::where('estado', 'activo')->sum('cantidad_actual')
>>> App\Models\Alimento::all()
>>> App\Models\PolloEngorde::where('estado', 'listo_venta')->get()
>>> App\Models\Operario::with('asistencias')->find(1)
```

## 🎨 Assets y Frontend

### Compilar Assets
```bash
# Desarrollo (con watch)
npm run dev

# Producción (optimizado)
npm run build

# Verificar sintaxis
npm run lint
```

### Limpiar Dependencias
```bash
# Limpiar node_modules
rm -rf node_modules
npm install

# Limpiar vendor
rm -rf vendor
composer install

# Limpiar todo
rm -rf node_modules vendor
composer install
npm install
```

## 🔐 Seguridad

### Generar Application Key
```bash
php artisan key:generate
```

### Storage Link
```bash
# Crear enlace simbólico para storage público
php artisan storage:link
```

## 📦 Composer

### Gestión de Paquetes
```bash
# Instalar paquete
composer require vendor/package

# Instalar paquete de desarrollo
composer require --dev vendor/package

# Actualizar dependencias
composer update

# Autoload
composer dump-autoload
```

## 🚀 Despliegue en Render

### Verificar Configuración
```bash
# Probar script de build localmente
bash render-build.sh

# Ver variables de entorno necesarias
cat .env.render
```

### Deploy Manual
```bash
# Hacer ejecutable el script
chmod +x render-build.sh

# Commit y push
git add .
git commit -m "Deploy to Render"
git push origin main
```

## 🔢 Cálculos Matemáticos del Sistema

### Verificar Fórmulas
```bash
php artisan tinker

# RF-01: Merma del 10%
>>> $teorica = 500;
>>> $neta = $teorica * 0.9;  // 450 huevos

# RF-02: Consumo de alimento
>>> $aves = 500;
>>> $consumo_kg = ($aves * 120) / 1000;  // 60 kg
>>> $consumo_quintales = $consumo_kg / 46;  // 1.3043 qq

# RF-03: Tarificador de ventas
>>> $cantidad = 15;
>>> $precio = $cantidad > 12 ? 30 : 35;  // S/. 30.00

# RF-06: Pago de operarios
>>> $dias = 7;
>>> $pago = $dias * 80;  // S/. 560.00
```

## 📚 Recursos Adicionales

### Documentación
- [README.md](README.md) - Documentación principal
- [DEPLOY.md](DEPLOY.md) - Guía de despliegue en Render
- [Laravel Docs](https://laravel.com/docs) - Documentación oficial de Laravel

### Soporte
```bash
# Ver ayuda de un comando
php artisan help nombre-comando

# Ejemplos:
php artisan help migrate
php artisan help pollos:actualizar-ciclos
```

---

**Tip:** Guarda este archivo en favoritos para consulta rápida durante el desarrollo.
