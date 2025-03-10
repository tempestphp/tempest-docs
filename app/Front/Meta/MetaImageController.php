<?php

namespace App\Front\Meta;

use App\Front\Blog\BlogRepository;
use Spatie\Browsershot\Browsershot;
use Tempest\Container\Tag;
use Tempest\Core\Kernel;
use Tempest\Router\Get;
use Tempest\Router\Request;
use Tempest\Router\Response;
use Tempest\Router\Responses\File;
use Tempest\Router\Responses\NotFound;
use Tempest\Router\Responses\Ok;
use Tempest\View\ViewRenderer;

use function Tempest\path;
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
            $html = $this->viewRenderer->render(view(__DIR__ . '/meta-blog.view.php', post: $post));

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

    #[Get('/meta/{type}')]
    public function default(
        string $type,
        Request $request,
    ): Response {
        $type = MetaType::tryFrom($type);

        if (! $type) {
            return new NotFound();
        }

        if ($request->has('html')) {
            $html = $this->viewRenderer->render(view($type->getViewPath()));

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
