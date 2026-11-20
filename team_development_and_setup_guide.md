# Restaurant Ordering System — Team Development & Collaboration Guide

This document tracks everything completed in the repository, explains our architectural choices, outlines our Git branching workflow, and logs team collaborations for our CS06 Final Project.

## 1. Completed Foundation & Setup

### Phase 1: Project Initialization & Authentication
* Installed a fresh **Laravel** application using the **Livewire starter kit** with **Single-File Components** and **Laravel's built-in authentication (Fortify)**.
* Verified authentication routes (`/login`, `/register`) and confirmed database connectivity with an initial test user.
* Configured **Pest** as our testing framework for upcoming automated business-rule tests.
* **Why we did this:** The Livewire starter kit already provides Blade, Tailwind CSS, and Fortify authentication out of the box, avoiding route conflicts from extra packages like Breeze while keeping our codebase lean and defensible.

### Phase 2: Repository & Team Collaborator Setup
* Initialized Git locally, renamed the primary branch to `main`, and connected the remote repository (`https://github.com/Ayreej17/OOP_Final_Project.git`).
* Added project documentation (`README.md` and `docs/DEVELOPMENT_GUIDE.md`).
* Added all three group members as **collaborators** on the GitHub repository so individual commits and pull requests are tracked under each member's GitHub account.

### Phase 3: Database Schema & Migrations
Created and structured **7 migrations** (1 table update + 6 new business tables) to satisfy the minimum 5 business tables and support all required modules:
1. **`users` (`add_role_and_status_to_users_table`)**: Added `role` (`'admin'`, `'staff'`) and `is_active` (`boolean`) to support User Management and role-based access control.
2. **`categories`**: Stores menu categories (`name`, `description`) for Master Data filtering.
3. **`menu_items`**: Stores items (`category_id`, `name`, `description`, `price`, `stock`, `is_available`) for Master Data CRUD and Encapsulation stock protection.
4. **`orders`**: Tracks transactions (`order_number`, `user_id`, `order_type`, `table_number`, `status`, `discount_type`, `subtotal`, `discount_amount`, `total_amount`) for Inheritance and state-transition business rules.
5. **`order_items`**: Pivot table linking orders and menu items (`order_id`, `menu_item_id`, `quantity`, `unit_price`, `subtotal`), freezing `unit_price` at purchase time.
6. **`payments`**: Stores payment records (`order_id`, `payment_method`, `amount_paid`, `change_amount`, `reference_number`, `status`) for Polymorphism and Abstraction.
7. **`audit_logs`**: Logs critical system events (`user_id`, `action`, `model_type`, `model_id`, `description`) for the Audit Trail module.

## 2. Team Workflow & Module Division

To ensure clean collaboration and verifiable Git contributions for every member, we follow a **Feature Branch Workflow**:

1. **Sync with `main`:** Before starting any task, run `git pull origin main`.
2. **Work on Assigned Feature Branches:** Never push unfinished code directly to `main`. Each member works on their dedicated branch:
   * **`feature/orders-oop` (Member 1):** Transactions & Business Rules — POS Order screen, Checkout/Payment processing, custom OOP classes (Encapsulation, Inheritance, Polymorphism, Abstraction), and Pest tests.
   * **`feature/users-menu` (Member 2):** User Management & Master Data — Role-based Gates/Middleware, User CRUD + activate/deactivate, Categories & Menu Items CRUD with search, filtering, and pagination.
   * **`feature/reports-audit` (Member 3):** Operational Reports & Audit Trail — Sales Summary and Top-Selling Items reports (with date filters and printable view), plus the Audit Trail viewer and logging service.
3. **Commit & Pull Request:** Commit frequently with clear messages (`git commit -m "Implement ..."`), push to your feature branch, and open a Pull Request on GitHub to merge completed features into `main`.

## 3. Immediate Next Steps (Shared Foundation)
Before splitting into feature branches, we are completing two final shared setup items on `main`:
* [ ] Configure `$fillable` properties and Eloquent relationships (`hasMany`, `belongsTo`) across all 7 models.
* [ ] Build and run `DatabaseSeeder` to populate default Admin/Staff test accounts, categories, and menu items.

## 4. Collaboration & Contribution Log
*(Update this table as milestones and pull requests are completed)*

| Date | Milestone / Activity | Branch | Collaborators Involved | Status |
| :--- | :--- | :--- | :--- | :--- |
| Nov 2026 | Initial Laravel setup, GitHub repo creation, & 7 database migrations | `main` | Member 1 | Completed |
| Nov 2026 | Added teammates as GitHub collaborators & established team workflow | `main` | All Members | Completed |
| TBD | Eloquent model relationships & database seeders | `main` | All Members | In Progress |
| TBD | User Management & Menu Master Data modules | `feature/users-menu` | Member 2 | Pending |
| TBD | POS Ordering, Payments, & Core OOP classes | `feature/orders-oop` | Member 1 | Pending |
| TBD | Operational Reports & Audit Trail modules | `feature/reports-audit` | Member 3 | Pending |

## 5. Local Onboarding Checklist for Collaborators
When cloning or pulling the repository for the first time:
1. Clone the repo: `git clone https://github.com/Ayreej17/OOP_Final_Project.git`
2. Install PHP & JS packages: `composer install && npm install`
3. Copy environment file and generate key: `cp .env.example .env && php artisan key:generate`
4. Configure your local MySQL/MariaDB database in `.env` and run `php artisan migrate --seed`
5. Create your feature branch: `git checkout -b feature/your-branch-name`
6. Start the dev server: `composer run dev`