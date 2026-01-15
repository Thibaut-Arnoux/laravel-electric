<?php

namespace App\Data\Scraping;

use App\Enums\CategoryEnum;
use App\Enums\ElementEnum;
use App\Enums\RarityEnum;
use App\Enums\SexEnum;
use App\Enums\SubcategoryEnum;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapOutputName(SnakeCaseMapper::class)]
class ItemData extends Data
{
    /**
     * @param  array<string, string>  $name
     * @param  array<string, string>  $description
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
        public ?ElementEnum $element,
        public ?int $minDefense,
        public ?int $maxDefense,
        public CategoryEnum $category,
        public ?SubcategoryEnum $subcategory,
        public RarityEnum $rarity,
        public ?SexEnum $sex,
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
