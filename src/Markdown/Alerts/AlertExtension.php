<?php

declare(strict_types=1);

namespace App\Markdown\Alerts;

use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Extension\ExtensionInterface;
use Override;

final class AlertExtension implements ExtensionInterface
{
    #[Override]
    public function register(EnvironmentBuilderInterface $environment): void
    {
        $environment->addBlockStartParser(new AlertBlockStartParser());
        $environment->addRenderer(AlertBlock::class, new AlertBlockRenderer());
    }
}
