# 🐔 Sistema de Gestión de Granja Avícola

Sistema MVP para gestión integral de granja avícola con módulos de producción de huevos, pollos de engorde, control de alimentos, ventas y personal.

## 🚀 Características Principales

### Requerimientos Funcionales Implementados

- **RF-01**: Control de merma del 10% en producción de huevos
- **RF-02**: Consumo de alimento de 120g por ponedora diaria
- **RF-03**: Ciclo de producción de pollos de engorde de 120 días
- **RF-04**: Tarificador automático (S/35 > 30 huevos, S/30 ≤ 30 huevos)
- **RF-05**: Gestión de descarte de ponedoras a los 36 meses
- **RF-06**: Control de pago a operarios (S/80 por día trabajado)

### Módulos CRUD Completos

1. **Galpones** - Gestión de infraestructura
2. **Ponedoras** - Control de lotes de gallinas ponedoras
3. **Producción Diaria** - Registro de huevos producidos con cálculo de merma
4. **Pollos de Engorde** - Gestión de lotes con ciclo de 120 días
5. **Alimentos** - Control de stock (quintales y kg) con precio
6. **Ventas** - Registro de ventas de huevos, pollos y descarte
7. **Operarios** - Gestión de personal
8. **Asistencias** - Control de asistencia y cálculo de pagos

## 🛠️ Stack Tecnológico

- **Backend**: Laravel 11.x (PHP 8.4)
- **Frontend**: Blade Templates + Tailwind CSS (Mobile First)
- **Base de Datos**: PostgreSQL (Producción) / MySQL (Local)
- **Deployment**: Docker + Render.com
- **Version Control**: Git + GitHub

## 📦 Instalación Local

### Requisitos
- PHP 8.2+
- Composer
- MySQL o PostgreSQL
- Node.js 18+ y npm

### Pasos

```bash
# Clonar repositorio
git clone https://github.com/Machi1811/granja-avicola.git
cd granja-avicola

# Instalar dependencias
composer install
npm install

# Configurar entorno
cp .env.example .env
php artisan key:generate

# Configurar base de datos en .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=granja_avicola
DB_USERNAME=root
DB_PASSWORD=

# Migrar y sembrar datos
php artisan migrate --seed

# Compilar assets
npm run build

# Iniciar servidor
php artisan serve
```

Acceder a: `http://localhost:8000`

## 🚢 Deployment en Render

### Base de Datos PostgreSQL
1. Crear PostgreSQL Database en Render
2. Copiar credenciales (Internal Database URL)

### Web Service (Docker)
1. Crear Web Service desde GitHub repo
2. Runtime: **Docker**
3. Configurar variables de entorno:

```env
APP_KEY=base64:...
APP_ENV=production
APP_DEBUG=false
DB_CONNECTION=pgsql
DB_HOST=dpg-xxx.oregon-postgres.render.com
DB_PORT=5432
DB_DATABASE=granja_avicola
DB_USERNAME=granja_user
DB_PASSWORD=xxx
ASSET_URL=https://tu-app.onrender.com
```

4. Deploy automático desde push a main

## 📊 Estructura del Proyecto

```
granja-avicola/
├── app/
│   ├── Http/Controllers/     # 9 controladores CRUD
│   └── Models/               # 9 modelos con relaciones
├── database/
│   ├── migrations/           # 9 tablas + sistema
│   └── seeders/              # Datos de prueba
├── resources/
│   └── views/                # 45+ vistas Blade (Mobile First)
├── routes/
│   └── web.php               # Rutas principales
├── docker-entrypoint.sh      # Script de inicio Docker
└── Dockerfile                # Configuración Docker
```

## 🎯 Funcionalidades Clave

### Dashboard Principal
- Resumen de galpones activos
- Métricas de producción diaria
- Alertas de stock de alimento
- Lotes próximos a venta (120 días)
- Ponedoras próximas a descarte (36 meses)

### Producción de Huevos
- Registro rápido diario
- Cálculo automático de merma (10%)
- Tarificador inteligente por docena
- Historial por galpón

### Pollos de Engorde
- Contador automático de días transcurridos
- Alerta de lotes listos para venta (120 días)
- Control de consumo de alimento
- Registro de ventas por kg

### Control Financiero
- Ventas consolidadas con totales
- Cálculo automático de pagos a operarios
- Control de compras de alimento
- Reportes por período

## 👥 Gestión de Personal
- Registro de operarios con DNI único
- Control de asistencia diaria
- Cálculo automático de pagos (S/80/día)
- Reporte de asistencias por operario

## 📱 Diseño Mobile First
- Interfaz optimizada para móviles
- Tailwind CSS responsive
- Navegación simple e intuitiva
- Iconos visuales para mejor UX

## 🔒 Seguridad
- Variables de entorno para credenciales
- HTTPS forzado en producción
- Validación de datos en backend
- Soft deletes para integridad de datos

## 📈 Optimizaciones de Producción
- Cache de configuración, rutas y vistas
- Compresión de assets con Vite
- Migraciones y seeders idempotentes
- Consultas optimizadas con Eager Loading

## 🐛 Soporte
Para reportar issues o solicitar features:
- GitHub Issues: https://github.com/Machi1811/granja-avicola/issues

## 📄 Licencia
Proyecto MVP - Sistema de Gestión Granja Avícola © 2026

---

**Desarrollado con ❤️ para la gestión eficiente de granjas avícolas**

**Deploy en producción**: https://granja-avicola.onrender.com
