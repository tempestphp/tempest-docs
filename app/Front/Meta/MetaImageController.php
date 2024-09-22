<?php

namespace App\Front\Meta;

use Spatie\Browsershot\Browsershot;
use Tempest\Core\Kernel;
use Tempest\Http\Get;
use Tempest\Http\Request;
use Tempest\Http\Response;
use Tempest\Http\Responses\File;
use Tempest\Http\Responses\NotFound;
use Tempest\Http\Responses\Ok;
use Tempest\View\ViewRenderer;
use function Tempest\env;
use function Tempest\path;
use function Tempest\view;

final readonly class MetaImageController
{
    public function __construct(
        private Kernel $kernel,
        private ViewRenderer $viewRenderer,
    ) {}

    #[Get('/meta/{type}')]
    public function __invoke(string $type, Request $request): Response
    {
        $type = MetaType::tryFrom($type);

        if (! $type) {
            return new NotFound();
        }

        $css = file_get_contents( __DIR__ . "/../../../public/main.css");

        $html = $this->viewRenderer->render(view($type->getViewPath(), css: $css));

        if ($request->has('html')) {
            return new Ok($html);
        }

        $path = path($this->kernel->root, 'public/meta/meta-' . $type->value . '.png');

        if (! is_dir(dirname($path))) {
            mkdir(dirname($path), recursive: true);
        }

        if (! is_file($path) || $request->has('nocache')) {
            $browsershot = Browsershot::html($html)
                ->setOption('args', ['--disable-web-security'])
                ->windowSize(1200, 628)
                ->deviceScaleFactor(2);

            if ($includePath = env('BROWSERSHOT_PATH')) {
                $browsershot->setIncludePath("{$includePath}:\$PATH");
            }

            if ($nodePath = env('BROWSERSHOT_NODE_PATH')) {
                $browsershot->setNodeBinary($nodePath);
            }

            if ($npmPath = env('BROWSERSHOT_NPM_PATH')) {
                $browsershot->setNpmBinary($npmPath);
            }

            $browsershot->save($path);
        }

        return (new File($path));
    }
}