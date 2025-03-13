<?php

namespace Tempest\Web\Blog;

use DateTimeImmutable;
use Exception;
use League\CommonMark\Extension\FrontMatter\Output\RenderedContentWithFrontMatter;
use League\CommonMark\MarkdownConverter;
use Spatie\YamlFrontMatter\YamlFrontMatter;
use Tempest\Support\Arr\ImmutableArray;

use function Tempest\map;
use function Tempest\Support\arr;

final readonly class BlogRepository
{
    public function __construct(
        private MarkdownConverter $markdown,
    ) {
    }

    /**
     * @return ImmutableArray<\App\Front\Blog\BlogPost>
     */
    public function all(bool $loadContent = false): ImmutableArray
    {
        return arr(glob(__DIR__ . '/Content/*.md'))
            ->reverse()
            ->map(function (string $path) use ($loadContent) {
                $content = file_get_contents($path);

                preg_match('/\d+-\d+-\d+-(?<slug>.*)\.md/', $path, $matches);

                $slug = $matches['slug'];

                $data = [
                    'slug' => $slug,
                    'createdAt' => $this->parseDate($path),
                    ...YamlFrontMatter::parse($content)->matter(),
                ];

                if ($loadContent) {
                    $content = $this->parseContent($path);

                    $data['content'] = $content->getContent();
                }

                return $data;
            })
            ->mapTo(BlogPost::class)
            ->filter(fn (BlogPost $post) => $post->published);
    }

    public function find(string $slug): ?BlogPost
    {
        $path = glob(__DIR__ . "/Content/*{$slug}*.md")[0] ?? null;

        if (! $path) {
            return null;
        }

        $content = $this->parseContent($path);

        $data = [
            'slug' => $slug,
            'content' => $content->getContent(),
            'createdAt' => $this->parseDate($path),
            ...$content->getFrontMatter(),
        ];

        return map($data)->to(BlogPost::class);
    }

    private function parseContent(string $path): ?RenderedContentWithFrontMatter
    {
        $content = @file_get_contents($path);

        if (! $content) {
            return null;
        }

        $parsed = $this->markdown->convert($content);

        if (! ($parsed instanceof RenderedContentWithFrontMatter)) {
            throw new Exception("Missing frontmatter or content in {$path}");
        }

        return $parsed;
    }

    private function parseDate(string $path): DateTimeImmutable
    {
        preg_match('#\d+-\d+-\d+#', $path, $matches);

        $date = $matches[0] ?? null;

        return new DateTimeImmutable($date ?? 'now');
    }
}
