<?php

namespace App\Web\Blog;

use Override;
use RuntimeException;
use App\Web\CommandPalette\Command;
use App\Web\CommandPalette\Indexer;
use App\Web\CommandPalette\Type;
use League\CommonMark\Extension\FrontMatter\Output\RenderedContentWithFrontMatter;
use League\CommonMark\MarkdownConverter;
use Tempest\Support\Arr\ImmutableArray;

use function Tempest\Support\arr;
use function Tempest\Support\Arr\get_by_key;
use function Tempest\Support\Arr\wrap;
use function Tempest\Router\uri;

final readonly class BlogIndexer implements Indexer
{
    public function __construct(
        private MarkdownConverter $markdown,
    ) {}

    #[Override]
    public function index(): ImmutableArray
    {
        return arr(glob(__DIR__ . '/articles/*.md'))
            ->map(function (string $path) {
                $markdown = $this->markdown->convert(file_get_contents($path));
                preg_match('/\d+-\d+-\d+-(?<slug>.*)\.md/', $path, $matches);

                if (! ($markdown instanceof RenderedContentWithFrontMatter)) {
                    throw new RuntimeException(sprintf('Blog entry [%s] is missing a frontmatter.', $path));
                }

                $frontmatter = $markdown->getFrontMatter();
                $title = get_by_key($frontmatter, 'title');
                $author = get_by_key($frontmatter, 'author');
                $description = get_by_key($frontmatter, 'description');
                $keywords = get_by_key($frontmatter, 'keywords');
                $tags = get_by_key($frontmatter, 'tag');

                $main = new Command(
                    type: Type::URI,
                    title: $title,
                    uri: uri([BlogController::class, 'show'], slug: $matches['slug']),
                    hierarchy: [
                        'Blog',
                        Author::tryFrom($author)?->getName() ?? 'Tempest',
                        $title,
                    ],
                    fields: [
                        $author,
                        $description,
                        ...wrap($keywords),
                        ...wrap($tags),
                    ],
                );

                return $main;
            });
    }
}
