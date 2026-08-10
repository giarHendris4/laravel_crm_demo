# Custom CRM & Lead Management System (Laravel)

A multi-tenant role-based Lead Distribution & CRM System built with Laravel & Tailwind CSS. Designed for managing sales pipelines, partner companies, and lead assignments.

# Key Features
- **Role-Based Access Control (RBAC):** Dedicated portals for Administrators, Sales Representatives, and Partner Companies.
- **Lead Distribution:** Manual lead assignment from Admin to single or multiple Partners.
- **Strict Data Isolation:** Custom Laravel Authorization Policies ensuring Partner Companies only access assigned leads.
- **Interactive Analytics Dashboard:** Real-time summary cards and sales conversion reports.

# Tech Stack
- **Backend:** Laravel 13 php(8.2+)
- **Frontend:** Blade, Tailwind CSS, Alpine.js
- **Database:** PostgreSQL / MySQL
- **Authentication:** Laravel Breeze & Custom Middleware

# Demo Credentials (For Testing)
| Role | Email | Password |
| :--- | :--- | :--- |
| **Admin** | admin@crm-demo.com | password |
| **Sales Rep** | sales@crm-demo.com | password |
| **Partner** | partner@crm-demo.com | password |