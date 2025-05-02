<?php

declare(strict_types=1);

namespace App\Markdown;

use App\Markdown\Alerts\AlertExtension;
use App\Markdown\CodeBlockRenderer;
use App\Markdown\Symbols\AttributeParser;
use App\Markdown\Symbols\FqcnParser;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\Attributes\AttributesExtension;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\CommonMark\Node\Block\FencedCode;
use League\CommonMark\Extension\CommonMark\Node\Block\Heading;
use League\CommonMark\Extension\CommonMark\Node\Inline\Code;
use League\CommonMark\Extension\CommonMark\Node\Inline\Link;
use League\CommonMark\Extension\FrontMatter\FrontMatterExtension;
use League\CommonMark\MarkdownConverter;
use Tempest\Container\Container;
use Tempest\Container\Initializer;
use Tempest\Container\Singleton;
use Tempest\Highlight\Highlighter;

final readonly class MarkdownInitializer implements Initializer
{
    #[\Override]
    #[Singleton]
    public function initialize(Container $container): MarkdownConverter
    {
        $environment = new Environment();
        $highlighter = $container->get(Highlighter::class, tag: 'project');

        $environment
            ->addExtension(new CommonMarkCoreExtension())
            ->addExtension(new FrontMatterExtension())
            ->addExtension(new AttributesExtension())
            ->addExtension(new AlertExtension())
            ->addInlineParser(new TempestPackageParser())
            ->addInlineParser(new FqcnParser())
            ->addInlineParser(new AttributeParser())
            ->addInlineParser(new HandleParser())
            ->addRenderer(FencedCode::class, new CodeBlockRenderer($highlighter))
            ->addRenderer(Code::class, new InlineCodeBlockRenderer($highlighter))
            ->addRenderer(Link::class, new LinkRenderer())
            ->addRenderer(Heading::class, new HeadingRenderer());

        return new MarkdownConverter($environment);
    }
}
