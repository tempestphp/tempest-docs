<?php

namespace App\Web\Documentation;

use Tempest\Support\IsEnumHelper;

enum Version: string
{
    use IsEnumHelper;

    case MAIN = 'main';
    // case VERSION_1 = '1.x';

    public static function default(): self
    {
        return self::MAIN;
    }
}
