<?php

namespace App\Enums;

enum RarityEnum: string
{
    case Common = 'common';
    case Rare = 'rare';
    case Ultimate = 'ultimate';
    case Uncommon = 'uncommon';
    case Unique = 'unique';
    case VeryRare = 'veryrare';
}
