<?php

declare(strict_types=1);

namespace Tempest\Web\Documentation;

use League\CommonMark\Extension\FrontMatter\Output\RenderedContentWithFrontMatter;
use League\CommonMark\MarkdownConverter;
use Spatie\YamlFrontMatter\YamlFrontMatter;
use Tempest\Support\Arr\ImmutableArray;

use function Tempest\Support\arr;
use function Tempest\Support\str;

final readonly class ChapterRepository
{
    public function __construct(
        private MarkdownConverter $markdown,
    ) {
    }

    public function find(string $version, string $category, string $slug): ?Chapter
    {
        $path = glob(__DIR__ . "/content/{$version}/{$category}/*{$slug}*.md")[0] ?? null;

        if (! $path) {
            return null;
        }

        $markdown = $this->markdown->convert(file_get_contents($path));

        if (! ($markdown instanceof RenderedContentWithFrontMatter)) {
            throw new \RuntimeException(sprintf('Documentation entry [%s] is missing a frontmatter.', $path));
        }

        ['title' => $title, 'category' => $category, 'description' => $description] = $markdown->getFrontMatter() + ['description' => null];

        return new Chapter(
            version: $version,
            category: $category,
            slug: $slug,
            body: $markdown->getContent(),
            title: $title,
            description: $description ?? null,
        );
    }

    /**
     * @return ImmutableArray<Chapter>
     */
    public function all(string $version, string $category = '*'): ImmutableArray
    {
        return arr(glob(__DIR__ . "/content/{$version}/{$category}/*.md"))
            ->map(function (string $path) use ($version) {
                $content = file_get_contents($path);
                $category = str($path)->beforeLast('/')->afterLast('/');

                preg_match('/(?<index>\d+-)?(?<slug>.*)\.md/', pathinfo($path, PATHINFO_BASENAME), $matches);

                return [
                    'version' => $version,
                    'slug' => $matches['slug'],
                    'index' => $matches['index'],
                    'category' => $category->toString(),
                    ...YamlFrontMatter::parse($content)->matter(),
                ];
            })
            ->mapTo(Chapter::class);
    }
}
