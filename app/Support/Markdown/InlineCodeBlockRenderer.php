<?php

namespace App\Support\Markdown;

use InvalidArgumentException;
use League\CommonMark\Extension\CommonMark\Node\Inline\Code;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use Stringable;
use Tempest\Highlight\Highlighter;

final class InlineCodeBlockRenderer implements NodeRendererInterface
{
    public function __construct(
        private Highlighter $highlighter = new Highlighter(),
    ) {
    }

    #[\Override]
    public function render(Node $node, ChildNodeRendererInterface $childRenderer): ?Stringable
    {
        if (! ($node instanceof Code)) {
            throw new InvalidArgumentException('Block must be instance of ' . Code::class);
        }

        preg_match('/^\{(?<match>[\w]+)}(?<code>.*)/', $node->getLiteral(), $match);

        $language = $match['match'] ?? 'php';
        $code = $match['code'] ?? $node->getLiteral();

        return '<code>' . $this->highlighter->parse($code, $language) . '</code>';
    }
}
