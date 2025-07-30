<?php

namespace App\Web\CommandPalette;

use Tempest\Support\Arr\ImmutableArray;

interface Indexer
{
    public function index(): ImmutableArray;
}
