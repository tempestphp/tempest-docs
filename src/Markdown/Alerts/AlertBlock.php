<?php

namespace App\Markdown\Alerts;

use League\CommonMark\Node\Block\AbstractBlock;

final class AlertBlock extends AbstractBlock
{
    public function __construct(
        public readonly string $alertType,
        public readonly ?string $icon,
        public readonly ?string $title,
    ) {
        parent::__construct();
    }
}
