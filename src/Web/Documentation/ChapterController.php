<?php

declare(strict_types=1);

namespace Tempest\Web\Documentation;

use Tempest\Core\Kernel;
use Tempest\Router\Get;
use Tempest\Router\Response;
use Tempest\Router\Responses\NotFound;
use Tempest\Router\Responses\Redirect;
use Tempest\Router\StaticPage;
use Tempest\View\View;

use function Tempest\Support\Str\before_first;
use function Tempest\uri;

final readonly class ChapterController
{
    #[Get('/docs')]
    #[Get('/documentation')]
    public function index(): Redirect
    {
        return new Redirect(uri(
            [self::class, '__invoke'],
            version: before_first(Kernel::VERSION, '.') . '.x',
            category: 'framework',
            slug: 'getting-started',
        ));
    }

    #[StaticPage(DocsDataProvider::class)]
    #[Get('/{version}/{category}/{slug}')]
    public function __invoke(string $version, string $category, string $slug, ChapterRepository $chapterRepository): View|Response
    {
        $currentChapter = $chapterRepository->find($version, $category, $slug);

        if (! $currentChapter) {
            return new NotFound();
        }

        return new ChapterView(
            chapterRepository: $chapterRepository,
            currentChapter: $currentChapter,
        );
    }
}
