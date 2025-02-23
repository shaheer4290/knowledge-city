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
                DATE_FORMAT(orders.order_date, "%Y-%m") as month,
                regions.region_name,
                SUM(orders.quantity * orders.unit_price) as total_sales
            FROM orders
            INNER JOIN stores ON stores.store_id = orders.store_storeId
            INNER JOIN regions ON regions.region_id = stores.region_id';
            
        $params = [];
        $where = [];
        
        if ($startDate) {
            $where[] = 'orders.order_date >= ?';
            $params[] = $startDate;
        }
        
        if ($endDate) {
            $where[] = 'orders.order_date <= ?';
            $params[] = $endDate;
        }
        
        if (!empty($where)) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }
        
        $sql .= ' GROUP BY DATE_FORMAT(orders.order_date, "%Y-%m"), regions.region_name
            ORDER BY month ASC, total_sales DESC';
        
        return $db->query($sql, $params)->fetchAll();
    }

    public function getTopCategoriesByStore(?int $storeId = null, ?int $limit = null): array
    {
        $db = $this->dbal->database();
        
        $sql = <<<SQL
            SELECT 
                stores.store_name,
                categories.category_name,
                SUM(orders.quantity * orders.unit_price) as total_sales,
                SUM(orders.quantity) as total_quantity
            FROM orders
            INNER JOIN stores ON stores.store_id = orders.store_storeId
            INNER JOIN products ON products.product_id = orders.product_productId
            INNER JOIN categories ON categories.category_id = products.category_id
        SQL;
            
        $params = [];
        
        if ($storeId !== null) {
            $sql .= ' WHERE stores.store_id = ' . (int)$storeId;
        }
        
        $sql .= ' GROUP BY stores.store_id, categories.category_id
            ORDER BY stores.store_name ASC, total_sales DESC';
            
        if ($limit !== null) {
            $sql .= ' LIMIT ' . max(1, (int)$limit);
        }
        
        return $db->query($sql)->fetchAll();
    }
} 