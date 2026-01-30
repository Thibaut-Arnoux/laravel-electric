<?php

namespace App\Data\Scraping\Item;

use Spatie\LaravelData\Data;

class ContentData extends Data
{
    public function __construct(
        public int $item,
        public int $count,
    ) {}
}
