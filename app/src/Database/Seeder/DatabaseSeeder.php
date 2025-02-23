<?php

declare(strict_types=1);

namespace App\Database\Seeder;

use Cycle\ORM\EntityManagerInterface;
use Cycle\ORM\ORMInterface;
use App\Entity\Region;
use App\Entity\Category;
use App\Entity\Store;
use App\Entity\Product;
use App\Entity\Order;
use Cycle\Database\DatabaseInterface;

class DatabaseSeeder
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ORMInterface $orm,
        private readonly DatabaseInterface $database
    ) {
    }

    public function run(): void
    {
        $this->truncateTables();
        $this->seedRegions();
        $this->seedCategories();
        $this->seedStores();
        $this->seedProducts();
        $this->seedOrders();
    }

    private function truncateTables(): void
    {
        // Disable foreign key checks
        $this->database->execute('SET FOREIGN_KEY_CHECKS=0');
        
        // Truncate all tables
        $this->database->execute('TRUNCATE TABLE orders');
        $this->database->execute('TRUNCATE TABLE products');
        $this->database->execute('TRUNCATE TABLE stores');
        $this->database->execute('TRUNCATE TABLE categories');
        $this->database->execute('TRUNCATE TABLE regions');
        
        // Enable foreign key checks
        $this->database->execute('SET FOREIGN_KEY_CHECKS=1');
    }

    private function seedRegions(): void
    {
        $regions = [
            ['regionName' => 'North'],
            ['regionName' => 'South'],
            ['regionName' => 'East'],
            ['regionName' => 'West']
        ];

        foreach ($regions as $regionData) {
            $region = new Region($regionData['regionName']);
            $this->entityManager->persist($region);
        }
        $this->entityManager->run();
    }

    private function seedCategories(): void
    {
        $categories = [
            ['categoryName' => 'Electronics'],
            ['categoryName' => 'Clothing'],
            ['categoryName' => 'Food'],
            ['categoryName' => 'Home'],
            ['categoryName' => 'Sports']
        ];

        foreach ($categories as $categoryData) {
            $category = new Category();
            $category->categoryName = $categoryData['categoryName'];
            $this->entityManager->persist($category);
        }
        $this->entityManager->run();
    }

    private function seedStores(): void
    {
        $regions = $this->orm->getRepository(Region::class)->findAll();
        
        foreach ($regions as $region) {
            for ($i = 1; $i <= 5; $i++) {
                $store = new Store();
                $store->regionId = $region->regionId;
                $store->storeName = sprintf("%s Store %d", $region->regionName, $i);
                $this->entityManager->persist($store);
            }
        }
        $this->entityManager->run();
    }

    private function seedProducts(): void
    {
        $categories = $this->orm->getRepository(Category::class)->findAll();

        foreach ($categories as $category) {
            for ($i = 1; $i <= 10; $i++) {
                $product = new Product();
                $product->categoryId = $category->categoryId;
                $product->categoryCategoryId = $category->categoryId;
                $product->productName = sprintf("%s Product %d", $category->categoryName, $i);
                $this->entityManager->persist($product);
            }
        }
        $this->entityManager->run();
    }

    private function seedOrders(): void
    {
        $stores = $this->orm->getRepository(Store::class)->findAll();
        $products = $this->orm->getRepository(Product::class)->findAll();
        
        // Generate 2 years of data
        $startDate = new \DateTime('-2 years');
        $endDate = new \DateTime();
        $interval = new \DateInterval('P1D');
        $period = new \DatePeriod($startDate, $interval, $endDate);

        foreach ($period as $date) {
            // Skip weekends
            if ($date->format('N') >= 6) {
                continue;
            }

            // Create 3 orders per day
            for ($i = 0; $i < 3; $i++) {
                $store = $stores[array_rand($stores)];
                $product = $products[array_rand($products)];
                
                $order = new Order();
                $order->customerId = mt_rand(1, 100);
                $order->productId = $product->productId;
                $order->product_productId = $product->productId;
                $order->quantity = mt_rand(1, 5);
                $order->unitPrice = mt_rand(10, 1000) / 10;
                $order->orderDate = $date;
                $order->storeId = $store->storeId;
                $order->store_storeId = $store->storeId;
                
                $this->entityManager->persist($order);
            }
        }
        
        $this->entityManager->run();
    }
} 