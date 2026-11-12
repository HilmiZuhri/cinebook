# 🎬 CineBook - Cinema Ticket Booking System

[![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![TailwindCSS](https://img.shields.io/badge/TailwindCSS-v3%2Fv4-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![MySQL](https://img.shields.io/badge/MySQL-8.x-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com)
[![Tests](https://img.shields.io/badge/Tests-Passing-brightgreen?style=for-the-badge&logo=php)](https://phpunit.de)

**CineBook** is an end-to-end cinema ticket booking platform built with **Laravel**. It features a headless authentication architecture via **Laravel Fortify**, live movie catalog synchronization using **The Movie Database (TMDB) REST API**, interactive seat map selection with concurrency protection, an administrative management dashboard, and a **Dark Cinematic** user interface styled with **Tailwind CSS**.

Developed as a showcase portfolio project demonstrating clean architecture, defensive programming against race conditions, and automated feature testing.

---

## ✨ Key Features

### 👤 Customer Experience (Dark Cinematic UI)
- **Live TMDB Integration**: Real-time synchronization of Now Playing, Popular, and Upcoming movies with response caching.
- **Movie Catalog & Instant Search**: Dynamic keyword query search with genre filters and metadata display.
- **Cinematic Detail Pages**: Full-screen backdrop banners, official embedded YouTube trailers, cast list, runtime, ratings, and synopsis.
- **Interactive Seat Map Selection**: Visual cinema screen layout with dynamic row-by-row seat states (Available, Selected, Booked).
- **Checkout & Payment Simulation**: 15-minute countdown reservation hold timer with simulated QRIS / E-Wallet payments.
- **Digital E-Tickets & Print Ready**: Boarding pass-style digital tickets with unique alphanumeric booking codes, CSS-rendered barcodes, and print-to-PDF styles.
- **Booking History**: Customer order tracking with real-time status indicators (Paid, Pending, Cancelled, Expired).

### 🛠️ Admin Panel & Management
- **Business KPI Metrics**: Real-time aggregation of gross revenue, total tickets sold, active orders, and registered customers.
- **Top-Grossing Movies Report**: Automated ranking of movies by ticket sales volume and revenue.
- **Transaction Management**: Comprehensive booking ledger with status filtering and direct e-ticket verification.

### 🛡️ Architecture & Security Engineering
- **Double Booking Prevention**: Utilizes **Database Transactions (`DB::transaction`)** combined with **Pessimistic Locking (`lockForUpdate`)** to prevent race conditions during concurrent seat reservations.
- **Schedule Overlap Validation**: Automated conflict resolution ensuring a cinema studio cannot be double-booked for intersecting time windows.
- **Headless Authentication**: Powered by **Laravel Fortify** decoupled from presentation views, backed by custom Role-Based Access Control (RBAC) middleware.
- **Automated Feature Testing**: 100% test coverage on critical business logic, access authorization, and seat locking integrity.

---

## 🛠️ Tech Stack

- **Framework**: Laravel 11 (PHP 8.2+)
- **Authentication**: Laravel Fortify
- **Frontend / Styling**: Blade Components, Tailwind CSS, Vite
- **Database**: MySQL / MariaDB
- **Third-Party API**: The Movie Database (TMDB) REST API v3
- **Testing Suite**: PHPUnit / Laravel Feature Testing

---

## 🚀 Getting Started

### Prerequisites
- PHP >= 8.2 (with `pdo_mysql` and `pdo_sqlite` extensions enabled)
- Composer >= 2.x
- Node.js >= 18.x & NPM
- MySQL or MariaDB

### Installation

1. Clone the repository:
```bash
git clone https://github.com/your-username/CineBook.git
cd CineBook
```

2. Install PHP and Node dependencies:
```bash
composer install
npm install
```

3. Configure Environment:
```bash
cp .env.example .env
php artisan key:generate
```

4. Update `.env` configuration:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cinebook
DB_USERNAME=root
DB_PASSWORD=

# TMDB Configuration
TMDB_BASE_URL=https://api.themoviedb.org/3
TMDB_IMAGE_BASE_URL=https://image.tmdb.org/t/p
TMDB_API_KEY=your_actual_tmdb_api_key_here
```

5. Run database migrations and seed default data:
```bash
php artisan migrate --seed
```

6. Compile frontend assets and start local server:
```bash
npm run build
php artisan serve
```
Access the application at `http://127.0.0.1:8000`.

---

## 🔑 Demo Credentials

| Role | Email | Password | Access Scope |
|---|---|---|---|
| **Administrator** | `admin@cinebook.com` | `password123` | Full access to Admin Dashboard, Analytics & Bookings |
| **Customer** | `customer@cinebook.com` | `password123` | Movie browsing, seat reservation, checkout & e-tickets |

---

## 🧪 Running Automated Tests

Run the full feature test suite verifying RBAC authorization, seat locking, and transactional integrity:

```bash
php artisan test --filter=BookingAndAuthorizationTest
```

Expected Output:
```text
   PASS  Tests\Feature\BookingAndAuthorizationTest
  ✓ customer cannot access admin dashboard
  ✓ admin can access admin dashboard
  ✓ customer can book seats successfully
  ✓ prevents double booking of same seat
  ✓ cancelling booking releases seats

  Tests:    5 passed (9 assertions)
  Duration: ~0.5s
```

---

## 📄 License

This project is open-sourced software licensed under the [MIT License](LICENSE).