<?php

namespace App\Markdown\Alerts;

use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\HtmlRenderer;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Util\HtmlElement;
use Tempest\View\ViewRenderer;

use function Tempest\get;
use function Tempest\view;

final class AlertBlockRenderer implements NodeRendererInterface
{
    #[\Override]
    public function render(Node $node, ChildNodeRendererInterface $childRenderer): mixed
    {
        if (! ($node instanceof AlertBlock)) {
            throw new \InvalidArgumentException('Incompatible node type: ' . get_class($node));
        }

        if (! ($childRenderer instanceof HtmlRenderer)) {
            throw new \InvalidArgumentException('Incompatible renderer type: ' . get_class($childRenderer));
        }

        $iconName = match ($node->icon) {
            'false' => null,
            null => match ($node->alertType) {
                'warning' => 'tabler:exclamation-circle',
                'info' => 'tabler:info-circle',
                'success' => 'tabler:check-circle',
                'error' => 'tabler:exclamation-circle',
                default => null,
            },
            default => $node->icon,
        };

        $icon = $iconName
            ? get(ViewRenderer::class)->render(view('<x-icon :name="$name" class="alert-icon" />', name: $iconName))
            : null;

        $content = new HtmlElement(
            tagName: 'div',
            attributes: ['class' => 'alert-wrapper'],
            contents: [
                $icon ? new HtmlElement('div', attributes: ['class' => 'alert-icon-wrapper'], contents: $icon) : null,
                new HtmlElement(
                    tagName: 'div',
                    attributes: ['class' => 'alert-content'],
                    contents: [
                        $node->title ? new HtmlElement('span', attributes: ['class' => 'alert-title'], contents: $node->title) : null,
                        $childRenderer->renderNodes($node->children()),
                    ],
                ),
            ],
        );

        return new HtmlElement(
            'div',
            ['class' => "alert alert-{$node->alertType}"],
            $content,
        );
    }
}
