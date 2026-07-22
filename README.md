# 🐔 Sistema de Gestión de Granja Avícola - MVP

Sistema completo de gestión operativa y financiera para granjas avícolas artesanales de ponedoras y pollos de engorde.

## 📋 Descripción

Este MVP automatiza la gestión operativa y financiera de una granja avícola artesanal de ponedoras y pollos de engorde, implementando de forma estricta las fórmulas matemáticas de consumo de alimento, mermas de producción, precios de venta por escala, ciclos de descarte por tiempo y control de pagos diarios a operarios.

## ✨ Características Principales

### Requerimientos Funcionales Implementados

#### RF-01: Control de Inventario de Ponedoras y Mermas
- Registro diario de aves ponedoras activas (población inicial de 500)
- **Cálculo automático de merma del 10%** en producción de huevos
- Ejemplo: 500 aves producen 450 huevos netos (restando 10% de merma)

#### RF-02: Conversión Automática de Alimento
- **Consumo por ave: 120 gramos** de alimento de postura al día
- Conversión automática: gramos → kilogramos → quintales (1 quintal = 46 kg)
- **Alerta automática** cuando quedan menos de 2 quintales de reserva

#### RF-03: Tarificador de Venta de Aves (Ponedoras)
- **1 a 12 gallinas: S/. 35.00** por unidad
- **Más de 12 gallinas: S/. 30.00** por unidad (precio de mayorista)
- Cálculo automático según cantidad

#### RF-04: Control de Pollos de Engorde por Camadas
- Gestión de lotes con fecha de ingreso
- **Ciclo de vida exacto de 120 días**
- Marcado automático como "Listo para Venta" al completar el ciclo
- Registro de consumo diario y venta final por peso en kg

#### RF-05: Alerta de Descarte de Galpones
- Control automático de antigüedad (**límite: 36 meses**)
- Bloqueo de producción al superar el límite
- Habilitación de venta como "Carne de Gallina"

#### RF-06: Control de Jornales y Personal
- Registro de asistencia diaria
- **Pago fijo: S/. 80.00** por día trabajado
- Sin beneficios sociales (régimen informal/artesanal)
- Cálculo automático: Total = Días_Trabajados × 80

### Requerimientos No Funcionales

#### RNF-02: Interfaz Mobile First
Formularios de registro diario optimizados para uso en campo desde teléfonos móviles con botones grandes y sin campos complicados.

#### RNF-03: Tareas Programadas
Sistema de alertas automáticas mediante Cronjobs diarios:
- Actualización de ciclo de 120 días para pollos de engorde
- Verificación de galpones de 36 meses para descarte
- Alerta de stock de alimento menor a 2 quintales

## 🛠️ Stack Tecnológico

- **Backend:** Laravel 11
- **Frontend:** Blade Templates + Tailwind CSS 4
- **Base de Datos:** PostgreSQL (producción) / SQLite (desarrollo)
- **Build Tool:** Vite
- **Despliegue:** Render.com

## 📦 Instalación Local

### Requisitos Previos

- PHP 8.2+
- Composer
- Node.js 18+
- PostgreSQL o SQLite

### Pasos de Instalación

```bash
# 1. Clonar el repositorio
git clone <url-del-repositorio>
cd granja-avicola

# 2. Instalar dependencias PHP
composer install

# 3. Instalar dependencias Node
npm install

# 4. Copiar archivo de entorno
cp .env.example .env

# 5. Generar application key
php artisan key:generate

# 6. Configurar base de datos en .env
# Para desarrollo con SQLite (default):
DB_CONNECTION=sqlite

# 7. Crear base de datos SQLite
touch database/database.sqlite

# 8. Ejecutar migraciones y seeders
php artisan migrate:fresh --seed

# 9. Compilar assets
npm run dev

# 10. Iniciar servidor
php artisan serve
```

Acceder a: `http://localhost:8000`

## 🚀 Despliegue en Render

Ver documentación completa en [DEPLOY.md](DEPLOY.md)

### Resumen Rápido

1. Conecta tu repositorio a Render
2. Render detectará automáticamente `render.yaml`
3. Configura las variables de entorno
4. El despliegue se realiza automáticamente

## 📱 Uso del Sistema

### Dashboard Principal
- Vista general de alertas críticas
- Estadísticas en tiempo real
- Accesos rápidos a funciones principales

### Registro Rápido Diario
1. **Producción de Huevos:** Registrar aves activas y producción (merma 10% automática)
2. **Asistencia de Operarios:** Marcar presentes/ausentes (pago S/80 automático)

### Módulos Principales
- 🏠 **Galpones:** Gestión de instalaciones (alerta 36 meses)
- 🥚 **Producción:** Control diario de huevos
- 🐥 **Pollos de Engorde:** Seguimiento de ciclo 120 días
- 🌾 **Alimentos:** Control de stock (alerta < 2 quintales)
- 💰 **Ventas:** Registro con tarificador automático
- 👷 **Operarios:** Gestión de personal y asistencia

## ⏰ Comandos Artisan

```bash
# Actualizar ciclos de pollos (ejecutar diariamente)
php artisan pollos:actualizar-ciclos

# Verificar galpones para descarte (ejecutar diariamente)
php artisan galpones:verificar-descarte

# Verificar stock de alimentos (ejecutar 2x al día)
php artisan alimentos:verificar-stock

# Ver tareas programadas
php artisan schedule:list

# Ejecutar scheduler manualmente
php artisan schedule:run
```

## 📊 Datos de Prueba

El sistema incluye seeders con datos realistas:

- **4 Galpones:** 3 activos, 1 en descarte
- **495 Ponedoras:** Distribuidas en 3 galpones
- **2 Tipos de Alimento:** Postura (1.5qq - alerta) y Engorde (8qq - OK)
- **3 Operarios:** Con historial de asistencia de 7 días
- **5 Lotes de Pollos:** En diferentes etapas del ciclo de 120 días

## 🔐 Seguridad

- SoftDeletes en todos los modelos (auditoría)
- Validación de datos en servidor
- Protección CSRF en formularios
- Variables de entorno para configuración sensible

## 📝 Estructura del Proyecto

```
granja-avicola/
├── app/
│   ├── Console/Commands/      # Comandos de alertas automáticas
│   ├── Http/Controllers/      # Controladores con lógica de negocio
│   └── Models/                # Modelos con cálculos automáticos
├── database/
│   ├── migrations/            # Esquema de base de datos
│   └── seeders/               # Datos iniciales
├── resources/
│   ├── css/                   # Estilos Tailwind
│   ├── js/                    # JavaScript frontend
│   └── views/                 # Vistas Blade (Mobile First)
├── routes/
│   ├── web.php               # Rutas de la aplicación
│   └── console.php           # Scheduler configurado
├── render.yaml               # Configuración Render
├── render-build.sh           # Script de build
└── DEPLOY.md                 # Guía de despliegue
```

## 🐛 Troubleshooting

### Error: "Class not found"
```bash
composer dump-autoload
php artisan config:clear
```

### Error en migraciones
```bash
php artisan migrate:fresh --seed
```

### Assets no se cargan
```bash
npm run build
php artisan view:clear
```

## 📄 Licencia

Este proyecto es propietario y fue desarrollado para gestión de granja avícola artesanal.

---

**Versión:** MVP 1.0  
**Fecha:** Julio 2026  
**Plazo de Desarrollo:** 10 días (Sprint MVP)
