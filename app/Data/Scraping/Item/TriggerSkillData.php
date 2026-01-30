<?php

namespace App\Data\Scraping\Item;

use Spatie\LaravelData\Data;

class TriggerSkillData extends Data
{
    public function __construct(
        public int $skill,
        public bool $onTarget,
    ) {}
}
