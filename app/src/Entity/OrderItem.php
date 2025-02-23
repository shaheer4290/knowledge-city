<?php

declare(strict_types=1);

namespace App\Entity;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Relation\BelongsTo;

#[Entity(table: 'order_items')]
class OrderItem
{
    #[Column(type: 'bigPrimary')]
    public int $orderItemId;
    
    #[Column(type: 'bigInteger')]
    public int $orderId;
    
    #[Column(type: 'bigInteger')]
    public int $productId;
    
    #[Column(type: 'integer')]
    public int $quantity;
    
    #[Column(type: 'decimal', precision: 10, scale: 2)]
    public float $unitPrice;
    
    #[Column(type: 'decimal', precision: 10, scale: 2)]
    public float $totalAmount;
    
    #[BelongsTo(target: Order::class)]
    public Order $order;
    
    #[BelongsTo(target: Product::class)]
    public Product $product;
    
    public function toArray(): array
    {
        return [
            'orderItemId' => $this->orderItemId,
            'orderId' => $this->orderId,
            'productId' => $this->productId,
            'quantity' => $this->quantity,
            'unitPrice' => $this->unitPrice,
            'totalAmount' => $this->totalAmount,
        ];
    }
} 