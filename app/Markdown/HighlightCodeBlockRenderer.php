<?php

namespace App\Markdown;

use InvalidArgumentException;
use League\CommonMark\Extension\CommonMark\Node\Block\FencedCode;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use Spatie\CommonMarkHighlighter\FencedCodeRenderer;

class HighlightCodeBlockRenderer extends FencedCodeRenderer
{
    public function render(Node $node, ChildNodeRendererInterface $childRenderer)
    {
        if (! $node instanceof FencedCode) {
            throw new InvalidArgumentException('Block must be instance of ' . FencedCode::class);
        }

        $element = parent::render($node, $childRenderer);

        $content = $element->getContents();

        $content = preg_replace_callback('/\&lt;[\w\s\<\"\=\-\>\/]+hljs[\w\s\<\"\=\-\>\/]+/', function ($match) {
            $match = str_replace('<span class="hljs-title">', '', $match[0] ?? '');

            $match = str_replace('</span>', '', $match);

            return $match;
        }, $content);

        $content = str_replace('&lt;/hljs&gt;', '</span>', $content);

        $lines = explode(PHP_EOL, $content);

        $regex = '/\&lt\;hljs([\w\s]+)&gt;/';

        foreach ($lines as $index => $line) {
            $line = preg_replace_callback($regex, function ($matches) {
                $class = $matches[1] ?? '';

                return "<span class=\"hljs-highlight {$class}\">";
            }, $line);

            $lines[$index] = $line;
        }

        unset($lines[array_key_last($lines)]);

        $element->setContents(implode(PHP_EOL, $lines) . '</code>');

        return $element;
    }
}
