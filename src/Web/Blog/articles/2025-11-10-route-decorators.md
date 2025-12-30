---
title: "Route decorators in Tempest 2.8"
description: Taking a deep dive in a new Tempest feature
author: brent
tag: release
---

When I began working on Tempest, the very first features were a container and a router. I already had a clear vision on what I wanted routing to look like: to embrace attributes to keep routes and controller actions close together. Coming from Laravel, this is quite a different approach, and so I wrote about [my vision on the router's design](/blog/about-route-attributes) to make sure everyone understood.

> If you decide that route attributes aren't your thing then, well, Tempest won't be your thing. That's ok. I do hope that I was able to present a couple of good arguments in favor of route attributes; and that they might have challenged your opinion if you were absolutely against them.

One tricky part with the route attributes approach was route grouping. My proposed solution back in the day was to implent custom route attributes that grouped behavior together. For example, where Laravel would define "a route group for admin routes" like so:

```php
Route::middleware([AdminMiddleware::class])
    ->prefix('/admin')
    ->group(function () {
        Route::get('/books', [BookAdminController::class, 'index'])
        Route::get('/books/{book}/show', [BookAdminController::class, 'show'])
        Route::post('/books/new', [BookAdminController::class, 'new'])
        Route::post('/books/{book}/update', [BookAdminController::class, 'update'])
        Route::delete('/books/{book}/delete', [BookAdminController::class, 'delete'])
    });
```

Tempest's approach would look like this:

```php
use Attribute;
use Tempest\Http\Method;
use Tempest\Router\Route;
use function Tempest\Support\path;

#[Attribute]
final class AdminRoute implements Route
{
    public function __construct(
        public string $uri,
        public array $middleware = [],
        public Method $method = Method::GET,
    ) {
        $this->uri = path('/admin', $uri);
        $this->middleware = [AdminMiddleware::class, ...$middleware];
    }
}
```

```php
final class BookAdminController
{
    #[AdminRoute('/books')]
    public function index(): View { /* … */ }
    
    #[AdminRoute('/books/{book}/show')]
    public function show(Book $book): View { /* … */ }
    
    #[AdminRoute('/books/new', method: Method::POST)]
    public function new(): View { /* … */ }
    
    #[AdminRoute('/books/{book}/update', method: Method::POST)]
    public function update(): View { /* … */ }
    
    #[AdminRoute('/books/{book}/delete', method: Method::DELETE)]
    public function delete(): View { /* … */ }
}
```

While I really like attribute-based routing, grouping route behavior does feel… suboptimal because of attributes. A couple of nitpicks:

- Tempest's default route attributes are represented by HTTP verbs: `#[Get]`, `#[Post]`, etc. Making admin variants for each verb might be tedious, so in my previous example I decided to use one `#[AdminRoute]`, where the verb would be specified manually. There's nothing stopping me from adding `#[AdminGet]`, `#[AdminPost]`, etc; but it doesn't feel super clean.
- When you prefer to namespace admin-specific route attributes like `#[Admin\Get]`, and `#[Admin\Post]`, you end up with naming collisions between normal- and admin versions. I've always found those types of ambiguities to increase cognitive load while coding.
- This approach doesn't really scale: say there are two types of route groups that require a specific middleware (`AuthMiddleware`, for example), then you end up making two or more route attributes, duplicating that logic of adding `AuthMiddleware` to both.
- Say you want nested route groups: one for admin routes and then one for book routes (with a `/admin/books` prefix), you end up with yet another variant called `#[AdminBookRoute]` attribute, not ideal.

So… what's the solution? I first looked at Symfony, which also uses attributes for routing:

```php
#[Route('/admin/books', name: 'admin_books_')]
class BookAdminController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response { /* … */ }
    
    #[Route('/{book}/show')]
    public function show(Book $book): Response { /* … */ }
    
    #[Route('/new', methods: ['POST'])]
    public function new(): Response { /* … */ }
    
    #[Route('/{book}/update', methods: ['POST'])]
    public function update(): Response { /* … */ }
    
    #[Route('/{book}/delete', methods: ['DELETE'])]
    public function delete(): Response { /* … */ }
}
```

I think Symfony's approach gets us halfway there: it has the benefit of being able to define "shared route behavior" on the controller level, but not across controllers. You could create abstract controllers like `AdminController` and `AdminBookController`, which doesn't scale horizontally when you want to combine multiple route groups, because PHP doesn't have multi-inheritance. On top of that, I also like Tempest's design of using HTTP verbs to model route attributes like `#[Get]` and `#[Post]`, which is missing with Symfony. All of that to say, I like Symfony's approach, but I feel like there's room for improvement.

With the scene now being set, let's see the design we ended up with in Tempest.

## A Tempesty solution

A week ago, my production server suddenly died. After some debugging, I realized the problem had to do with the recent refactor of [my blog](https://stitcher.io) to Tempest. The RSS and meta-image routes apparently started a session, which eventually led to the server being overflooded with hundreds of RSS reader- and social media requests per minute, each of them starting a new session. The solution was to remove all session-related middleware (CSRF protection, and "back URL" support) from these routes. While trying to come up with a proper solution, I had a realization: instead of making a "stateless route" class, why not add an attribute that worked _alongside_ the existing route attributes? That's what led to a new `#[Stateless]` attribute:

```php
#[Stateless, {:hl-type:Get:}('/rss')]
public function rss(): Response {}
```

This felt like a really nice solution: I didn't have to make my own route attributes anymore, but could instead "decorate" them with additional functionality. The first iteration of the `#[Stateless]` attribute was rather hard-coded in Tempest's router (I was on the clock, trying to revive my server), it looked something like this:

```php
// Skip middleware that sets cookies or session values when the route is stateless
if (
    $matchedRoute->route->handler->hasAttribute(Stateless::class)
    && in_array(
        needle: $middlewareClass->getName(),
        haystack: [
            VerifyCsrfMiddleware::class,
            SetCurrentUrlMiddleware::class,
            SetCookieMiddleware::class,
        ],
        strict: true,
    )
) {
    return $callable($request);
}
```

I knew, however, that it would be trivial to make this into a reusable pattern. A couple of days later and that's exactly what I did: route decorators are Tempest's new way of modeling grouped route behavior, and I absolutely love them. Here's a quick overview.

First, route decorators work _alongside_ route attributes, not as a _replacement_. This means that they can be combined in any way you'd like, and they should all work together seeminglessly:

```php
final class BookAdminController
{
    #[{:hl-type:Admin:}, {:hl-type:Books:}, {:hl-type:Get:}('/{book}/show')]
    public function show(Book $book): View { /* … */ }
    
    // …
}
```

Furthermore, route decorators can also be defined on the controller level, which means they'll be applied to all its actions:

```php
#[{:hl-type:Admin:}, {:hl-type:Books:}]
final class BookAdminController
{
    #[Get('/')]
    public function index(): View { /* … */ }
    
    #[Get('/{book}/show')]
    public function show(Book $book): View { /* … */ }
    
    #[Post('/new')]
    public function new(): View { /* … */ }
    
    #[Post('/{book}/update')]
    public function update(): View { /* … */ }
    
    #[Delete('/{book}/delete')]
    public function delete(): View { /* … */ }
}
```

Finally, you're encouraged to make your custom route attributes as well (you might have already guessed that because of `#[Admin]` and `#[Books]`). Here's what both of these attributes would look like:

```php
use Attribute;
use Tempest\Router\RouteDecorator;

#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_CLASS)]
final readonly class Admin implements RouteDecorator
{
    public function decorate(Route $route): Route
    {
        $route->uri = path('/admin', $route->uri)->toString();
        $route->middleware[] = AdminMiddleware::class;

        return $route;
    }
}
```

```php
use Attribute;
use Tempest\Router\RouteDecorator;

#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_CLASS)]
final readonly class Books implements RouteDecorator
{
    public function decorate(Route $route): Route
    {
        $route->uri = path('/books', $route->uri)->toString();

        return $route;
    }
}
```

You can probably guess what a route decorator's job is: it is passed the current route, it can do some changes to it, and then return it. You can add and combine as many route decorators as you'd like, and Tempest's router will stitch them all together. Under the hood, that looks like this:

```php
// Get the route attribute
$route = $method->getAttributes(Route::class);
            
// Get all decorators from the method and its controller class
 $decorators = [
    ...$method->getDeclaringClass()->getAttributes(RouteDecorator::class),
    ...$method->getAttributes(RouteDecorator::class),
];

// Loop over each decorator and apply it one by one
foreach ($decorators as $decorator) {
    $route = $decorator->decorate($route);
}
```

As an added benefit: all of this route decorating is done during [Tempest's discovery phase](/2.x/internals/discovery), which means the decorated route will be cached, and decorators themselves won't be run in production.

On top of adding the {b`Tempest\Router\RouteDecorator`} interface, I've also added a couple of built-in route decorators that come with the framework:

- {b`Tempest\Router\Prefix`}: which adds a prefix to all decorated routes.
- {b`Tempest\Router\WithMiddleware`}: which adds one or more middleware classes to all decorated routes.
- {b`Tempest\Router\WithoutMiddleware`}: which explicitely removes one or more middleware classes from the default middleware stack to all decorated routes.
- {b`Tempest\Router\Stateless`}: which will remove all session and cookie related middleware from the decorated routes.

I really like the solution we ended up with. I think it combines the best of both worlds. Maybe you have some thoughts about it as well? [Join the Tempest Discord](/discord) to let us know! You can also read all the details of route decorators [in the docs](/2.x/essentials/routing#route-decorators-route-groups).
