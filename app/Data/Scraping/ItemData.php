<?php

namespace App\Data\Scraping;

use App\Data\Scraping\Item\AbilityData;
use App\Data\Scraping\Item\ContentData;
use App\Data\Scraping\Item\DismantleData;
use App\Data\Scraping\Item\LocationData;
use App\Data\Scraping\Item\PossibleRandomStatData;
use App\Data\Scraping\Item\SpawnData;
use App\Data\Scraping\Item\TriggerSkillData;
use App\Data\Scraping\Item\UpgradeLevelData;
use App\Enums\AttackSpeedEnum;
use App\Enums\CategoryEnum;
use App\Enums\ConsumedItemEnum;
use App\Enums\ElementEnum;
use App\Enums\RarityEnum;
use App\Enums\SexEnum;
use App\Enums\SubcategoryEnum;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapOutputName(SnakeCaseMapper::class)]
class ItemData extends Data
{
    /**
     * @param  array<string, string>  $name
     * @param  array<string, string>  $description
     * @param  array<int, SpawnData>  $spawns
     * @param  array<int, AbilityData>|null  $abilities
     * @param  array<int, TriggerSkillData>|null  $triggerSkill
     * @param  array<int, ContentData>|null  $contents
     * @param  array<int, DismantleData>|null  $dismantle
     * @param  array<int, PossibleRandomStatData>|null  $possibleRandomStats
     * @param  array<int, UpgradeLevelData>|null  $upgradeLevels
     */
    public function __construct(
        #[MapOutputName('item_id')] public int $id,
        public array $name,
        public array $description,
        public string $icon,
        public ?int $class,
        public int $level,
        public ?ElementEnum $element,
        public ?int $minDefense,
        public ?int $maxDefense,
        public ?int $minAttack,
        public ?int $maxAttack,
        public ?AttackSpeedEnum $attackSpeed,
        public ?float $attackSpeedValue,
        public ?int $attackRange,
        public ?bool $twoHanded,
        public ?int $additionalSkillDamage,
        public ?bool $ultimateConvertible,
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
        public ?int $duration,
        public ?int $transy,
        #[DataCollectionOf(SpawnData::class)]
        public array $spawns,
        #[DataCollectionOf(AbilityData::class)]
        public ?array $abilities,
        #[DataCollectionOf(TriggerSkillData::class)]
        public ?array $triggerSkill,
        public ?int $triggerSkillProbability,
        #[MapInputName('consumedMP')] public ?int $consumedMp,
        public ?ConsumedItemEnum $consumedItem,
        public ?float $cooldown,
        public ?float $casting,
        #[DataCollectionOf(ContentData::class)]
        public ?array $contents,
        #[DataCollectionOf(DismantleData::class)]
        public ?array $dismantle,
        #[DataCollectionOf(PossibleRandomStatData::class)]
        public ?array $possibleRandomStats,
        public ?int $elementAttack,
        public ?int $flightSpeed,
        public ?int $guildContribution,
        public ?LocationData $location,
        public ?int $minimumTargetItemLevel,
        public ?LocationData $blinkwingTarget,
        public ?int $coupleBankSlots,
        public ?int $coupleCheers,
        public ?int $coupleTeleports,
        public ?int $fishingLargeChance,
        public ?int $gatheringChance,
        #[DataCollectionOf(UpgradeLevelData::class)]
        public ?array $upgradeLevels,
    ) {}
}
