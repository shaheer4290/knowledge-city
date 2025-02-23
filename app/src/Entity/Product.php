<?php

declare(strict_types=1);

namespace App\Entity;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;

#[Entity(table: 'products')]
class Product
{
    #[Column(type: 'bigPrimary', name: 'product_id')]
    public int $productId;
    
    #[Column(type: 'bigInteger', name: 'store_id')]
    public int $storeId;
    
    #[Column(type: 'bigInteger', name: 'category_id')]
    public int $categoryId;
    
    #[Column(type: 'string', length: 255)]
    public string $name;
    
    #[Column(type: 'decimal(10,2)')]
    public float $price;
    
    public function toArray(): array
    {
        return [
            'product_id' => $this->productId,
            'store_id' => $this->storeId,
            'category_id' => $this->categoryId,
            'name' => $this->name,
            'price' => $this->price
        ];
    }
}