<?php

declare(strict_types=1);

namespace App\Entity;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Relation\HasMany;

#[Entity(table: 'categories')]
class Category
{
    #[Column(type: 'bigPrimary')]
    public int $categoryId;
    
    #[Column(type: 'string', length: 200)]
    public string $categoryName;
    
    #[HasMany(target: Product::class, innerKey: 'categoryId', outerKey: 'categoryId')]
    public array $products;
    
    public function __construct()
    {
        $this->products = [];
    }
    
    public function toArray(): array
    {
        return [
            'categoryId' => $this->categoryId,
            'categoryName' => $this->categoryName,
        ];
    }
} 