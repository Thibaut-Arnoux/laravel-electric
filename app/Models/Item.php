<?php

namespace App\Models;

use App\Enums\CategoryEnum;
use App\Enums\ElementEnum;
use App\Enums\RarityEnum;
use App\Enums\SexEnum;
use App\Enums\SubcategoryEnum;
use App\Traits\ElectricAttributes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Item extends Model
{
    use ElectricAttributes;
    use HasTranslations;
    use HasUuids;

    protected $guarded = ['id'];

    /** @var string[] */
    protected static array $shape = ['*'];

    /** @var string[] */
    public array $translatable = ['name', 'description'];

    protected function casts(): array
    {
        return [
            'spawns' => 'array',
            'element' => ElementEnum::class,
            'category' => CategoryEnum::class,
            'subcategory' => SubcategoryEnum::class,
            'rarity' => RarityEnum::class,
            'sex' => SexEnum::class,
        ];
    }
}
