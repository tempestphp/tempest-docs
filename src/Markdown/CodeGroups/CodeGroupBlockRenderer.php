<?php

declare(strict_types=1);

namespace App\Markdown\CodeGroups;

use App\Markdown\CodeBlocks\CodeBlockRenderer;
use App\Markdown\ResolvesInfoWords;
use InvalidArgumentException;
use League\CommonMark\Extension\CommonMark\Node\Block\FencedCode;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Util\HtmlElement;
use Override;

final class CodeGroupBlockRenderer implements NodeRendererInterface
{
    use ResolvesInfoWords;

    public function __construct(
        private CodeBlockRenderer $code_block_renderer,
    ) {}

    #[Override]
    public function render(Node $node, ChildNodeRendererInterface $child_renderer): mixed
    {
        if (! ($node instanceof CodeGroupBlock)) {
            throw new InvalidArgumentException('Incompatible node type: ' . $node::class);
        }

        $tabs = [];
        $panels = [];
        $index = 0;

        foreach ($node->children() as $child) {
            if (! ($child instanceof FencedCode)) {
                continue;
            }

            $info_words = $this->getInfoWords($child);
            $filename = $info_words[1] ?? "Tab {$index}";

            $is_active = $index === 0;
            $tab_id = 'tab-' . md5($filename . $index);
            $panel_id = 'panel-' . md5($filename . $index);

            $tabs[] = new HtmlElement(
                tagName: 'button',
                attributes: [
                    'class' => 'code-group-tab' . ($is_active ? ' active' : ''),
                    'role' => 'tab',
                    'aria-selected' => $is_active ? 'true' : 'false',
                    'aria-controls' => $panel_id,
                    'id' => $tab_id,
                    'data-panel' => $panel_id,
                ],
                contents: $filename,
            );

            $rendered_code = $this->code_block_renderer->render($child, $child_renderer);

            $panels[] = new HtmlElement(
                tagName: 'div',
                attributes: array_filter([
                    'class' => 'code-group-panel' . ($is_active ? ' active' : ''),
                    'role' => 'tabpanel',
                    'aria-labelledby' => $tab_id,
                    'id' => $panel_id,
                    'hidden' => $is_active ? null : 'hidden',
                ]),
                contents: $rendered_code,
            );

            $index++;
        }

        if ($tabs === []) {
            return '';
        }

        $tab_list = new HtmlElement(
            tagName: 'div',
            attributes: [
                'class' => 'code-group-tabs',
                'role' => 'tablist',
            ],
            contents: $tabs,
        );

        return new HtmlElement(
            tagName: 'div',
            attributes: ['class' => 'code-group'],
            contents: [$tab_list, ...$panels],
        );
    }
}
