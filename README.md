# CS06: Object-Oriented Programming — Restaurant Ordering System

**Group 08 (CS B)**  
* **Members:**
  * AGUILAR, Jhanns Bernard Abancio
  * BINASING, Alheqma Pangalinon
  * MAUDO, Rose Anne Berico

---

## 1. Project Overview
The **Restaurant Ordering System** is a database-driven web application built with Laravel to manage restaurant operations, including menu catalog management, dine-in and takeout order processing, payment handling, operational reporting, user role management, and audit logging.

This project fulfills the **CS06: Object-Oriented Programming** final project requirements by demonstrating **Encapsulation**, **Inheritance**, **Polymorphism**, and **Abstraction** within application-specific business logic alongside relational database design and automated testing.

---

## 2. Technology Stack
* **Backend Framework:** PHP 8.3 & Laravel (MVC Architecture)
* **Frontend & Reactivity:** Blade Templates, Tailwind CSS, & Livewire (Single-File Components)
* **Authentication:** Laravel Fortify (Login, Registration, Password Reset)
* **Database:** MySQL / MariaDB
* **Testing:** Pest / PHPUnit
* **Version Control:** Git & GitHub

---

## 3. Setup & Development Log

### Phase 1: Project Initialization & Authentication
1. **Laravel Installation:** Created a fresh Laravel project configured with the **Livewire starter kit** (single-file components) and **Laravel's built-in authentication** (powered by Laravel Fortify).
2. **Auth Verification:** Verified active login/registration routes (`php artisan route:list --path=login`) and tested initial user registration against the local database.
3. **Git & GitHub Initialization:**
   * Configured local Git user identity and committed the base project scaffold (`b8edf3f`).
   * Standardized the primary branch to `main` (`git branch -M main`).
   * Connected the local repository to `origin` (`https://github.com/Ayreej17/OOP_Final_Project.git`) and pushed the initial commit (`git push -u origin main`).

---

### Phase 2: Relational Database Schema Design
To support the required functional modules and business rules, seven database migrations were created and structured:

| Table | Purpose & Module Support | Key Columns |
| :--- | :--- | :--- |
| **`users`** *(updated)* | **User Management:** Supports role-based access control and account activation/deactivation. | `role` (`admin`, `staff`), `is_active` (`boolean`) |
| **`categories`** | **Master Data:** Organizes menu items for search and category filtering. | `id`, `name`, `description`, `timestamps` |
| **`menu_items`** | **Master Data & Encapsulation:** Tracks item pricing, availability, and stock levels. | `id`, `category_id`, `name`, `price`, `stock`, `is_available`, `timestamps` |
| **`orders`** | **Transactions & Inheritance:** Tracks dine-in/takeout orders, discount types, totals, and order lifecycle states. | `id`, `order_number`, `user_id`, `order_type`, `table_number`, `status`, `discount_type`, `subtotal`, `discount_amount`, `total_amount`, `timestamps` |
| **`order_items`** | **Transactions:** Pivot table preserving historical `unit_price` and line subtotals per order. | `id`, `order_id`, `menu_item_id`, `quantity`, `unit_price`, `subtotal`, `timestamps` |
| **`payments`** | **Transactions & Polymorphism:** Records payment methods, amounts tendered, change, and reference numbers. | `id`, `order_id`, `payment_method`, `amount_paid`, `change_amount`, `reference_number`, `status`, `timestamps` |
| **`audit_logs`** | **Audit Trail:** Tracks critical system actions (cancellations, price changes, user status updates). | `id`, `user_id`, `action`, `model_type`, `model_id`, `description`, `timestamps` |

*(Note: Standard Laravel `timestamps()` (`created_at` and `updated_at`) are included across tables to support date-filtered operational reports, chronological order tracking, and audit log timestamps.)*

---

## 4. Planned Module & Team Task Allocation
Once the shared database schema, models, and seeders are finalized on `main`, development is divided across feature branches to maintain clear Git contribution records:

* **Member 1 (`feature/orders-transactions`) — Jhanns Bernard Aguilar:**
  * **Modules:** Transactions & Business Rules
  * **Scope:** POS order creation, end-to-end checkout workflow, custom OOP domain/service classes (Encapsulation, Inheritance, Polymorphism, Abstraction), and automated Pest business-rule tests.
* **Member 2 (`feature/users-menu`) — Alheqma Binasing:**
  * **Modules:** User Management & Master Data
  * **Scope:** User account management (roles, profiles, activate/deactivate) and Categories/Menu Items CRUD with search, filtering, and pagination.
* **Member 3 (`feature/reports-audit`) — Rose Anne Maudo:**
  * **Modules:** Operational Reports & Audit Trail
  * **Scope:** Two operational reports (Sales Summary & Top-Selling Items with date filters and printable views) and the Audit Trail log viewer.

---

## 5. Local Installation Instructions (For Group Members)
1. Clone the repository:
   ```bash
   git clone https://github.com/Ayreej17/OOP_Final_Project.git
   cd OOP_Final_Project
   ```
2. Install PHP and Node dependencies:
   ```bash
   composer install
   npm install
   ```
3. Configure environment variables:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *(Update `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD` in `.env` to match your local MySQL/MariaDB setup.)*
4. Run database migrations and seeders:
   ```bash
   php artisan migrate --seed
   ```
5. Start the development servers:
   ```bash
   composer run dev
   ```

---

## 6. Academic Integrity & AI Assistance Disclosure
In compliance with **Section 11 (Academic Integrity and Group Policies)** of the CS06 Final Project Guidelines, the group discloses that an AI assistant (Gemini) was consulted during development for architectural planning, Git workflow guidance, database schema design review, and documentation formatting. All application code, OOP implementations, migrations, and tests are reviewed, understood, and authored/defended by the group members.