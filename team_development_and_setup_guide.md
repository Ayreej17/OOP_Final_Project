# Restaurant Ordering System — Team Development & Setup Guide

This document explains everything currently built in the repository, why certain architectural choices were made, and how our team will collaborate moving forward.

---

## 1. Project Foundation & Authentication

### What Was Done
* Installed a fresh **Laravel** application using the **Livewire starter kit** with **Single-File Components** and **Laravel's built-in authentication (Fortify)**.
* Verified authentication routes (`/login`, `/register`) and confirmed database connectivity by creating an initial test user.
* Selected **Pest** as the testing framework to write automated business-rule tests later.

### Why We Did It This Way
* **No Laravel Breeze Needed:** The Livewire starter kit already includes Blade, Tailwind CSS, and Laravel Fortify out of the box. Installing Breeze on top of it would cause duplicate routes and view conflicts.
* **Lightweight Auth:** Optional features like Two-Factor Authentication (2FA) and Email Verification were skipped because authentication does not count toward our five required functional modules. Keeping auth simple lets us focus on our **User Management** module and custom OOP classes.

---

## 2. Git & GitHub Workflow

### What Was Done
* Initialized Git locally, configured user credentials, and created the initial commit.
* Renamed the default branch from `master` to `main` (`git branch -M main`).
* Connected our local repository to GitHub (`git remote add origin ...`) and linked the upstream tracking branch (`git push -u origin main`).

### How We Will Collaborate
* **`main` Branch:** Holds our stable, shared foundation (migrations, models, seeders, and tested features).
* **Feature Branches:** Once our shared models and seeders are pushed, each teammate should create their own branch (e.g., `git checkout -b feature/module-name`) before writing code.
* **Individual Commits:** Every teammate must commit and push their own work regularly so GitHub accurately records individual contributions for grading.

---

## 3. Database Schema & Migrations

We generated **7 migrations** (1 table update + 6 new business tables) to satisfy the minimum 5 business tables and support all required modules.

### Table Breakdown & Purpose
1. **`users` (`add_role_and_status_to_users_table`)**
   * **Columns Added:** `role` (defaults to `'staff'`, can be `'admin'`) and `is_active` (boolean, defaults to `true`).
   * **Purpose:** Supports the **User Management** module—allowing Admins to assign roles and activate/deactivate staff accounts without deleting historical records.
2. **`categories`**
   * **Columns:** `id`, `name` (unique), `description`, `timestamps`.
   * **Purpose:** Groups menu items (e.g., Main Course, Drinks, Desserts) for **Master Data** filtering.
3. **`menu_items`**
   * **Columns:** `id`, `category_id` (foreign key), `name`, `description`, `price`, `stock`, `is_available`, `timestamps`.
   * **Purpose:** Stores restaurant items for **Master Data** CRUD and search. The `stock` and `is_available` columns will be protected by **Encapsulation** rules so out-of-stock items cannot be ordered.
4. **`orders`**
   * **Columns:** `id`, `order_number` (unique), `user_id` (foreign key), `order_type` (`'dine_in'` or `'takeout'`), `table_number`, `status` (`'pending'`, `'preparing'`, `'completed'`, `'cancelled'`), `discount_type`, `subtotal`, `discount_amount`, `total_amount`, `timestamps`.
   * **Purpose:** Tracks the core **Transaction** lifecycle, enforces valid status transitions, and supports **Inheritance** (e.g., Dine-In vs. Takeout or Discount types).
5. **`order_items`**
   * **Columns:** `id`, `order_id` (foreign key), `menu_item_id` (foreign key), `quantity`, `unit_price`, `subtotal`, `timestamps`.
   * **Purpose:** Pivot table connecting orders and menu items. Storing `unit_price` here freezes the price at the moment of purchase so future menu price updates do not alter past sales reports.
6. **`payments`**
   * **Columns:** `id`, `order_id` (foreign key), `payment_method` (`'cash'`, `'gcash'`, `'card'`), `amount_paid`, `change_amount`, `reference_number`, `status`, `timestamps`.
   * **Purpose:** Completes the end-to-end transaction and demonstrates **Abstraction & Polymorphism** via different payment processor classes.
7. **`audit_logs`**
   * **Columns:** `id`, `user_id` (nullable foreign key), `action`, `model_type`, `model_id`, `description`, `timestamps`.
   * **Purpose:** Powers the **Audit Trail** module by recording critical actions (such as order cancellations, menu price updates, and user status changes).

### Why Every Table Has `$table->timestamps()`
* **Eloquent Default:** Laravel automatically manages `created_at` and `updated_at` whenever models are created or updated.
* **Reports Module:** Date-filtered sales and top-selling item reports rely directly on `orders.created_at` and `payments.created_at`.
* **Audit Trail & Kitchen Queue:** Tracks exact event times in `audit_logs` and lets staff process orders in chronological order.

---

## 4. What Comes Next (Before Splitting Into Feature Branches)

Before we split up the five modules among the three of us, we still need to finish two shared setup steps on `main`:
1. **Configure Eloquent Models:** Define `$fillable` properties and relationships (`hasMany`, `belongsTo`) inside `User`, `Category`, `MenuItem`, `Order`, `OrderItem`, `Payment`, and `AuditLog`.
2. **Create Database Seeders:** Seed sample Admin/Staff accounts, food categories, and menu items so everyone has identical test data locally (`php artisan migrate:seed`).

---

## 5. Quick Reference: Getting Started Locally

If you are pulling this repository for the first time:
1. Run `git pull origin main` to get the latest updates.
2. Make sure your local MySQL/MariaDB server is running and `.env` matches your database credentials.
3. Run `php artisan migrate` to create all tables in your local database.
4. Run `composer run dev` to start both Laravel and Vite.