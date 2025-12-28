<?php

declare(strict_types=1);

namespace App\Markdown;

final class MarkdownPost
{
    public function __construct(
        public string $content,
        public ?string $title = null,
        public ?string $description = null,
    ) {}

    /**
     * @return array<string, array{title: string, children: array<string, string>}>
     */
    public function getSubChapters(): array
    {
        return SubChapterExtractor::extract($this->content);
    }
}
