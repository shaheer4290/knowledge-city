<?php

declare(strict_types=1);

namespace App\Service;

use Cycle\Database\DatabaseProviderInterface;

final class SalesAnalyticsService
{
    public function __construct(
        private readonly DatabaseProviderInterface $dbal
    ) {
    }

    public function getMonthlySalesByRegion(?string $startDate, ?string $endDate): array
    {
        $db = $this->dbal->database();
        
        $sql = 'SELECT 
                o.month_key as month,
                r.region_name,
                SUM(o.quantity * o.unit_price) as total_sales
            FROM orders o
            INNER JOIN products p ON p.product_id = o.product_id
            INNER JOIN stores s ON s.store_id = p.store_id
            INNER JOIN regions r ON r.region_id = s.region_id';
            
        $params = [];
        $where = [];
        
        if ($startDate) {
            $where[] = 'o.order_date >= ?';
            $params[] = $startDate;
        }
        
        if ($endDate) {
            $where[] = 'o.order_date <= ?';
            $params[] = $endDate;
        }
        
        if (!empty($where)) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }
        
        $sql .= ' GROUP BY o.month_key, r.region_name
            ORDER BY month ASC, total_sales DESC';
        
        $statement = $db->prepare($sql);
        return $statement->execute($params)->fetchAll();
    }

    public function getTopCategoriesByStore(?int $storeId = null, ?int $limit = null): array
    {
        $db = $this->dbal->database();
        
        $sql = <<<SQL
            SELECT 
                s.store_name,
                c.category_name,
                SUM(o.quantity * o.unit_price) as total_sales,
                SUM(o.quantity) as total_quantity
            FROM orders o
            INNER JOIN products p ON p.product_id = o.product_id
            INNER JOIN stores s ON s.store_id = p.store_id
            INNER JOIN categories c ON c.category_id = p.category_id
            USE INDEX (idx_order_analytics, idx_product_analytics)
        SQL;
            
        $params = [];
        
        if ($storeId !== null) {
            $sql .= ' WHERE s.store_id = ' . (int)$storeId;
        }
        
        $sql .= ' GROUP BY s.store_id, c.category_id
            ORDER BY s.store_name ASC, total_sales DESC';
            
        if ($limit !== null) {
            $sql .= ' LIMIT ' . max(1, (int)$limit);
        }
        
        return $db->query($sql)->fetchAll();
    }

    public function getProductPerformance(?int $storeId = null, ?string $startDate = null, ?string $endDate = null): array
    {
        $db = $this->dbal->database();
        
        $sql = <<<SQL
            SELECT 
                p.name as product_name,
                s.store_name,
                c.category_name,
                SUM(o.quantity) as total_quantity,
                SUM(o.quantity * o.unit_price) as total_sales,
                AVG(o.unit_price) as average_price
            FROM orders o
            INNER JOIN products p ON p.product_id = o.product_id
            INNER JOIN stores s ON s.store_id = p.store_id
            INNER JOIN categories c ON c.category_id = p.category_id
            USE INDEX (idx_order_analytics, idx_product_analytics)
        SQL;
        
        $where = [];
        $params = [];
        
        if ($storeId !== null) {
            $where[] = 's.store_id = ?';
            $params[] = $storeId;
        }
        
        if ($startDate) {
            $where[] = 'o.order_date >= ?';
            $params[] = $startDate;
        }
        
        if ($endDate) {
            $where[] = 'o.order_date <= ?';
            $params[] = $endDate;
        }
        
        if (!empty($where)) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }
        
        $sql .= ' GROUP BY p.product_id
            ORDER BY total_sales DESC';
        
        $statement = $db->prepare($sql);
        return $statement->execute($params)->fetchAll();
    }
} 