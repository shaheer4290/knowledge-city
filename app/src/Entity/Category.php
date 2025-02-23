<?php

declare(strict_types=1);

namespace App\Entity;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;

#[Entity(table: 'categories')]
class Category
{
    #[Column(type: 'bigPrimary', name: 'category_id')]
    public int $categoryId;
    
    #[Column(type: 'string', name: 'category_name')]
    public string $categoryName;
    
    public function toArray(): array
    {
        return [
            'category_id' => $this->categoryId,
            'category_name' => $this->categoryName
        ];
    }
} 