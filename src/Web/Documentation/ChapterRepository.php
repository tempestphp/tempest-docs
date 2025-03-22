<?php

declare(strict_types=1);

namespace App\Web\Documentation;

use League\CommonMark\Extension\FrontMatter\Output\RenderedContentWithFrontMatter;
use League\CommonMark\MarkdownConverter;
use Spatie\YamlFrontMatter\YamlFrontMatter;
use Tempest\Support\Arr\ImmutableArray;

use function Tempest\Support\arr;
use function Tempest\Support\Arr\get_by_key;
use function Tempest\Support\Regex\replace;
use function Tempest\Support\str;

final readonly class ChapterRepository
{
    public function __construct(
        private MarkdownConverter $markdown,
    ) {
    }

    public function find(Version $version, string $category, string $slug): ?Chapter
    {
        $category = replace($category, '/^\d+-/', '');
        $slug = replace($slug, '/^\d+-/', '');
        $path = ImmutableArray::createFrom(recursive_search(
            folder: __DIR__ . "/content/{$version->value}",
            pattern: sprintf("#.*\/(\d+-)?%s\/(\d+-)?%s\.md#", preg_quote($category, '#'), preg_quote($slug, '#')),
        ))->sort()->first();

        if (! $path) {
            return null;
        }

        $markdown = $this->markdown->convert(file_get_contents($path));

        if (! ($markdown instanceof RenderedContentWithFrontMatter)) {
            throw new \RuntimeException(sprintf('Documentation entry [%s] is missing a frontmatter.', $path));
        }

        $frontmatter = $markdown->getFrontMatter();
        $title = get_by_key($frontmatter, 'title');
        $description = get_by_key($frontmatter, 'description');

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
    public function all(Version $version, string $category = '*'): ImmutableArray
    {
        return arr(glob(__DIR__ . "/content/{$version->value}/*{$category}/*.md"))
            ->map(function (string $path) use ($version) {
                $content = file_get_contents($path);
                $category = str($path)->beforeLast('/')->afterLast('/')->replaceRegex('/^\d+-/', '');

                preg_match('/(?<index>\d+-)?(?<slug>.*)\.md/', pathinfo($path, PATHINFO_BASENAME), $matches);

                return [
                    'version' => $version,
                    'slug' => replace($matches['slug'], '/^\d+-/', ''),
                    'index' => $matches['index'],
                    'category' => $category->toString(),
                    ...YamlFrontMatter::parse($content)->matter(),
                ];
            })
            ->mapTo(Chapter::class);
    }
}
