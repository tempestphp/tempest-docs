<?php

namespace App\Chapters;

use League\CommonMark\Extension\FrontMatter\Output\RenderedContentWithFrontMatter;
use League\CommonMark\MarkdownConverter;
use Spatie\YamlFrontMatter\YamlFrontMatter;

readonly class ChapterRepository
{
    public function __construct(
        private MarkdownConverter $markdown,
    ) {}

    public function find(string $slug): Chapter
    {
        $content = $this->getContent($slug);

        $markdown = $this->markdown->convert($content);

        $frontMatter = $markdown instanceof RenderedContentWithFrontMatter ? $markdown->getFrontMatter() : [
            'title' => $slug,
        ];

        return new Chapter(...[
            ...[
                'slug' => $slug,
                'body' => $markdown->getContent()
            ],
            ...$frontMatter,
        ]);
    }

    /**
     * @return \App\Chapters\Chapter[]
     */
    public function all(): array
    {
        return array_map(
            function (string $content) {
                $slug = pathinfo($content, PATHINFO_FILENAME);

                $content = $this->getContent($slug);

                /** @var array $frontMatter */
                $frontMatter = YamlFrontMatter::parse($content)->matter();

                $frontMatter['title'] ??= $slug;

                return new Chapter(...[
                    ...[
                        'slug' => $slug,
                        'body' => ''
                    ],
                    ...$frontMatter,
                ]);
            },
            glob(__DIR__ . "/../Content/*.md"),
        );
    }

    private function getContent(string $slug): string
    {
        $path = glob(__DIR__ . "/../Content/{$slug}*.md")[0] ?? __DIR__ . "/../Content/{$slug}.md";

        return file_get_contents($path);
    }
}
