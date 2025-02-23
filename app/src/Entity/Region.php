<?php

declare(strict_types=1);

namespace App\Entity;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;

#[Entity(table: 'regions')]
class Region
{
    #[Column(type: 'bigPrimary', name: 'region_id')]
    public int $regionId;
    
    #[Column(type: 'string', name: 'region_name')]
    public string $regionName;
    
    public function toArray(): array
    {
        return [
            'region_id' => $this->regionId,
            'region_name' => $this->regionName
        ];
    }
} 