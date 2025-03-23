<?php

namespace App\Web\Documentation;

use App\Web\CommandPalette\Command;
use App\Web\CommandPalette\Type;
use League\CommonMark\Extension\CommonMark\Node\Block\Heading;
use League\CommonMark\Extension\FrontMatter\Output\RenderedContentWithFrontMatter;
use League\CommonMark\MarkdownConverter;
use League\CommonMark\Node\Inline\Text;
use League\CommonMark\Node\Query;
use Tempest\Support\Arr\ImmutableArray;
use Tempest\Support\Str\ImmutableString;

use function Tempest\Support\arr;
use function Tempest\Support\Arr\get_by_key;
use function Tempest\Support\Str\to_kebab_case;
use function Tempest\Support\Str\to_sentence_case;
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
    public function __invoke(Version $version): ImmutableArray
    {
        return arr(glob(__DIR__ . "/content/{$version->value}/*/*.md"))
            ->flatMap(function (string $path) use ($version) {
                $markdown = $this->markdown->convert(file_get_contents($path));

                if (! ($markdown instanceof RenderedContentWithFrontMatter)) {
                    throw new \RuntimeException(sprintf('Documentation entry [%s] is missing a frontmatter.', $path));
                }

                $path = new ImmutableString($path);
                $category = $path->beforeLast('/')->afterLast('/')->replaceRegex('/\d+-/', '');
                $chapter = $path->basename('.md')->replaceRegex('/\d+-/', '');
                $title = get_by_key($markdown->getFrontMatter(), 'title');

                $main = new Command(
                    type: Type::URI,
                    title: $title,
                    uri: uri(ChapterController::class, version: $version, category: $category, slug: $chapter),
                    hierarchy: [
                        'Documentation',
                        to_sentence_case($category),
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
