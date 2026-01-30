<?php

namespace App\Data\Scraping\Item;

use Spatie\LaravelData\Data;

class DismantleData extends Data
{
    public function __construct(
        public int $count,
        public bool $savePiercing,
        public bool $saveElement,
        public bool $saveUpgrade,
        public ?int $inputUpgradeLevel = null,
        public ?int $item = null,
    ) {}
}
