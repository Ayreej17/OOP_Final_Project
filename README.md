# CS06: Object-Oriented Programming — Restaurant Ordering System

**Group 08 (CS B)**
* **Members:** *(To be added)*

## 1. Project Overview
A database-driven web application built with Laravel to manage restaurant operations, including menu management, dine-in and takeout orders, payments, operational reports, user roles, and audit logging.

## 2. Tech Stack
* **Backend:** PHP 8.3 & Laravel
* **Frontend:** Blade, Tailwind CSS, & Livewire (Single-File Components)
* **Authentication:** Laravel Fortify
* **Database:** MySQL / MariaDB
* **Testing:** Pest

## 3. Progress Log

### Phase 1: Project Initialization & Git Setup
* Initialized a fresh Laravel project with the Livewire starter kit and built-in authentication.
* Verified authentication routes and initial user registration.
* Configured Git, renamed the primary branch to `main`, and pushed the initial repository to GitHub.

### Phase 2: Database Schema Design
Created migrations for the core system tables:
* **`users`** *(updated)*: Added `role` (`admin`, `staff`) and `is_active` (`boolean`).
* **`categories`**: Stores menu categories (`name`, `description`).
* **`menu_items`**: Stores menu items (`category_id`, `name`, `description`, `price`, `stock`, `is_available`).
* **`orders`**: Tracks orders (`order_number`, `user_id`, `order_type`, `table_number`, `status`, `discount_type`, `subtotal`, `discount_amount`, `total_amount`).
* **`order_items`**: Pivot table linking orders and menu items (`order_id`, `menu_item_id`, `quantity`, `unit_price`, `subtotal`).
* **`payments`**: Records order payments (`order_id`, `payment_method`, `amount_paid`, `change_amount`, `reference_number`, `status`).
* **`audit_logs`**: Logs critical system events (`user_id`, `action`, `model_type`, `model_id`, `description`).

## 4. Planned Modules
1. **User Management:** Role-based access control and account activation/deactivation.
2. **Master Data:** Categories and Menu Items CRUD with search, filtering, and pagination.
3. **Transactions & Business Rules:** End-to-end order processing, payment handling, and OOP domain logic.
4. **Operational Reports:** Sales summary and top-selling items reports with date filters and printable views.
5. **Audit Trail:** Activity log viewer for critical system events.

## 5. Local Setup Instructions
1. Clone the repository and enter the directory:
   ```bash
   git clone https://github.com/Ayreej17/OOP_Final_Project.git
   cd OOP_Final_Project
   ```
2. Install dependencies:
   ```bash
   composer install && npm install
   ```
3. Set up environment and app key:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
4. Configure database credentials in `.env`, then run migrations and seeders:
   ```bash
   php artisan migrate --seed
   ```
5. Start the local development server:
   ```bash
   composer run dev
   ```

## 6. AI Assistance Disclosure
In accordance with course academic integrity guidelines, an AI assistant (Gemini) was used during development for architectural planning, Git setup guidance, database schema review, and documentation formatting. All final code, OOP implementations, and tests are reviewed, understood, and maintained by the group.