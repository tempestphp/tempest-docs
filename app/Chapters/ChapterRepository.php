<?php

namespace App\Chapters;

use League\CommonMark\MarkdownConverter;

readonly class ChapterRepository
{
    public function __construct(
        private MarkdownConverter $markdown,
    ) {}

    public function find(string $slug): Chapter
    {
        $path = glob(__DIR__ . "/../Content/{$slug}*.md")[0] ?? __DIR__ . "/../Content/{$slug}.md";

        $content = file_get_contents($path);

        $this->markdown->convert($content);

        return Chapter::fromMarkdown($slug, $this->markdown->convert($content));
    }

    /**
     * @return \App\Chapters\Chapter[]
     */
    public function all(): array
    {
        return array_map(
            function (string $content) {
                $slug = pathinfo($content, PATHINFO_FILENAME);

                return $this->find($slug);
            },
            glob(__DIR__ . "/../Content/*.md"),
        );
    }
}
