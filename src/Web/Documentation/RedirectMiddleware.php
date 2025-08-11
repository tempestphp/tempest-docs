<?php

namespace App\Web\Documentation;

use Tempest\Core\Priority;
use Tempest\Http\Request;
use Tempest\Http\Response;
use Tempest\Http\Responses\NotFound;
use Tempest\Http\Responses\Redirect;
use Tempest\Router\HttpMiddleware;
use Tempest\Router\HttpMiddlewareCallable;
use Tempest\Router\MatchedRoute;
use Tempest\Router\Router;

use function Tempest\get;
use function Tempest\Support\Arr\get_by_key;
use function Tempest\Support\Regex\matches;
use function Tempest\Support\str;
use function Tempest\uri;

#[Priority(Priority::HIGHEST)]
final readonly class RedirectMiddleware implements HttpMiddleware
{
    public function __construct(
        private Router $router,
        private MatchedRoute $route,
    ) {}

    #[\Override]
    public function __invoke(Request $request, HttpMiddlewareCallable $next): Response
    {
        $path = str($request->path);
        $response = $next($request);
        $version = Version::tryFromString(get_by_key($this->route->params, 'version'));

        // If not a docs page, let's just continue normal flow
        if ($this->route->route->uri !== '/{version}/{category}/{slug}') {
            return $response;
        }

        // Redirect to slugs without numbers
        if (matches($this->route->params['category'], '/^\d+-/') || matches($this->route->params['slug'], '/^\d+-/')) {
            return new Redirect($path->replaceRegex('/\/\d+-/', '/'));
        }

        // If no version found, 404
        if ($version === null) {
            return new Redirect(uri([DocumentationController::class, 'index']));
        }

        // Redirect to actual version
        if ($version->getUrlSegment() !== $this->route->params['version']) {
            return new Redirect($path->replace("/{$this->route->params['version']}/", "/{$version->getUrlSegment()}/"));
        }

        return $response;
    }
}
