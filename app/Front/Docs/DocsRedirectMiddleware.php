<?php

namespace App\Front\Docs;

use Tempest\Core\KernelEvent;
use Tempest\EventBus\EventHandler;
use Tempest\Http\HttpMiddleware;
use Tempest\Http\Request;
use Tempest\Http\Response;
use Tempest\Http\Responses\NotFound;
use Tempest\Http\Responses\Redirect;
use Tempest\Http\Router;
use function Tempest\Support\str;
use function Tempest\uri;

final readonly class DocsRedirectMiddleware implements HttpMiddleware
{
    public function __construct(
        private Router $router,
    ) {}

    #[EventHandler(KernelEvent::BOOTED)]
    public function register(): void
    {
        $this->router->addMiddleware(self::class);
    }

    public function __invoke(Request $request, callable $next): Response
    {
        $path = str($request->getPath());

        /** @var \Tempest\Http\Response $response */
        $response = $next($request);

        // If not a docs page, let's just continue normal flow
        if (! $path->startsWith('/docs')) {
            return $response;
        }

        // Redirect to slug without number
        if ($path->afterLast('/')->matches('/\d+-/')) {
            return new Redirect($path->replaceRegex('/\d+-/', ''));
        }

        if ($response instanceof NotFound) {
            return new Redirect(uri([DocsController::class, 'frameworkIndex']));
        }

        return $response;
    }
}