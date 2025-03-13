<?php

namespace Tempest\Web\Blog;

use League\CommonMark\Extension\FrontMatter\Output\RenderedContentWithFrontMatter;
use League\CommonMark\MarkdownConverter;
use Tempest\Support\Arr\ImmutableArray;
use Tempest\Web\CommandPalette\Command;
use Tempest\Web\CommandPalette\Type;

use function Tempest\Support\arr;
use function Tempest\uri;

final readonly class BlogIndexer
{
    public function __construct(
        private MarkdownConverter $markdown,
    ) {
    }

    public function __invoke(): ImmutableArray
    {
        return arr(glob(__DIR__ . '/articles/*.md'))
            ->map(function (string $path) {
                $markdown = $this->markdown->convert(file_get_contents($path));
                preg_match('/\d+-\d+-\d+-(?<slug>.*)\.md/', $path, $matches);

                if (! ($markdown instanceof RenderedContentWithFrontMatter)) {
                    throw new \RuntimeException(sprintf('Blog entry [%s] is missing a frontmatter.', $path));
                }

                ['title' => $title, 'author' => $author, 'description' => $description] = $markdown->getFrontMatter();

                $main = new Command(
                    type: Type::URI,
                    title: $title,
                    uri: uri([BlogController::class, 'show'], slug: $matches['slug']),
                    hierarchy: [
                        'Blog',
                        $author,
                        $title,
                    ],
                    fields: [
                        'author' => $author,
                        'description' => $description,
                    ],
                );

                return $main;
            });
    }
}
