# Custom CRM & Lead Management System (Laravel)

# Features
- Role-Based Access Control (RBAC)
- Lead Distribution (manual assignment)
- Strict Data Isolation
- Interactive Analytics Dashboard
- Laporan & Export (Excel/CSV) dengan periode Harian / Mingguan / Custom

# Tech Stack
- Backend: Laravel 13, PHP 8.3+
- Frontend: Blade, Tailwind CSS, Alpine.js
- Database: SQLite (default) / MySQL / PostgreSQL
- Authentication: Laravel Breeze & Custom Middleware

# Installation
```bash
git clone git@github.com:giarHendris4/laravel_crm_demo.git
cd laravel_crm_demo/crm
# Catatan: aplikasi berada di subfolder `crm/` (lokasi composer.json).

composer install

cp .env.example .env
php artisan key:generate

# Jika memakai SQLite (default), pastikan file database tersedia:
touch database/database.sqlite

npm install && npm run build

php artisan migrate --seed
php artisan serve
```
> Catatan: `npm run build` digunakan agar aset Tailwind/JS ter-compile untuk produksi.
> Untuk pengembangan dengan hot-reload, jalankan `npm run dev` di terminal terpisah.
> Jika ingin memakai MySQL/PostgreSQL, sesuaikan `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` di `.env`.

# Demo Credentials (For Testing)
| Role | Email | Password |
| :--- | :--- | :--- |
| **Admin** | admin@crm-demo.com | password |
| **Sales Rep** | sales@crm-demo.com | password |
| **Partner** | partner@crm-demo.com | password |
