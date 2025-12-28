<?php

declare(strict_types=1);

namespace App\Web\CommandPalette;

final class IndexerConfig
{
    public function __construct(
        public array $indexerClasses = [],
    ) {}
}
