<?php

namespace App\Markdown;

use InvalidArgumentException;
use League\CommonMark\Extension\CommonMark\Node\Inline\Link;
use League\CommonMark\Extension\CommonMark\Renderer\Inline\LinkRenderer as InlineLinkRenderer;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Xml\XmlNodeRendererInterface;
use League\Config\ConfigurationAwareInterface;
use League\Config\ConfigurationInterface;
use Override;
use Stringable;

use function Tempest\Support\Regex\replace;

final class LinkRenderer implements NodeRendererInterface, XmlNodeRendererInterface, ConfigurationAwareInterface
{
    private ConfigurationInterface $config;

    #[Override]
    public function render(Node $node, ChildNodeRendererInterface $childRenderer): Stringable
    {
        if (! ($node instanceof Link)) {
            throw new InvalidArgumentException('Node must be instance of ' . Link::class);
        }

        // Replace .md at the end, before a / or a #
        $node->setUrl(
            replace($node->getUrl(), '/\.md((?=[\/#?])|$)/', ''),
        );

        $renderer = new InlineLinkRenderer();
        $renderer->setConfiguration($this->config);

        return $renderer->render($node, $childRenderer);
    }

    #[Override]
    public function setConfiguration(ConfigurationInterface $configuration): void
    {
        $this->config = $configuration;
    }

    #[Override]
    public function getXmlTagName(Node $node): string
    {
        return 'link';
    }

    #[Override]
    public function getXmlAttributes(Node $node): array
    {
        if (! ($node instanceof Link)) {
            throw new InvalidArgumentException('Node must be instance of ' . Link::class);
        }

        return [
            'destination' => $node->getUrl(),
            'title' => $node->getTitle() ?? '',
        ];
    }
}
