<?php

declare(strict_types=1);

namespace App\Web\CommandPalette;

use Tempest\Support\Arr\ImmutableArray;

interface Indexer
{
    public function index(): ImmutableArray;
}
