<?php

declare(strict_types=1);

namespace App\Entity;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Relation\BelongsTo;

#[Entity(table: 'products')]
class Product
{
    #[Column(type: 'bigPrimary')]
    public int $productId;
    
    #[Column(type: 'bigInteger')]
    public int $categoryId;
    
    #[Column(type: 'string', length: 200)]
    public string $productName;
    
    #[Column(type: 'bigInteger', name: 'category_categoryId')]
    public int $categoryCategoryId;
    
    #[BelongsTo(target: Category::class, innerKey: 'categoryId', outerKey: 'categoryId')]
    public Category $category;
    
    public function __construct()
    {
        // Initialize category_categoryId to match categoryId
        $this->categoryCategoryId = 0;
    }
    
    public function toArray(): array
    {
        return [
            'productId' => $this->productId,
            'categoryId' => $this->categoryId,
            'productName' => $this->productName,
            'categoryCategoryId' => $this->categoryCategoryId
        ];
    }
}