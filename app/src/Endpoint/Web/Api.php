<?php

declare(strict_types=1);

namespace App\Endpoint\Web;

use App\Entity\Order;
use App\Entity\Store;
use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Region;
use Cycle\ORM\ORMInterface;
use Spiral\Http\ResponseWrapper;
use Spiral\Router\Annotation\Route;
use Spiral\Router\Annotation\Parameter;
use Cycle\ORM\Select;
use Cycle\Database\DatabaseProviderInterface;
use Cycle\Database\Query\SelectQuery;
use Cycle\Database\Injection\Fragment;
use App\Service\SalesAnalyticsService;

final class Api
{
    public function __construct(
        private readonly ResponseWrapper $response,
        private readonly ORMInterface $orm,
        private readonly DatabaseProviderInterface $dbal,
        private readonly SalesAnalyticsService $salesAnalytics
    ) {
    }
    
    #[Route(route: '/show-orders', name: 'show-orders', methods: 'GET')]
    public function showOrders(ORMInterface $orm): \Psr\Http\Message\ResponseInterface
    {
        $repository = $orm->getRepository(Order::class);
        
        $orders = $repository->findAll();
        $data = array_map(fn(Order $order) => $order->toArray(), $orders);
        
        return $this->response->json(['data' => $data]);
    }

    #[Route(route: '/monthly-sales-by-region', name: 'monthly-sales-by-region', methods: 'GET')]
    public function monthlySalesByRegion(
        #[Parameter('start_date')] ?string $startDate = null,
        #[Parameter('end_date')] ?string $endDate = null
    ): \Psr\Http\Message\ResponseInterface {
        $results = $this->salesAnalytics->getMonthlySalesByRegion($startDate, $endDate);
        return $this->response->json(['data' => $results]);
    }

    #[Route(route: '/top-categories-by-store', name: 'top-categories-by-store', methods: 'GET')]
    public function topCategoriesByStore(
        #[Parameter('store_id')] ?int $storeId = null,
        #[Parameter('limit')] ?int $limit = 5
    ): \Psr\Http\Message\ResponseInterface {
        $results = $this->salesAnalytics->getTopCategoriesByStore($storeId, $limit);
        return $this->response->json(['data' => $results]);
    }
}