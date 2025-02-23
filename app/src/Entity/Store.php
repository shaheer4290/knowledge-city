<?php

declare(strict_types=1);

namespace App\Entity;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;

#[Entity(table: 'stores')]
class Store
{
    #[Column(type: 'bigPrimary', name: 'store_id')]
    public int $storeId;
    
    #[Column(type: 'bigInteger', name: 'region_id')]
    public int $regionId;
    
    #[Column(type: 'string', length: 255, name: 'store_name')]
    public string $storeName;
    
    public function toArray(): array
    {
        return [
            'store_id' => $this->storeId,
            'region_id' => $this->regionId,
            'store_name' => $this->storeName
        ];
    }
}