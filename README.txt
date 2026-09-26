# 🍹 Campus Bites

A simple campus food-ordering web app for cassava (*mihogo*) and juice, built with plain PHP + MySQL. Customers browse the menu, customize their order, checkout, and track their order status — no login required. Admins manage products, routes, pickup points, delivery staff, and site settings from a dashboard.

Built as a single flat folder so it can be deployed directly on shared hosting (e.g. InfinityFree) with zero build step.

---

## Features

**Customer-facing**
- Browse menu (Mihogo & Juice), customize cassava (chili / tomato / fried or boiled)
- Cart with quantity updates
- Checkout with **Pickup** or **Delivery**
- Payment method at checkout: **Cash** or **Mobile payment** (admin-editable number)
- Automatic batch/route assignment based on order cutoff times
- Order tracking by order number, with a visual status timeline
- Bilingual UI — **Kiswahili (default)** and **English**, switchable from the nav bar
- Two WhatsApp contact links in the footer that open a chat directly (numbers editable by admin)

**Admin dashboard** (`admin_login.php`)
- Today's stats: orders, sales, pending, ready
- Manage products (name, category, price, image upload, description, availability)
- Manage routes (arrival/cutoff/pickup windows)
- Manage pickup points
- Manage delivery people (add/edit, assign to delivery orders)
- Manage all orders and update their status
- Site settings: business info, pickup point, **payment number**, **footer WhatsApp contacts**

**Delivery dashboard** (`delivery_login.php`)
- Delivery staff log in and see only their assigned orders
- Update status: Assigned → On the Way → Delivered

---

## Tech stack

- PHP (procedural, `mysqli`)
- MySQL / MariaDB
- Vanilla HTML/CSS/JS — no frameworks, no build tools, no Composer

---

## Project structure

Everything lives in one flat folder (as required by most shared hosting):

```
├── config.php              # DB connection, session, helpers (e(), setting(), redirect()...)
├── lang.php                # Kiswahili/English translation dictionary + t() helper
├── database.sql            # Full schema + seed data + migration notes
│
├── header.php / footer.php # Shared layout for customer pages
├── index.php                # Home page
├── products.php             # Menu / add to cart
├── cart.php                  # View & update cart
├── checkout.php              # Customer details, pickup/delivery, payment method
├── order-success.php         # Order confirmation
├── track-order.php           # Order status lookup
├── logout.php
│
├── admin_header.php / admin_footer.php
├── admin_login.php / admin_logout.php
├── admin_index.php           # Dashboard
├── admin_products.php
├── admin_routes.php
├── admin_pickup_points.php
├── admin_delivery.php        # Manage delivery staff + assign orders
├── admin_orders.php          # All orders, update status
├── admin_settings.php        # Business info, payment number, WhatsApp contacts
│
├── delivery_login.php / delivery_logout.php
├── delivery_index.php        # Delivery staff dashboard
│
├── style.css
├── script.js
├── favicon.ico / logo.png / logo-192.png / apple-touch-icon.png
└── *.png                     # Product images
```

---

## Setup

### 1. Requirements
- PHP 7.4+ with `mysqli` extension
- MySQL/MariaDB
- Any web server (XAMPP locally, or shared hosting like InfinityFree)

### 2. Get the code
```bash
git clone https://github.com/<your-username>/campus-bites.git
cd campus-bites
```

### 3. Create the database
Create a MySQL database (e.g. `campus_bites`), then import the schema:
```bash
mysql -u root -p campus_bites < database.sql
```
Or, in phpMyAdmin: **create the database first**, open it, then use **Import** → select `database.sql` → Go.

> ⚠️ You must select/create the database *before* importing — `database.sql` does not create the database itself.

### 4. Configure the connection
Edit `config.php`:
```php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "campus_bites";
```

### 5. Run it
- **Local (XAMPP/WAMP):** drop the folder into `htdocs/`, then visit `http://localhost/campus-bites/`
- **Shared hosting:** upload the *contents* of this folder to your `public_html` / `htdocs` root

### 6. Default logins
| Role  | URL | Email | Password |
|---|---|---|---|
| Admin | `admin_login.php` | `admin@example.com` | `password` |

> Change the admin password immediately after first login.

---

## Configuration from the admin panel

Instead of editing code, most business settings are managed from **Admin → Settings**:
- Business name, college name, phone, pickup point details
- **Mobile payment number** shown to customers at checkout
- **Footer WhatsApp contacts** (name + number for the two contact people shown site-wide)

Delivery staff are managed from **Admin → Delivery**.

---

## Migrating an existing database

If you already had this project running before the payment-method / WhatsApp features were added, run this once instead of re-importing the whole file:

```sql
ALTER TABLE orders ADD COLUMN IF NOT EXISTS payment_method VARCHAR(20) NOT NULL DEFAULT 'cash' AFTER delivery_location;

INSERT INTO settings(setting_key,setting_value) VALUES
('payment_number','0744409418'),
('whatsapp_contact1_name','Msaidizi 1'),
('whatsapp_contact1_phone','0744409418'),
('whatsapp_contact2_name','Msaidizi 2'),
('whatsapp_contact2_phone','')
ON DUPLICATE KEY UPDATE setting_key=setting_key;
```

(This block is also included, commented, at the bottom of `database.sql`.)

---

## Notes / known limitations

- No `.gitignore` / environment file yet — `config.php` holds plaintext local credentials; **do not commit real production credentials**. Consider moving DB credentials to a non-tracked `config.local.php` or environment variables before deploying publicly.
- No CSRF protection or rate limiting on forms — fine for a small campus project, not hardened for public production use without further review.
- Language preference is stored in the PHP session (not a cookie), so it resets when the session ends.

---

## License

rashtech.| All right reserved.
