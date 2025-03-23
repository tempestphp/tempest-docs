<?php

namespace App\Markdown\Alerts;

use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\HtmlRenderer;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Util\HtmlElement;

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

        $cssClass = 'alert alert-' . $node->alertType;
        $content = new HtmlElement(
            tagName: 'div',
            attributes: ['class' => 'alert-wrapper'],
            contents: [
                $node->title ? new HtmlElement('span', attributes: ['class' => 'alert-title'], contents: $node->title) : null,
                $childRenderer->renderNodes($node->children()),
            ],
        );

        return new HtmlElement(
            'div',
            ['class' => $cssClass],
            $content,
        );
    }
}
