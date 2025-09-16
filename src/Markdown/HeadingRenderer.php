<?php

namespace App\Markdown;

use Override;
use InvalidArgumentException;
use League\CommonMark\Extension\CommonMark\Node\Block\Heading;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Util\HtmlElement;
use Tempest\Support\Str\ImmutableString;

final class HeadingRenderer implements NodeRendererInterface
{
    #[Override]
    public function render(Node $node, ChildNodeRendererInterface $childRenderer): ?string
    {
        if (! ($node instanceof Heading)) {
            throw new InvalidArgumentException('Block must be instance of ' . Heading::class);
        }

        $tag = 'h' . $node->getLevel();
        $attrs = $node->data->get('attributes');
        $slug = new ImmutableString($childRenderer->renderNodes($node->children()))
            ->stripTags()
            ->kebab()
            ->toString();

        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 9h14M5 15h14M11 4L7 20M17 4l-4 16"/></svg>';

        return new HtmlElement(
            tagName: $tag,
            attributes: [
                ...$attrs,
                'id' => $slug,
            ],
            contents: new HtmlElement(
                tagName: 'a',
                attributes: ['href' => '#' . $slug, 'class' => 'heading-permalink'],
                contents: [
                    new HtmlElement('span', contents: $svg),
                    $childRenderer->renderNodes($node->children()),
                ],
            ),
        );
    }
}
