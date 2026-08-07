# 📚 Library Management System

A role-based library management system built with **Laravel 12**, designed to help schools and institutions manage book inventory, borrowing, returns, and penalties through separate dashboards for **Admins**, **Librarians**, and **Students**.

---

## ✨ Features

- **Role-based access control** — Admin, Librarian, and Student each get a dedicated dashboard and permission set.
- **Book catalog management** — add, edit, and organize the library's book inventory.
- **Borrowing workflow** — track borrow dates and return (due) dates for every loan.
- **Penalty system** — automatically flag/handle overdue borrows.
- **Reporting** — generate reports across borrowing activity and inventory.
- **Search** — look up books quickly from the catalog.

### 🗺️ Planned / In Progress
The following enhancements are on the roadmap:
- Advanced search filters
- Streamlined "add book" UI (all book details on a single view)
- Lazy loading for long book listings
- Book reviews for student/instructor feedback
- Notifications for due dates, overdue items, and reservation alerts

---

## 🧑‍🤝‍🧑 User Roles

| Role | Access |
|------|--------|
| **Admin** | Full control over the system — manages books, users, and reports |
| **Librarian** | Manages book inventory and borrow/return transactions |
| **Student** | Browses the catalog, borrows books, and views their own history |

---

## 🛠️ Tech Stack

| Layer | Technology |
|-------|------------|
| Backend | [Laravel 12](https://laravel.com) (PHP ^8.2) |
| Frontend build | [Vite 7](https://vitejs.dev) + [Laravel Vite Plugin](https://laravel.com/docs/vite) |
| Styling | [Tailwind CSS 4](https://tailwindcss.com) |
| Testing | PHPUnit |
| Dev tooling | Laravel Pint, Laravel Sail, Laravel Pail, Faker, Mockery |

**Color palette:**

| Swatch | Hex |
|--------|-----|
| ⬜ | `#EFECE3` |
| 🟦 | `#8FABD4` |
| 🔵 | `#4A70A9` |
| ⬛ | `#000000` |
| 🟨 | `#F2E9D8` (parchment) |
| 🟫 | `#A67C52` (book spines) |
| 🟤 | `#6C4F3D` (hardcover brown) |
| ⚫ | `#2C1A13` (ink) |

---

## 🚀 Getting Started

### Prerequisites

- PHP >= 8.2
- Composer
- Node.js & npm
- A database (MySQL/SQLite/PostgreSQL — configure via `.env`)

### Installation

```bash
# 1. Clone the repository
git clone https://github.com/rhondelp/LIBRARY-MANAGEMENT-SYSTEM.git
cd LIBRARY-MANAGEMENT-SYSTEM

# 2. Install PHP dependencies
composer install

# 3. Install JS dependencies
npm install

# 4. Copy the environment file and generate an app key
cp .env.example .env
php artisan key:generate

# 5. Configure your database credentials in .env, then run migrations
php artisan migrate

# 6. (Optional) Seed the database with sample data
php artisan db:seed
```

### Running the app

```bash
# Start the Laravel dev server
php artisan serve

# In a separate terminal, run the Vite dev server for hot-reloading assets
npm run dev
```

The app should now be available at `http://localhost:8000`.

### Building for production

```bash
npm run build
```

---

## 🧹 Useful Artisan Commands

Handy when things get cached weirdly during development:

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan optimize:clear
php artisan optimize
```

---

## 🧪 Testing

```bash
php artisan test
```

---

## 📁 Project Structure

Standard Laravel 12 directory layout:

```
app/          Application logic (models, controllers, policies, etc.)
bootstrap/    Framework bootstrap files
config/       Configuration files
database/     Migrations, factories, and seeders
public/       Publicly accessible entry point & compiled assets
resources/    Views (Blade), CSS, and JS source
routes/       Route definitions
storage/      Logs, cache, and file uploads
tests/        PHPUnit tests
```

---

## 🤝 Contributing

Contributions, issues, and feature requests are welcome. Feel free to open a pull request or start a discussion.

## 📄 License

This project is open-sourced software. Check the repository for license details.