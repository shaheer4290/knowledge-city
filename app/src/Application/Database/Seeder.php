<?php

declare(strict_types=1);

namespace App\Application\Database;

use Cycle\Database\DatabaseInterface;

class Seeder
{
    public function __construct(
        private readonly DatabaseInterface $db
    ) {
    }

    public function run(): void
    {
        $this->clear();

        try {
            // 1. Seed Regions
            $this->db->execute('
                INSERT INTO regions (region_name) VALUES 
                ("North"), ("South"), ("East"), ("West"), ("Central")
            ');

            // 2. Seed Categories
            $this->db->execute('
                INSERT INTO categories (category_name) VALUES 
                ("Electronics"), ("Clothing"), ("Books"), 
                ("Home & Garden"), ("Sports")
            ');

            // 3. Seed Stores
            for ($i = 1; $i <= 10; $i++) {
                $this->db->execute(
                    'INSERT INTO stores (region_id, store_name) 
                    VALUES (?, ?)',
                    [rand(1, 5), "Store " . $i]
                );
            }

            // 4. Seed Products
            $productData = [
                ['Laptop', 1, 999.99],
                ['Smartphone', 1, 599.99],
                ['T-Shirt', 2, 29.99],
                ['Jeans', 2, 49.99],
                ['Novel', 3, 19.99],
                ['Textbook', 3, 79.99],
                ['Garden Tools', 4, 149.99],
                ['Plant Pots', 4, 24.99],
                ['Running Shoes', 5, 89.99],
                ['Yoga Mat', 5, 39.99]
            ];

            foreach ($productData as $product) {
                $this->db->execute(
                    'INSERT INTO products (name, store_id, category_id, price) 
                    VALUES (?, ?, ?, ?)',
                    [$product[0], rand(1, 10), $product[1], $product[2]]
                );
            }

            // 5. Seed Orders
            $startDate = strtotime('-1 year');
            $endDate = time();

            for ($i = 0; $i < 1000; $i++) {
                $orderDate = date('Y-m-d', rand($startDate, $endDate));
                $monthKey = date('Y-m-01', strtotime($orderDate));
                $product = $this->db->query('SELECT product_id, price FROM products ORDER BY RAND() LIMIT 1')->fetch();
                
                $this->db->execute(
                    'INSERT INTO orders (product_id, quantity, unit_price, order_date, month_key) 
                    VALUES (?, ?, ?, ?, ?)',
                    [
                        $product['product_id'],
                        rand(1, 10),
                        $product['price'],
                        $orderDate,
                        $monthKey
                    ]
                );

                // Commit every 100 orders to avoid memory issues
                if ($i % 100 === 0) {
                    $this->db->execute('COMMIT');
                }
            }

            echo "Database seeded successfully!\n";

        } catch (\Exception $e) {
            echo "Error seeding database: " . $e->getMessage() . "\n";
            echo "SQL State: " . $e->getCode() . "\n";
            throw $e;
        }
    }

    public function clear(): void
    {
        // Disable foreign key checks
        $this->db->execute('SET FOREIGN_KEY_CHECKS=0');

        // Clear all tables
        $this->db->execute('TRUNCATE TABLE orders');
        $this->db->execute('TRUNCATE TABLE products');
        $this->db->execute('TRUNCATE TABLE stores');
        $this->db->execute('TRUNCATE TABLE categories');
        $this->db->execute('TRUNCATE TABLE regions');

        // Re-enable foreign key checks
        $this->db->execute('SET FOREIGN_KEY_CHECKS=1');
    }
}