CREATE TABLE IF NOT EXISTS admins (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(190) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  category VARCHAR(50) NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  image VARCHAR(255) NOT NULL,
  description TEXT,
  status TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS routes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(80) NOT NULL,
  arrival_time TIME NOT NULL,
  cutoff_time TIME NOT NULL,
  pickup_start TIME NOT NULL,
  pickup_end TIME NOT NULL,
  status TINYINT(1) DEFAULT 1
);

CREATE TABLE IF NOT EXISTS pickup_points (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  point_number VARCHAR(50) NOT NULL,
  location VARCHAR(255) NOT NULL,
  description TEXT,
  phone VARCHAR(40),
  status TINYINT(1) DEFAULT 1
);

CREATE TABLE IF NOT EXISTS delivery_people (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  phone VARCHAR(40) NOT NULL,
  gender VARCHAR(20),
  email VARCHAR(190) UNIQUE,
  password_hash VARCHAR(255),
  availability VARCHAR(120),
  status VARCHAR(30) DEFAULT 'Available',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS settings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  setting_key VARCHAR(100) NOT NULL UNIQUE,
  setting_value TEXT
);

CREATE TABLE IF NOT EXISTS orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_number VARCHAR(40) NOT NULL UNIQUE,
  customer_name VARCHAR(120) NOT NULL,
  phone VARCHAR(40) NOT NULL,
  college_name VARCHAR(190) NOT NULL,
  student_id VARCHAR(100),
  delivery_method ENUM('pickup','delivery') NOT NULL DEFAULT 'pickup',
  delivery_location VARCHAR(255),
  payment_method VARCHAR(20) NOT NULL DEFAULT 'cash',
  route_id INT,
  pickup_point_id INT,
  delivery_person_id INT NULL,
  status VARCHAR(30) NOT NULL DEFAULT 'Confirmed',
  total_amount DECIMAL(10,2) NOT NULL DEFAULT 0,
  notes TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (route_id) REFERENCES routes(id) ON DELETE SET NULL,
  FOREIGN KEY (pickup_point_id) REFERENCES pickup_points(id) ON DELETE SET NULL,
  FOREIGN KEY (delivery_person_id) REFERENCES delivery_people(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS order_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  product_id INT NOT NULL,
  quantity INT NOT NULL,
  unit_price DECIMAL(10,2) NOT NULL,
  subtotal DECIMAL(10,2) NOT NULL,
  chili_option VARCHAR(50),
  tomato_option VARCHAR(50),
  preparation VARCHAR(50),
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE RESTRICT
);

-- ============================================================
-- MIGRATION: if you already imported this database before,
-- run the two ALTER statements below once (they are safe to
-- re-run, MySQL 8+ ignores them if the column already exists).
-- ============================================================
ALTER TABLE orders ADD COLUMN IF NOT EXISTS payment_method VARCHAR(20) NOT NULL DEFAULT 'cash' AFTER delivery_location;

INSERT INTO admins(name,email,password_hash)
VALUES ('Main Admin','admin@example.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC9uQ0cV2h4qK6Jj5ZK')
ON DUPLICATE KEY UPDATE name=VALUES(name);

INSERT INTO products(name,category,price,image,description,status) VALUES
('Mihogo 100','Mihogo',100,'mihogo-100.png','Mihogo portion ya 100.',1),
('Mihogo 200','Mihogo',200,'mihogo-200.png','Mihogo portion ya 200.',1),
('Juice 500','Juice',500,'juice-500.png','Juice ya 500.',1),
('Juice 1000','Juice',1000,'juice-1000.png','Juice ya 1000.',1);

INSERT INTO routes(name,arrival_time,cutoff_time,pickup_start,pickup_end,status) VALUES
('Route 1','03:00:00','02:30:00','03:00:00','04:00:00',1),
('Route 2','07:00:00','06:30:00','07:00:00','08:00:00',1),
('Route 3','12:00:00','11:30:00','12:00:00','13:00:00',1);

INSERT INTO pickup_points(name,point_number,location,description,phone,status)
VALUES ('Campus Juice Point','CP-001','Near Main Gate','Default pickup point. Edit this from admin.','',1);

INSERT INTO settings(setting_key,setting_value) VALUES
('business_name','Campus Bites'),
('college_name','Sample College'),
('business_phone',''),
('pickup_name','Campus Juice Point'),
('pickup_number','CP-001'),
('pickup_location','Near Main Gate'),
('business_description','Fresh fried cassava and juice, ordered simply and collected or delivered in batches.')
ON DUPLICATE KEY UPDATE setting_value=VALUES(setting_value);

-- New settings for payment number + footer WhatsApp contacts.
-- Uses a no-op update so it will NOT overwrite values you already
-- changed from the admin panel if you re-run this file.
INSERT INTO settings(setting_key,setting_value) VALUES
('payment_number','0744409418'),
('whatsapp_contact1_name','Msaidizi 1'),
('whatsapp_contact1_phone','0744409418'),
('whatsapp_contact2_name','Msaidizi 2'),
('whatsapp_contact2_phone','')
ON DUPLICATE KEY UPDATE setting_key=setting_key;

-- IMPORTANT:
-- The sample admin password is: password
-- Change it after first login.
