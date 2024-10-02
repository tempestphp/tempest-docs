<?php

namespace App\Front\Blog;

use DateTimeImmutable;
use League\CommonMark\Extension\FrontMatter\Output\RenderedContentWithFrontMatter;
use League\CommonMark\MarkdownConverter;
use League\CommonMark\Output\RenderedContent;
use Spatie\YamlFrontMatter\YamlFrontMatter;
use Tempest\Support\ArrayHelper;
use function Tempest\map;
use function Tempest\Support\arr;

final readonly class BlogRepository
{
    public function __construct(
        private MarkdownConverter $markdown,
    ) {}

    /**
     * @return ArrayHelper<\App\Front\Blog\BlogPost>
     */
    public function all(): ArrayHelper
    {
        return arr(glob(__DIR__ . '/Content/*.md'))
            ->map(function (string $path) {
                $content = file_get_contents($path);

                preg_match('/\d+-\d+-\d+-(?<slug>.*)\.md/', $path, $matches);

                $slug = $matches['slug'];

                return [
                    'slug' => $slug,
                    'createdAt' => $this->parseDate($path),
                    ...YamlFrontMatter::parse($content)->matter(),
                ];
            })
            ->mapTo(BlogPost::class);
    }

    public function find(string $slug): ?BlogPost
    {
        $path = glob(__DIR__ . "/Content/*{$slug}*.md")[0] ?? null;

        if (! $path) {
            return null;
        }

        $content = @file_get_contents($path);

        if (! $content) {
            return null;
        }

        /** @var RenderedContentWithFrontMatter $parsed */
        $parsed = $this->markdown->convert($content);

        $data = [
            'slug' => $slug,
            'content' => $parsed->getContent(),
            'createdAt' => $this->parseDate($path),
            ...$parsed->getFrontMatter()
        ];

        return map($data)->to(BlogPost::class);
    }

    private function parseSlug(string $path): string
    {
        ld($path);
    }

    private function parseDate(string $path): DateTimeImmutable
    {
        preg_match('#\d+-\d+-\d+#', $path, $matches);

        $date = $matches[0] ?? null;

        return new DateTimeImmutable($date ?? 'now');
    }
}