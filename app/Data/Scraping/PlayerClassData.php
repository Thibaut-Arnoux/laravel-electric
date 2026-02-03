<?php

namespace App\Data\Scraping;

use App\Enums\ClassTypeEnum;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapOutputName(SnakeCaseMapper::class)]
class PlayerClassData extends Data
{
    /**
     * @param  array<string, string>  $name
     * @param  array<string, float>  $autoAttackFactors
     */
    public function __construct(
        #[MapOutputName('class_id')]
        public int $id,
        public array $name,
        public ?ClassTypeEnum $type,
        public string $tree,
        #[MapOutputName('parent_class_id')]
        public ?int $parent,
        public string $icon,
        public int $minLevel,
        public int $maxLevel,
        public float $hp,
        #[MapOutputName('max_hp')]
        public string $maxHP,
        public float $fp,
        #[MapOutputName('max_fp')]
        public string $maxFP,
        public float $mp,
        #[MapOutputName('max_mp')]
        public string $maxMP,
        public float $defense,
        public float $magicDefenseStaFactor,
        public float $magicDefenseIntFactor,
        public int $attackSpeed,
        public float $block,
        public int $critical,
        public array $autoAttackFactors
    ) {}
}
