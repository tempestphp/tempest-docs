<?php

declare(strict_types=1);

namespace App\Front\Docs;

use League\CommonMark\Extension\FrontMatter\Output\RenderedContentWithFrontMatter;
use League\CommonMark\MarkdownConverter;
use Spatie\YamlFrontMatter\YamlFrontMatter;

readonly class DocsRepository
{
    public function __construct(
        private MarkdownConverter $markdown,
    ) {
    }

    public function find(string $category, string $slug): DocsChapter
    {
        $content = $this->getContent($category, $slug);

        $markdown = $this->markdown->convert($content);

        $frontMatter = $markdown instanceof RenderedContentWithFrontMatter ? $markdown->getFrontMatter() : [
            'title' => $slug,
        ];

        return new DocsChapter(...[
            ...[
                'category' => $category,
                'slug' => $slug,
                'body' => $markdown->getContent(),
            ],
            ...$frontMatter,
        ]);
    }

    /**
     * @return \App\Front\Docs\DocsChapter[]
     */
    public function all(string $category = '*'): array
    {
        return array_filter(array_map(
            function (string $path) {
                preg_match('/(?<category>[\w]+)\/(?<slug>[\w-]+)\.md/', $path, $matches);

                if (! isset($matches['slug'])) {
                    return null;
                }

                $slug = $matches['slug'];
                $category = $matches['category'];

                $content = file_get_contents($path);
                /** @var array $frontMatter */
                $frontMatter = YamlFrontMatter::parse($content)->matter();

                $frontMatter['title'] ??= $slug;

                return new DocsChapter(...[
                    ...[
                        'category' => $category,
                        'slug' => $slug,
                        'body' => '',
                    ],
                    ...$frontMatter,
                ]);
            },
            glob(__DIR__ . "/Content/{$category}/*.md"),
        ));
    }

    private function getContent(string $category, string $slug): string
    {
        $path = glob(__DIR__ . "/Content/{$category}/{$slug}*.md")[0] ?? __DIR__ . "/Content/{$slug}.md";

        return file_get_contents($path);
    }
}
