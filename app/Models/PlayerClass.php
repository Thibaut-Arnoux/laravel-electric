<?php

namespace App\Models;

use App\Enums\ClassTypeEnum;
use App\Traits\ElectricAttributes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class PlayerClass extends Model
{
    use ElectricAttributes;
    use HasTranslations;
    use HasUuids;

    protected $table = 'player_classes';

    protected $guarded = ['id'];

    /** @var string[] */
    protected static array $shape = ['*'];

    /** @var string[] */
    public array $translatable = ['name'];

    protected function casts(): array
    {
        return [
            'type' => ClassTypeEnum::class,
            'auto_attack_factors' => 'array',
        ];
    }

    /**
     * @return BelongsTo<PlayerClass, $this>
     */
    public function parentClass(): BelongsTo
    {
        return $this->belongsTo(PlayerClass::class, 'parent_class_id', 'class_id');
    }

    /**
     * @return HasMany<PlayerClass, $this>
     */
    public function childClasses(): HasMany
    {
        return $this->hasMany(PlayerClass::class, 'parent_class_id', 'class_id');
    }
}
