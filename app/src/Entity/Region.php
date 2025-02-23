<?php

declare(strict_types=1);

namespace App\Entity;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Relation\HasMany;

#[Entity(table: 'regions')]
class Region
{
    #[Column(type: 'bigPrimary')]
    public int $regionId;
    
    #[Column(type: 'string', length: 200)]
    public string $regionName;
    
    #[HasMany(target: Store::class, innerKey: 'regionId', outerKey: 'regionId')]
    public array $stores;
    
    public function __construct(string $regionName)
    {
        $this->regionName = $regionName;
        $this->stores = [];
    }
    
    public function toArray(): array
    {
        return [
            'regionId' => $this->regionId,
            'regionName' => $this->regionName,
        ];
    }
} 