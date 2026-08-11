# Custom CRM & Lead Management System (Laravel)

# Features
- Role-Based Access Control (RBAC)
- Lead Distribution (manual assignment)
- Strict Data Isolation
- Interactive Analytics Dashboard

# Tech Stack
- Backend: Laravel 13, PHP 8.3+
- Frontend: Blade, Tailwind CSS, Alpine.js
- Database: PostgreSQL / MySQL
- Authentication: Laravel Breeze & Custom Middleware

# Installation
```bash
git clone git@github.com:giarHendris4/laravel_crm_demo.git
cd repo
composer install
npm install && npm run dev
cp .env.example .env
php artisan migrate --seed
php artisan serve


# Demo Credentials (For Testing)
| Role | Email | Password |
| :--- | :--- | :--- |
| **Admin** | admin@crm-demo.com | password |
| **Sales Rep** | sales@crm-demo.com | password |
| **Partner** | partner@crm-demo.com | password |