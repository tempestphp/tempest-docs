<?php

namespace App\Web\CommandPalette;

final class IndexerConfig
{
    public function __construct(
        public array $indexerClasses = [],
    ) {}
}