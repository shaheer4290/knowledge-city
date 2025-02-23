<?php

declare(strict_types=1);

namespace App\Entity;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;

#[Entity(table: 'orders')]
class Order
{
    #[Column(type: 'bigPrimary', name: 'order_id')]
    public int $orderId;
    
    #[Column(type: 'bigInteger', name: 'product_id')]
    public int $productId;
    
    #[Column(type: 'integer')]
    public int $quantity;
    
    #[Column(type: 'decimal', precision: 10, scale: 2, name: 'unit_price')]
    public float $unitPrice;
    
    #[Column(type: 'date', name: 'order_date')]
    public \DateTimeInterface $orderDate;
    
    #[Column(type: 'date', name: 'month_key')]
    public \DateTimeInterface $monthKey;
    
    public function toArray(): array
    {
        return [
            'order_id' => $this->orderId,
            'product_id' => $this->productId,
            'quantity' => $this->quantity,
            'unit_price' => $this->unitPrice,
            'order_date' => $this->orderDate->format('Y-m-d'),
            'month_key' => $this->monthKey->format('Y-m-d')
        ];
    }
}