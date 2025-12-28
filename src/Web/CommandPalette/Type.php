<?php

declare(strict_types=1);

namespace App\Web\CommandPalette;

enum Type: string
{
    case URI = 'uri';
    case JAVASCRIPT = 'js';
}
