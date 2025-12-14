<?php

namespace App\Markdown\CodeGroups;

use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Extension\ExtensionInterface;
use Override;

final class CodeGroupExtension implements ExtensionInterface
{
    #[Override]
    public function register(EnvironmentBuilderInterface $environment): void
    {
        $environment->addBlockStartParser(new CodeGroupBlockStartParser());
    }
}
