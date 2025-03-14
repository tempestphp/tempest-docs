<?php

namespace Tempest\Web\Documentation;

use League\CommonMark\Extension\CommonMark\Node\Block\Heading;
use League\CommonMark\Extension\FrontMatter\Output\RenderedContentWithFrontMatter;
use League\CommonMark\Extension\HeadingPermalink\HeadingPermalink;
use League\CommonMark\MarkdownConverter;
use League\CommonMark\Node\Inline\Text;
use League\CommonMark\Node\Query;
use Tempest\Support\Arr\ImmutableArray;
use Tempest\Web\CommandPalette\Command;
use Tempest\Web\CommandPalette\Type;

use function Tempest\Support\arr;
use function Tempest\Support\Str\to_kebab_case;
use function Tempest\Support\Str\to_title_case;
use function Tempest\uri;

/**
 * Indexes the blog.
 */
final readonly class DocumentationIndexer
{
    public function __construct(
        private MarkdownConverter $markdown,
    ) {
    }

    /**
     * @return ImmutableArray<Command>
     */
    public function __invoke(string $version): ImmutableArray
    {
        return arr(glob(__DIR__ . "/content/{$version}/*/*.md"))
            ->flatMap(function (string $path) use ($version) {
                $markdown = $this->markdown->convert(file_get_contents($path));
                preg_match('/(?<index>\d+-)?(?<slug>.*)\.md/', pathinfo($path, PATHINFO_BASENAME), $matches);

                if (! ($markdown instanceof RenderedContentWithFrontMatter)) {
                    throw new \RuntimeException(sprintf('Documentation entry [%s] is missing a frontmatter.', $path));
                }

                ['title' => $title, 'category' => $category] = $markdown->getFrontMatter();

                $main = new Command(
                    type: Type::URI,
                    title: $title,
                    uri: uri(ChapterController::class, version: $version, category: $category, slug: $matches['slug']),
                    hierarchy: [
                        'Documentation',
                        to_title_case($category),
                        $title,
                    ],
                );

                /** @var Heading[] */
                $matchingNodes = new Query()
                    ->where(Query::type(Heading::class))
                    ->findAll($markdown->getDocument());

                $indices = arr(iterator_to_array($matchingNodes))
                    ->map(function (Heading $heading) use ($main) {
                        /** @var Text */
                        $text = $heading->firstChild();
                        $slug = to_kebab_case($text->getLiteral());

                        return new Command(
                            type: Type::URI,
                            title: $text->getLiteral(),
                            uri: $main->uri . '#' . $slug,
                            hierarchy: [
                                ...$main->hierarchy,
                                $text->getLiteral(),
                            ],
                        );
                    })
                    ->filter();

                return [
                    $main,
                    ...$indices,
                ];
            });
    }
}
