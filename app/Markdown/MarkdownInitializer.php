<?php

namespace App\Markdown;

use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\CommonMark\Node\Block\FencedCode;
use League\CommonMark\Extension\CommonMark\Node\Inline\Code;
use League\CommonMark\Extension\FrontMatter\FrontMatterExtension;
use League\CommonMark\MarkdownConverter;
use Tempest\Container\Container;
use Tempest\Container\Initializer;
use Tempest\Container\Singleton;

#[Singleton]
final readonly class MarkdownInitializer implements Initializer
{
    public function initialize(Container $container): MarkdownConverter
    {
        $environment = new Environment();

        $environment
            ->addExtension(new CommonMarkCoreExtension())
            ->addExtension(new FrontMatterExtension())
            ->addRenderer(FencedCode::class, new HighlightCodeBlockRenderer())
//            ->addRenderer(Code::class, new HighlightInlineCodeRenderer())
;
        return new MarkdownConverter($environment);
    }
}