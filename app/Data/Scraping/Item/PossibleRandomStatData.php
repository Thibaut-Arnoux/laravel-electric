<?php

namespace App\Data\Scraping\Item;

use Spatie\LaravelData\Data;

class PossibleRandomStatData extends Data
{
    public function __construct(
        public string $parameter,
        public int|float $add,
        public int|float $addMax,
        public bool $rate,
    ) {}
}
