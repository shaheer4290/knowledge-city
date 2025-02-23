<?php

declare(strict_types=1);

namespace App\Entity;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Relation\BelongsTo;

#[Entity(table: 'stores')]
class Store
{
    #[Column(type: 'bigPrimary')]
    public int $storeId;
    
    #[Column(type: 'bigInteger')]
    public int $regionId;
    
    #[Column(type: 'string', length: 200)]
    public string $storeName;
    
    #[BelongsTo(target: Region::class, innerKey: 'regionId', outerKey: 'regionId')]
    public Region $region;
}