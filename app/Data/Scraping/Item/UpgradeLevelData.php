<?php

namespace App\Data\Scraping\Item;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;

class UpgradeLevelData extends Data
{
    /**
     * @param  array<int, AbilityData>  $abilities
     */
    public function __construct(
        public int $upgradeLevel,
        public int $requiredLevel,
        #[DataCollectionOf(AbilityData::class)]
        public array $abilities,
    ) {}
}
