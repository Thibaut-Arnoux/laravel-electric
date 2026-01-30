<?php

namespace App\Data\Scraping\Item;

use Spatie\LaravelData\Data;

class AbilityData extends Data
{
    public function __construct(
        public string $parameter,
        public ?bool $rate = null,
        public int|float|null $add = null,
        public int|float|null $set = null,
    ) {}
}
