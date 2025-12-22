<?php

namespace App\Traits;

use Illuminate\Support\Facades\Schema;

trait ElectricAttributes
{
    /** @return string[] */
    public static function getTableColumns(): array
    {
        return Schema::getColumnListing((static::make())->getTable());
    }

    /**
     * The attributes authorized and exported by default to Eletric.
     *
     * @return string[]
     */
    public static function getShape(): array
    {
        // use property_exists and not is_array as spatie does for translation because it is static and not instance property
        if (property_exists(static::class, 'shape') === false) {
            return [];
        }

        /** @disregard P1014 */
        if (static::$shape === ['*']) {
            return static::getTableColumns();
        }

        /** @disregard P1014 */
        return static::$shape;
    }

    public static function getImplodedShape(string $separator = ','): string
    {
        return implode(separator: $separator, array: static::getShape());
    }
}
