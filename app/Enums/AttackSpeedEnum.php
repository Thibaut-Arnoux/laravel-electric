<?php

namespace App\Enums;

enum AttackSpeedEnum: string
{
    case Fast = 'fast';
    case Normal = 'normal';
    case Slow = 'slow';
    case VeryFast = 'veryfast';
    case VerySlow = 'veryslow';
}
