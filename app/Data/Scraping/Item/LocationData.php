<?php

namespace App\Data\Scraping\Item;

use Spatie\LaravelData\Data;

class LocationData extends Data
{
    public function __construct(
        public int $world,
        public int|float $x,
        public int|float $y,
        public int|float $z,
        public ?int $continent = null,
    ) {}
}
