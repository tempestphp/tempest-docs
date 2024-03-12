<?php

namespace App\Chapters;

use App\Front\DocsController;
use League\CommonMark\Extension\FrontMatter\Output\RenderedContentWithFrontMatter;
use League\CommonMark\Output\RenderedContentInterface;
use function Tempest\uri;

final readonly class Chapter
{
    public function __construct(
        public string $slug,
        public string $body,
        public string $title,
    ) {
    }

    public static function fromMarkdown(string $slug, RenderedContentInterface|RenderedContentWithFrontMatter $markdown): self
    {
        $frontMatter = $markdown instanceof RenderedContentWithFrontMatter ? $markdown->getFrontMatter() : [
            'title' => 'Unknown',
        ];

        return new self(...[
            ...[
                'slug' => $slug,
                'body' => $markdown->getContent()
            ],
            ...$frontMatter,
        ]);
    }

    public function getUri(): string
    {
        return uri([DocsController::class, 'show'], slug: $this->slug);
    }
}
