# Dreamy Pages Bookstore — Setup Guide

This folder is a working PHP + MySQL version of your bookstore. It replaces the static
`fantasy.html`, `fiction.html`, etc. pages with database-driven pages, and adds real
login/signup, a session-based cart, and order storage in MySQL.

## 1. Install XAMPP

Download XAMPP from https://www.apachefriends.org and install it (Windows/Mac/Linux all supported).
During install, make sure **Apache** and **MySQL** components are checked.

## 2. Start Apache and MySQL

Open the **XAMPP Control Panel** and click **Start** next to both `Apache` and `MySQL`.
Both rows should turn green. If MySQL won't start, something else (often Skype, or a previously
installed MySQL service) is using port 3306 — close it and try again.

## 3. Copy this project into htdocs

Copy the whole `dreamypages` folder into XAMPP's `htdocs` directory:

- Windows: `C:\xampp\htdocs\dreamypages`
- macOS: `/Applications/XAMPP/htdocs/dreamypages`
- Linux: `/opt/lampp/htdocs/dreamypages`

## 4. Create the database

1. Open `http://localhost/phpmyadmin` in your browser (this only works while Apache + MySQL are running).
2. Click **Import** in the top menu.
3. Click **Choose File**, select `sql/dreamypages.sql` from this project.
4. Click **Go** at the bottom. This creates the `dreamypages` database with four tables
   (`users`, `books`, `orders`, `order_items`, `contact_messages`) and seeds ~30 sample books.

## 5. Open the site

Visit `http://localhost/dreamypages/index.php` in your browser. You should see the home page
pulling "Featured Books" live from MySQL.

## 6. How the pieces connect

- `includes/db.php` opens one shared PDO connection using host `localhost`, database `dreamypages`,
  user `root`, password `` (empty) — XAMPP's defaults. Every page includes this file first.
- If you ever set a MySQL root password in XAMPP, update `$DB_PASS` in `includes/db.php` to match.
- `register.php` / `login.php` handle authentication with `password_hash()` / `password_verify()` —
  passwords are never stored in plain text.
- `category.php?genre=fantasy` (etc.) replaces the old static category pages — it queries the
  `books` table by genre instead of hardcoding book lists in HTML.
- Adding a book stores it in `$_SESSION['cart']`; `cart.php` joins that against the `books` table
  to show live prices and stock.
- `checkout.php` is the order-management step: it copies the cart into one `orders` row plus
  matching `order_items` rows, reduces `books.stock`, and empties the cart — all inside a single
  MySQL transaction so it can't half-save.
- `orders.php` is the "My Orders" history page for the logged-in user.

## 7. About the book cover images

The original HTML referenced image files on your local `D:\...` drive, which won't exist on any
other computer. Every page now falls back to `assets/images/placeholder.svg` automatically. To use
real covers: drop `.jpg`/`.png` files into `assets/images/`, then update the `image` column for
each book in phpMyAdmin (Browse → Edit) to match the filename.

## 8. Common errors

- **"Database connection failed"** — Apache/MySQL aren't running, or the database hasn't been
  imported yet. Recheck steps 2 and 4.
- **White/blank page** — open `http://localhost/dashboard/phpmyadmin` error log, or temporarily add
  `error_reporting(E_ALL); ini_set('display_errors', 1);` to the top of `includes/db.php` to see the
  exact PHP error.
- **"Access denied for user 'root'@'localhost'"** — your MySQL has a root password set; put it in
  `$DB_PASS` in `includes/db.php`.
