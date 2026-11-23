# Online Clothing Store & Management System

> [!WARNING]
> **Security review required before sharing or deployment.** The current public source contains hard-coded cryptographic/JWT keys and has SQL queries that interpolate user-controlled values. Treat the committed keys as compromised: replace them with newly generated secrets stored outside Git, and review the full Git history because changing the latest files does not erase previously committed values. Keep this project as an academic prototype only; do not use real customer data or payments.

A university team project built with **PHP, MySQL, HTML, and CSS**. The application demonstrates an online clothing storefront with account registration and login, product browsing, a shopping cart, checkout, customer feedback, and administrator product/order management.

> **Project type:** Academic prototype. It is intended for learning and demonstration, not for processing real payments or personal data.

## Features

### Customer experience
- User registration and login.
- Product catalog with category, keyword, and trending filters.
- Product details, available sizes, and stock quantities.
- Shopping cart with quantity-based and promotional discount rules.
- Checkout flow with order records and stock updates.
- Feedback submission for purchased products.
- Profile information updates.

### Administration
- Admin-facing product catalog.
- Add, edit, and delete clothing items.
- View customer orders and order details.

### Authentication and application logic
- Password hashing and verification using PHP `password_hash()` and `password_verify()`.
- JWT access and refresh token flow using `firebase/php-jwt`.
- Refresh-token hashes stored in the users table.
- Product price encryption/decryption using PHP OpenSSL and AES-256-CBC.

## Technology Stack

| Layer | Technologies |
| --- | --- |
| Frontend | HTML5, CSS3, Bootstrap |
| Backend | PHP |
| Database | MySQL / MariaDB |
| Database access | MySQLi |
| Authentication | PHP Sessions, JWT |
| Cryptography | PHP password hashing, OpenSSL AES-256-CBC |
| Dependency management | Composer |

## Main Project Structure

| File / Folder | Purpose |
| --- | --- |
| `index.php`, `register.php`, `logout.php` | Login, registration, and logout |
| `refresh_token.php` | Refresh-token flow |
| `home.php`, `trendy_clothes.php`, `details.php` | Storefront and product browsing |
| `isert_card.php`, `card.php`, `del_card.php`, `buy.php` | Cart and checkout workflow |
| `feedback.php` | Customer feedback |
| `view admin.php`, `add clothes.html`, `insert.php`, `editclothes.php`, `update.php`, `delete.php` | Product administration |
| `view orders.php`, `order details.php` | Order management |
| `config.php` | Database connection |
| `openssl.php` | Product-price encryption helper |
| `saproject (4).sql` | Database schema and sample data |
| `composer.json`, `composer.lock` | PHP dependency definitions |
| `images/`, `pics/`, CSS files | Images and styling assets |

## Database Overview

Import the supplied SQL dump to create the `saproject` database. It contains these main tables:

- `users` — customer and administrator account records.
- `clothes` — product details, encrypted prices, and stock by size.
- `addcard` — shopping-cart entries.
- `bought` — purchased items and order records.
- `feedback` — customer reviews and quality scores.

## Run Locally

### Requirements
- PHP with MySQLi and OpenSSL extensions enabled.
- MySQL or MariaDB.
- Composer.
- A local PHP environment such as XAMPP or Laragon.

### 1. Clone the repository

```bash
git clone https://github.com/MernaAyman-l/E-Commerce-Web-Application.git
cd E-Commerce-Web-Application
```

### 2. Install PHP dependencies

```bash
composer install
```

The project uses `firebase/php-jwt`, declared in `composer.json`.

### 3. Create and import the database

1. Start MySQL/MariaDB.
2. Create a database named `saproject` (or import the dump, which creates the tables for that database).
3. Import `saproject (4).sql` using phpMyAdmin or the MySQL command line.

### 4. Configure the database connection

Update `config.php` to use your local database host, username, password, and database name. Do not commit real credentials.

### 5. Configure application secrets

The current source contains hard-coded cryptographic keys. For a local demonstration, replace them with new values kept outside version control (for example, environment variables). Do not reuse any key or sample credential from the repository.

### 6. Start the local web server

Place the project folder under your local server's web root (for example, XAMPP's `htdocs` or Laragon's `www`), start Apache and MySQL, then open the project through your local server, for example:

```text
http://localhost/E-Commerce-Web-Application/
```

## My Contributions

This was a team project. My contributions included:

- Developing and styling frontend pages, forms, product cards, and cart/order views using HTML, CSS, and PHP.
- Implementing password hashing and verification for user registration and login.
- Hashing refresh tokens before storing them in the database.

## Security Notes & Known Limitations

This repository is an educational prototype and has not been presented as production-hardened software. A review of the current source shows areas that should be addressed before any real deployment:

- Cryptographic keys are hard-coded in the PHP source. Rotate them and move secrets to environment-based configuration; removing a key from the latest file does not remove it from Git history.
- Several database queries interpolate user-controlled values into SQL. Use prepared statements consistently to reduce SQL-injection risk.
- Review session handling, token validation, cookie flags, expiration/rotation, and authorization checks across every protected page.
- Review file-upload validation and filesystem permissions for product images.
- Price encryption alone does not provide integrity protection. Use authenticated encryption or a well-reviewed design, with keys stored outside the repository.
- The included SQL dump contains demonstration records. Never use sample credentials or data in a real environment.

## Learning Outcomes

- Building a database-backed PHP web application.
- Implementing user authentication and token-based session flows.
- Working with relational data for products, stock, carts, orders, and feedback.
- Creating customer-facing and administrator-facing web pages.
- Applying basic cryptographic operations and identifying web-application security concerns.
