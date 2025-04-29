<?php

namespace App\Web\Meta;

use App\Web\Blog\BlogRepository;
use App\Web\Documentation\ChapterRepository;
use App\Web\Documentation\Version;
use Spatie\Browsershot\Browsershot;
use Tempest\Container\Tag;
use Tempest\Core\Kernel;
use Tempest\Router\Get;
use Tempest\Http\Request;
use Tempest\Http\Response;
use Tempest\Http\Responses\File;
use Tempest\Http\Responses\Ok;
use Tempest\View\ViewRenderer;

use function Tempest\support\path;
use function Tempest\uri;
use function Tempest\view;

final readonly class MetaImageController
{
    public function __construct(
        private Kernel $kernel,
        private ViewRenderer $viewRenderer,
        #[Tag('meta')]
        private Browsershot $browsershot,
    ) {
    }

    #[Get('/meta/blog/{slug}')]
    public function blog(string $slug, Request $request, BlogRepository $repository): Response
    {
        $post = $repository->find($slug);

        if ($request->has('html')) {
            $html = $this->viewRenderer->render(view(__DIR__ . '/views/blog.view.php', post: $post));

            return new Ok($html);
        }

        $path = path($this->kernel->root, 'public/meta/meta-blog-' . $slug . '.png');

        if (! is_dir(dirname($path))) {
            mkdir(dirname($path), recursive: true);
        }

        if (! is_file($path) || $request->has('nocache')) {
            $this->browsershot
                ->windowSize(1200, 628)
                ->setUrl(uri([self::class, 'blog'], slug: $slug, html: true))
                ->save($path);
        }

        return new File($path);
    }

    #[Get('/meta/documentation/{version}/{category}/{slug}')]
    public function documentation(string $version, string $category, string $slug, Request $request, ChapterRepository $repository): Response
    {
        $version = Version::from($version);
        $chapter = $repository->find($version, $category, $slug);

        if ($request->has('html')) {
            $html = $this->viewRenderer->render(view(__DIR__ . '/views/documentation.view.php', chapter: $chapter));

            return new Ok($html);
        }

        $path = path($this->kernel->root, "public/meta/meta-documentation-{$version->value}-{$category}-{$slug}.png");

        if (! is_dir(dirname($path))) {
            mkdir(dirname($path), recursive: true);
        }

        if (! is_file($path) || $request->has('nocache')) {
            $this->browsershot
                ->windowSize(1200, 628)
                ->setUrl(uri([self::class, 'documentation'], version: $version->value, category: $category, slug: $slug, html: true))
                ->save($path);
        }

        return new File($path);
    }

    #[Get('/meta/{type}')]
    public function default(string $type, Request $request): Response
    {
        $type = MetaType::tryFrom($type) ?? MetaType::HOME;

        if ($request->has('html')) {
            $html = $this->viewRenderer->render(view($type->getViewPath(), title: $request->get('title'), subtitle: $request->get('subtitle')));

            return new Ok($html);
        }

        $path = path($this->kernel->root, 'public/meta/meta-' . $type->value . '.png');

        if (! is_dir(dirname($path))) {
            mkdir(dirname($path), recursive: true);
        }

        if (! is_file($path) || $request->has('nocache')) {
            $this->browsershot
                ->setUrl(uri([self::class, 'default'], type: $type->value, html: true))
                ->save($path);
        }

        return new File($path);
    }
}
