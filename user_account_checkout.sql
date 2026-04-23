-- user_account_checkout.sql
-- Purpose: Add customer user accounts and connect them to orders for better checkout.
-- Safe to run on an existing database. This script avoids duplicate changes when possible.

START TRANSACTION;

-- 1) Customer users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NULL,
    default_address TEXT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    last_login_at DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_users_email (email),
    INDEX idx_users_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2) Add user_id to orders if it does not exist yet
SET @has_user_id := (
    SELECT COUNT(*)
    FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME = 'orders'
      AND COLUMN_NAME = 'user_id'
);
SET @sql_add_user_id := IF(
    @has_user_id = 0,
    'ALTER TABLE orders ADD COLUMN user_id INT NULL AFTER id',
    'SELECT "orders.user_id already exists"'
);
PREPARE stmt FROM @sql_add_user_id;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- 3) Add index on orders.user_id if missing
SET @has_orders_user_idx := (
    SELECT COUNT(*)
    FROM information_schema.STATISTICS
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME = 'orders'
      AND INDEX_NAME = 'idx_orders_user_id'
);
SET @sql_add_user_idx := IF(
    @has_orders_user_idx = 0,
    'ALTER TABLE orders ADD INDEX idx_orders_user_id (user_id)',
    'SELECT "idx_orders_user_id already exists"'
);
PREPARE stmt FROM @sql_add_user_idx;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- 4) Add foreign key orders.user_id -> users.id if missing
SET @has_orders_user_fk := (
    SELECT COUNT(*)
    FROM information_schema.REFERENTIAL_CONSTRAINTS
    WHERE CONSTRAINT_SCHEMA = DATABASE()
      AND CONSTRAINT_NAME = 'fk_orders_user_id'
      AND TABLE_NAME = 'orders'
);
SET @sql_add_user_fk := IF(
    @has_orders_user_fk = 0,
    'ALTER TABLE orders ADD CONSTRAINT fk_orders_user_id FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE',
    'SELECT "fk_orders_user_id already exists"'
);
PREPARE stmt FROM @sql_add_user_fk;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

COMMIT;

-- Optional example account (password is placeholder hash; replace from PHP password_hash before production)
-- INSERT INTO users (full_name, email, password_hash, phone, default_address)
-- VALUES ('Demo User', 'demo@example.com', '$2y$10$replace_with_real_hash', '+1-555-0100', '123 Demo Street');
