<p align="center">
    <h1 align="center">💰 App Finanzas</h1>
    <p align="center">Aplicación web de gestión de finanzas personales con Arquitectura Limpia</p>
</p>

<p align="center">
    <a href="https://laravel.com"><img src="https://img.shields.io/badge/Laravel-13.x-FF2D20?style=flat-square&logo=laravel" alt="Laravel"></a>
    <a href="https://php.net"><img src="https://img.shields.io/badge/PHP-8.3+-777BB4?style=flat-square&logo=php" alt="PHP"></a>
    <a href="https://livewire.laravel.com"><img src="https://img.shields.io/badge/Livewire-3.6-FB2A45?style=flat-square&logo=livewire" alt="Livewire"></a>
    <a href="https://tailwindcss.com"><img src="https://img.shields.io/badge/Tailwind-3.x-06B6D4?style=flat-square&logo=tailwindcss" alt="Tailwind CSS"></a>
    <a href="https://www.sqlite.org"><img src="https://img.shields.io/badge/SQLite-3-003B57?style=flat-square&logo=sqlite" alt="SQLite"></a>
</p>

---

## Sobre el Proyecto

**App Finanzas** es una aplicación web para la gestión de finanzas personales. Permite a los usuarios administrar cuentas bancarias, registrar transacciones (ingresos/gastos), categorizar gastos, configurar presupuestos y visualizar reportes gráficos en un dashboard interactivo.

### Características Principales

- **Dashboard financiero** — Saldo total, ingresos/gastos mensuales, tasa de ahorro, gráfica de 6 meses, distribución por categoría, presupuestos activos
- **Gestión de transacciones** — CRUD completo de ingresos y gastos con fechas, montos, categorías y descripciones
- **Multi-cuenta** — Soporte para múltiples cuentas bancarias con diferentes monedas
- **Sistema de categorías** — Categorías personalizables con nombre, ícono y color; únicas por usuario/tipo
- **Presupuestos** — Límites de gasto por categoría con rangos de fecha y barras de progreso
- **Personalización** — Color del tema, colores de gráficas, nombre de la app, logo (admin controla lo global)
- **Modo oscuro** — Toggle con efecto de animación de gota de agua
- **Diseño responsive** — Sidebar en desktop, navegación inferior en móvil
- **Roles** — Admin y usuario regular; el admin gestiona configuración global

## Stack Tecnológico

| Componente | Tecnología |
|---|---|
| Backend | Laravel 13.x, PHP 8.3+ |
| Frontend | Livewire 3.6, Alpine.js 3.x, Tailwind CSS 3.x |
| Build | Vite 8.x |
| Base de datos | SQLite |
| Auth | Laravel Breeze (Livewire) |
| Arquitectura | Repository Pattern + Service Layer |
| Contenedores | Docker (PHP 8.3-FPM + Nginx Alpine) |

## Estructura del Proyecto

```
app-finanzas/
├── app/
│   ├── Http/
│   │   ├── Controllers/          # CategoryController, TransactionController
│   │   └── Requests/             # Form Requests (Store/Update Category, Store/Update Transaction)
│   ├── Livewire/
│   │   ├── Actions/              # Logout
│   │   ├── Forms/                # LoginForm
│   │   └── Components/           # Dashboard, ExpenseList, RegisterTransaction, SettingsPage
│   ├── Models/                   # User, Account, Categories, Currencies, Transaction, Budget, Setting
│   ├── Repositories/
│   │   ├── Eloquent/             # Implementaciones con Eloquent
│   │   └── Interfaces/           # Contratos del repositorio
│   └── Services/                 # TransactionService, CategoryService, SettingService
├── database/
│   ├── factories/                # UserFactory
│   ├── migrations/               # 12 migraciones
│   └── seeders/                  # DatabaseSeeder, GlobalSettingsSeeder
├── docker/nginx/                 # Configuración de Nginx
├── resources/
│   ├── views/
│   │   ├── components/           # 19 componentes Blade (sidebar, bottom-nav, theme-toggle, etc.)
│   │   ├── layouts/              # app.blade.php, guest.blade.php
│   │   ├── livewire/             # Componentes Livewire + páginas de auth
│   │   └── web/                  # welcome, dashboard, gastos, profile, settings
│   ├── css/app.css               # Tailwind + animaciones personalizadas
│   ├── js/app.js                 # Sidebar, persistencia de tema
│   └── js/animacion.js           # Efecto de gota de agua para dark mode
├── tests/                        # Feature (Auth, Profile) + Unit tests
├── compose.yaml                  # Docker Compose
├── dockerfile                    # PHP 8.3-FPM + Node 20
└── .env.example
```

## Base de Datos

| Tabla | Descripción |
|---|---|
| `users` | Usuarios con campo `role` (admin/user) |
| `accounts` | Cuentas bancarias por usuario con moneda y saldo |
| `categories` | Categorías de ingreso/gasto con ícono y color |
| `transactions` | Transacciones (UUID PK) con monto, tipo, fecha |
| `budgets` | Presupuestos por categoría con rango de fechas |
| `settings` | Configuración por usuario + global (user_id=null) |
| `currencies` | Catálogo de monedas |

## Instalación

### Requisitos

- PHP 8.3+
- Composer
- Node.js 20+ / pnpm
- SQLite (incluido en PHP por defecto)

### Instalación Local

```bash
# 1. Clonar el repositorio
git clone <url-repositorio>
cd app-finanzas

# 2. Instalar dependencias
composer install
pnpm install

# 3. Configurar entorno
cp .env.example .env
php artisan key:generate

# 4. Crear base de datos SQLite
touch database/database.sqlite

# 5. Ejecutar migraciones y seeders
php artisan migrate --seed

# 6. Compilar assets
pnpm run build

# 7. Iniciar servidor
php artisan serve
```

La app estará disponible en `http://localhost:8000`.

### Usando el script de setup

```bash
composer setup
```

Esto ejecuta: `install`, `.env`, `key:generate`, `migrate`, `npm install`, `npm build`.

### Usando Docker

```bash
docker compose up -d
```

La app estará en `http://localhost:8000` y Vite en `http://localhost:5173`.

## Desarrollo

```bash
# Ejecutar todo concurrently (servidor, queue, pail, vite)
composer dev

# O manualmente en terminales separadas
php artisan serve
php artisan queue:listen
php artisan pail
pnpm run dev
```

## Usuarios de Prueba

| Email | Rol | Contraseña |
|---|---|---|
| `test@example.com` | Usuario | `password` |
| `admin@app-finanzas.test` | Admin | `password` |

## Testing

```bash
# Limpiar caché y ejecutar tests
composer test

# O directamente
php artisan test
```

Los tests usan SQLite in-memory para aislamiento y velocidad.

## Arquitectura

El proyecto sigue **Arquitectura Limpia** con separación en capas:

- **Controllers** — Reciben HTTP, validan con Form Requests, delegan a Services
- **Services** — Contienen la lógica de negocio
- **Repositories** — Acceso a datos con patrón Repository (Interface + Eloquent)
- **Models** — Eloquent ORM con relaciones y scopes
- **Livewire Components** — UI reactiva server-driven

## Comandos Útiles

| Comando | Descripción |
|---|---|
| `composer setup` | Instalación completa del proyecto |
| `composer dev` | Ejecutar todos los servicios de desarrollo |
| `composer test` | Ejecutar suite de tests |
| `php artisan migrate` | Ejecutar migraciones pendientes |
| `php artisan migrate:fresh --seed` | Recrear DB con seeders |
| `php artisan db:seed --class=GlobalSettingsSeeder` | Insertar configuración global |
| `pnpm run build` | Compilar assets para producción |
| `pnpm run dev` | Compilar con hot-reload (Vite) |

## License

MIT License
