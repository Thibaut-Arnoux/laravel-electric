<?php

namespace App\Data\Scraping\Item;

use Spatie\LaravelData\Data;

class SpawnData extends Data
{
    public function __construct(
        public int $world,
        public int $left,
        public int $top,
        public int $right,
        public int $bottom,
        public ?int $continent = null,
    ) {}
}
