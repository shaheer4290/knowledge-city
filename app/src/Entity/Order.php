<?php

declare(strict_types=1);

namespace App\Entity;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Relation\BelongsTo;

#[Entity(table: 'orders')]
class Order
{
    #[Column(type: 'bigPrimary')]
    public int $orderId;
    
    #[Column(type: 'bigInteger')]
    public int $customerId;
    
    #[Column(type: 'bigInteger')]
    public int $productId;
    
    #[Column(type: 'integer')]
    public int $quantity;
    
    #[Column(type: 'decimal', precision: 10, scale: 2)]
    public float $unitPrice;
    
    #[Column(type: 'date')]
    public \DateTimeInterface $orderDate;
    
    #[Column(type: 'bigInteger')]
    public int $storeId;
    
    #[Column(type: 'bigint', name: 'store_storeId')]
    public int $store_storeId;
    
    #[Column(type: 'bigint', name: 'product_productId')]
    public int $product_productId;
    
    #[BelongsTo(target: Store::class, innerKey: 'storeId', outerKey: 'storeId')]
    public Store $store;
    
    #[BelongsTo(target: Product::class, innerKey: 'productId', outerKey: 'productId')]
    public Product $product;
    
    public function __construct()
    {
        // Initialize the foreign key fields
        $this->store_storeId = 0;
        $this->product_productId = 0;
    }
    
    public function toArray(): array
    {
        return [
            'orderId' => $this->orderId,
            'customerId' => $this->customerId,
            'productId' => $this->productId,
            'quantity' => $this->quantity,
            'unitPrice' => $this->unitPrice,
            'orderDate' => $this->orderDate->format('Y-m-d'),
            'storeId' => $this->storeId,
            'store_storeId' => $this->store_storeId,
            'product_productId' => $this->product_productId
        ];
    }
}