<?php

namespace App\Models;

use App\Traits\ElectricAttributes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Item extends Model
{
    use ElectricAttributes;
    use HasTranslations;
    use HasUuids;

    protected $casts = ['spawns' => 'array'];

    protected $guarded = ['id'];

    /** @var string[] */
    protected static array $shape = ['*'];

    /** @var string[] */
    public array $translatable = ['name', 'description'];
}
