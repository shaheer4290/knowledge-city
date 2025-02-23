<?php

declare(strict_types=1);

namespace Migration;

use Cycle\Migrations\Migration;

class CreateOptimizedSchema extends Migration
{
    protected const DATABASE = 'default';

    public function up(): void
    {
        // 1. Drop existing tables except users and migrations
        $this->database()->execute('SET FOREIGN_KEY_CHECKS=0');
        
        // Drop specific tables
        $this->database()->execute('DROP TABLE IF EXISTS orders');
        $this->database()->execute('DROP TABLE IF EXISTS products');
        $this->database()->execute('DROP TABLE IF EXISTS stores');
        $this->database()->execute('DROP TABLE IF EXISTS categories');
        $this->database()->execute('DROP TABLE IF EXISTS regions');
        
        $this->database()->execute('SET FOREIGN_KEY_CHECKS=1');

        // 2. Create regions table
        $this->database()->execute('
            CREATE TABLE regions (
                region_id BIGINT AUTO_INCREMENT PRIMARY KEY,
                region_name VARCHAR(255) NOT NULL
            )
        ');

        // 3. Create categories table
        $this->database()->execute('
            CREATE TABLE categories (
                category_id BIGINT AUTO_INCREMENT PRIMARY KEY,
                category_name VARCHAR(255) NOT NULL,
                INDEX idx_category_analytics (category_id, category_name)
            )
        ');

        // 4. Create stores table
        $this->database()->execute('
            CREATE TABLE stores (
                store_id BIGINT AUTO_INCREMENT PRIMARY KEY,
                region_id BIGINT NOT NULL,
                store_name VARCHAR(255) NOT NULL,
                CONSTRAINT fk_stores_region FOREIGN KEY (region_id) 
                    REFERENCES regions(region_id),
                INDEX idx_store_region (region_id),
                INDEX idx_store_analytics (store_id, store_name)
            )
        ');

        // 5. Create products table
        $this->database()->execute('
            CREATE TABLE products (
                product_id BIGINT AUTO_INCREMENT PRIMARY KEY,
                store_id BIGINT NOT NULL,
                category_id BIGINT NOT NULL,
                name VARCHAR(255) NOT NULL,
                price DECIMAL(10,2) NOT NULL,
                CONSTRAINT fk_products_store FOREIGN KEY (store_id) 
                    REFERENCES stores(store_id),
                CONSTRAINT fk_products_category FOREIGN KEY (category_id) 
                    REFERENCES categories(category_id),
                INDEX idx_product_store (store_id),
                INDEX idx_product_analytics (product_id, store_id, category_id)
            )
        ');

        // 6. Create orders table
        $this->database()->execute('
            CREATE TABLE orders (
                order_id BIGINT AUTO_INCREMENT PRIMARY KEY,
                product_id BIGINT NOT NULL,
                quantity INT NOT NULL,
                unit_price DECIMAL(10,2) NOT NULL,
                order_date DATE NOT NULL,
                month_key DATE GENERATED ALWAYS AS 
                    (DATE_FORMAT(order_date, "%Y-%m-01")) STORED,
                CONSTRAINT fk_orders_product FOREIGN KEY (product_id) 
                    REFERENCES products(product_id),
                INDEX idx_order_analytics (order_date, product_id, quantity, unit_price),
                INDEX idx_order_date (order_date)
            )
        ');
    }

    public function down(): void
    {
        // Drop tables in reverse order, preserving users and migrations
        $this->database()->execute('SET FOREIGN_KEY_CHECKS=0');
        $this->database()->execute('DROP TABLE IF EXISTS orders');
        $this->database()->execute('DROP TABLE IF EXISTS products');
        $this->database()->execute('DROP TABLE IF EXISTS stores');
        $this->database()->execute('DROP TABLE IF EXISTS categories');
        $this->database()->execute('DROP TABLE IF EXISTS regions');
        $this->database()->execute('SET FOREIGN_KEY_CHECKS=1');
    }
} 