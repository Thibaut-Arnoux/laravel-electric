<?php

namespace App\Traits;

trait ElectricAttributes
{
    public static function getShape(): array
    {
        return is_array(static::$shape) ? static::$shape : [];
    }

    public static function getImplodedShape(string $separator = ','): string
    {
        return implode(separator: $separator, array: static::getShape());
    }
}
