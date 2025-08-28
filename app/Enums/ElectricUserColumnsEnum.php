<?php

namespace App\Enums;

enum ElectricUserColumnsEnum: string
{
    case ID = 'id';
    case NAME = 'name';
    case EMAIL = 'email';
    case CREATED_AT = 'created_at';
    case UPDATED_AT = 'updated_at';

    public static function values(): string
    {
        return implode(separator: ',', array: array_column(array: self::cases(), column_key: 'value'));
    }
}
