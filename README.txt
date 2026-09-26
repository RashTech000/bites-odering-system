CAMPUS BITES — SINGLE-FOLDER / INFINITY READY
===============================================

This project is intentionally FLAT: all PHP, CSS, JS, images and database.sql are in this ONE folder.

Upload the CONTENTS of this folder to your InfinityFree htdocs/public_html web root.

Database:
1. Create a MySQL database on InfinityFree.
2. Import database.sql using phpMyAdmin.
   - If you already had this database installed before, just run the two
     statements under the "MIGRATION" comment near the middle of the file
     (they add the payment_method column and the new settings).
3. Open config.php and replace DB_HOST, DB_USER, DB_PASS and DB_NAME with the database details from InfinityFree.

Admin:
URL: admin_login.php
Email: admin@example.com
Password: password

Delivery:
URL: delivery_login.php

Customer:
URL: index.php

Language:
- The store defaults to Kiswahili. Customers can switch to English with the
  "SW | EN" links in the top navigation (choice is remembered per session).
- To add more translated text, edit lang.php.

Payment at checkout:
- Customers choose Cash or Mobile payment when placing an order.
- The mobile payment number (default 0744409418) is editable from
  Admin → Settings → Payment.

Footer WhatsApp contacts:
- Two contact people (labelled male/female) appear in the site footer.
  Tapping a contact opens a WhatsApp chat with that number directly.
- Edit their names/numbers from Admin → Settings → Footer WhatsApp contacts.

Delivery person management:
- Admin → Delivery lets the admin add/edit delivery people (name, phone,
  gender, availability, status) and assign them to delivery orders.

Important:
- Change the admin password after first login.
- Admin can edit business name, college, pickup point, prices, images,
  routes, the payment number and the footer WhatsApp contacts.
- Product images are kept in this same folder.
