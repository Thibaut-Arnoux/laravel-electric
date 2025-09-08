<?php

namespace App\Data\Scraping;

use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapOutputName(SnakeCaseMapper::class)]
class ItemData extends Data
{
    /**
     * @param  array<string, string>  $name
     * @param  array<string, string|null>  $description
     * @param  array<int, array<string, int>>  $spawns
     */
    public function __construct(
        #[MapOutputName('item_id')]
        public int $id,
        public array $name,
        public array $description,
        public string $icon,
        public ?int $class,
        public int $level,
        public string $element,
        public ?int $minDefense,
        public ?int $maxDefense,
        public string $category,
        public ?string $subcategory,
        public string $rarity,
        public ?string $sex,
        public int $stack,
        public ?int $buyPrice,
        public int $sellPrice,
        public bool $consumable,
        public bool $premium,
        public bool $shining,
        public bool $tradable,
        public bool $deletable,
        public bool $durationRealTime,
        public ?int $transy,
        public array $spawns
    ) {}
}
