# Shopping Project — Online Clothing Storefront & Management System

A full-stack PHP and MySQL e-commerce clothing web application (`Shopping_Project`) featuring user registration and login, `JWT` session token handling (`firebase/php-jwt`), `AES-256-CBC` price encryption/decryption (`openssl.php`), product catalog browsing, search, category and trendy filtering, shopping cart with multi-tier discount logic, stock validation per size, order checkout (`bought`), post-purchase buyer feedback (`feedback`), and catalog/order management views.

## Repository & File Architecture

### Authentication, Session & Token Management
- `index.php`: User login page that verifies submitted credentials using `password_verify()`, handles admin redirection when `email === 'admin'`, generates `HS256` JWT `access_token` and `refresh_token` payloads via `Firebase\JWT\JWT`, stores a hashed refresh token (`password_hash`) in `users.refresh_token`, and stores tokens in `$_SESSION`.
- `register.php`: User sign-up page (`Username`, `Email`, `Age`, `Password`) that hashes passwords with `password_hash(..., PASSWORD_DEFAULT)`, checks whether the email already exists in `users`, and inserts new accounts.
- `refresh_token.php`: Validates the session `refresh_token` using `JWT::decode`, checks expiration (`exp < time()`), fetches the user from `users`, issues a new `access_token` (`60` seconds) and rotated `refresh_token` (`+7 days`), updates `users.refresh_token` with a new hash, and redirects to `redirect` (defaulting to `index.php`).
- `logout.php`: Calls `session_start()` and `session_destroy()` and redirects to `index.php`.

### Encryption & Database Layer
- `config.php`: Establishes the `mysqli` connection to local database `saproject` (`localhost`, user `root`, empty password by default).
- `openssl.php`: Defines the `openssl` helper class using `AES-256-CBC` (`encrypt()`, `decrypt()`, and `getIv()`), generating a random initialization vector (`IV`) per encryption operation and prepending it before base64 encoding.
- `saproject (4).sql`: MySQL/MariaDB database dump defining `addcard`, `bought`, `clothes`, `feedback`, and `users` tables along with sample rows.
- `composer.json`, `composer.lock`, `vendor/`: Composer configuration and dependencies (`firebase/php-jwt` `^6.11`).

### Storefront, Cart, Checkout & Feedback
- `home.php`: Protected storefront view (`access_token` check with fallback to `refresh_token.php?redirect=home.php` or `index.php`) supporting keyword search across `CONCAT(name, cat)`, category filtering (`dress`, `shirt`, `pants`, `jacket`), trendy filtering (`trendy = 1`), and listing all clothing items with decrypted prices, size selection (`small`, `medium`, `large`), quantity input, add-to-cart forms, feedback links, and product detail links.
- `trendy_clothes.php`: Displays items marked with `trendy = 1` from `clothes`.
- `details.php`: Displays item details (`image`, `name`, decrypted `price`, stock counts for `small`, `medium`, `large`, and `TRENDY` badge if enabled) along with submitted customer feedback entries (`Username`, `feedback`, `quality_score`) and a back link.
- `isert_card.php`: Validates `access_token`, checks available stock in `clothes` for the requested `size` and `quantity`, alerts if stock is insufficient, or inserts the item into `addcard` for the authenticated `user_id` before redirecting to `card.php`.
- `card.php`: Lists items in `addcard` for the current `user_id` with decrypted prices, calculates `total_before_dis` and `total_after_dis` based on quantity (`number_of_pieces > 3`), subtotal (`total_before_dis >= 1000`), and trendy item count (`trendy >= 1`), allows item removal via `del_card.php?id=...`, and links to `buy.php?id=$total_after_dis`.
- `del_card.php`: Deletes a cart entry by `id` from `addcard` and redirects to `card.php`.
- `buy.php`: Validates `access_token`, verifies stock availability for every item in `addcard` across its selected size, generates an `order_id` (`$uid . date("Y/m/d") . date("h:i:sa")`), deducts purchased quantities from `clothes`, records orders in `bought`, clears the user's `addcard` rows, and redirects to `action_page2.html`.
- `action_page2.html`: Order confirmation screen (`your order is on the way...`) with a return link to `home.php`.
- `feedback.php`: Validates `access_token`, verifies that the user has purchased `item_id` in `bought` and has not already submitted feedback for that `user_id` and `item_id` in `feedback`, renders a rating form (`Good = 2`, `Okay = 1`, `Bad = 0` + `suggestion` textarea), inserts the review into `feedback`, and redirects to `practice_ac.html`.
- `practice_ac.html`: Feedback confirmation screen (`your feedback submitted successefully`) with a link back to `home.php`.

### Profile, Catalog & Order Management
- `edit.php`: Protected profile update view allowing authenticated users to update `Username`, `Email`, and `Age` in `users`.
- `view admin.php`: Catalog management page with search, category filter, trendy filter, and product cards with `delete`, `edit`, and `details` links plus navigation to `add clothes.html` and `view orders.php`.
- `add clothes.html` & `insert.php`: Form and handler to add clothing items (`name`, `cat`, encrypted `price`, `small`, `medium`, `large`, uploaded image moved to `images/`, and `trendy`) into `clothes`.
- `editclothes.php` & `update.php`: Form and handler to edit clothing item fields, decrypt/re-encrypt `price`, optionally replace and unlink the previous image in `images/`, and update `clothes`.
- `delete.php`: Deletes an item's image file via `unlink()` and removes the row from `clothes`.
- `view orders.php` & `order details.php`: Lists distinct orders from `bought` with buyer username, clickable `order_id`, and `total_price`, and displays individual ordered items, sizes, and quantities per `order_id`.

### Static Assets & Styling
- `styles.css`, `home1.css`, `card.css`, `details.css`, `action_page2.html`, `add clothes.html`, `practice_ac.html`: Page layouts, backgrounds, card styling, form styling, and confirmation views.
- `images/`, `pics/`, `background2.jpg`, `background3.jpg`, `background10.jpg`, `delivery.jpg`, `onlineshopping2.jpg`, `good1.png`, `neutral1.png`, `bad.png`: Visual assets and product/UI imagery.

## Database Schema Overview (`saproject`)
- `users`: `Id`, `Username`, `Email`, `Age`, `Password`, `refresh_token`.
- `clothes`: `id`, `name`, `cat`, `price` (encrypted string), `small`, `medium`, `large`, `image`, `trendy`.
- `addcard`: `id`, `user_id`, `item_id`, `name`, `price`, `size`, `quantity`, `trendy`.
- `bought`: `id`, `order_id`, `user_id`, `item_id`, `name`, `quantity`, `total_price`, `size`.
- `feedback`: `id`, `user_id`, `item_id`, `order_id`, `feedback`, `quality_score`.

## Tools & Technologies
- **Backend:** PHP (`mysqli`, PHP Sessions) & Composer (`firebase/php-jwt`)
- **Database:** MySQL / MariaDB (`saproject`)
- **Frontend:** HTML5, CSS3, Bootstrap 5 (`CDN` in select pages), Google Fonts (`Open Sans` in `feedback.php`)

## How to Run Locally
1. Import `saproject (4).sql` into MySQL/MariaDB (database name: `saproject`).
2. Update database connection settings in `config.php` if your MySQL host, username, password, or database name differs.
3. Place the project folder inside your PHP web server directory (such as **XAMPP** `htdocs` or **Laragon** `www`), verify `vendor/` is present, and open `index.php` in your browser.
