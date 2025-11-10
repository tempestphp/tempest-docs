---
title: "Route Decorators in Tempest 2.8" 
description: Taking a deep dive in a new Tempest feature
author: brent
tag: release
---

When I began working on Tempest, the very first things I added were a container and a router. I already had a clear vision on what I wanted routing to look like: to embrace attributes to keep routes and controller actions close together. Coming from Laravel, this is quite a different approach, and so I wrote about [my vision on the router's design](/blog/about-route-attributes) to make sure everyone understood.

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

While I really like attribute-based routing, grouping route bevaiour does feel… suboptimal with this approach. A couple of nitpicks:

- Tempest's default route attributes are represented by HTTP verbs: `#[Get]`, `#[Post]`, etc. Making admin variants for each verb might be tedious, so in my previous example I decided to use one `#[AdminRoute]`, where the verb would be specified manually. There's nothing stopping me from adding `#[AdminGet]`, `#[AdminPost]`, etc; but it doesn't feel super clean either.
- When you prefer to namespace admin-specific route attributes like `#[Admin\Get]`, and `#[Admin\Post]`, you end up with naming collisions between normal- and admin versions. I've always found those types of ambiguities to increase cognitive load while coding.
- This approach doesn't really scale: say there are two types of route groups that require a specific middleware (`AuthMiddleware`, for example), then you end up making two or more route attributes, duplicating adding the `AuthMiddleware` to both.
- Say you want nested route groups: one for admin routes and then one for book routes (with a `/admin/books` prefix), you end up with yet another `#[AdminBookRoute]` attribute, not ideal. 

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

I think Symfony's approach gets us halfway there: it has the benefit of being able to define "shared route behavior" on the controller level, but not across controllers. You could create abstract controllers like `AdminController` and `AdminBookController`, but I find that a very cumbersome approach. It also doesn't scale when you want to combine multiple route behaviors horizontally because PHP doesn't have multi-inheritance. On top of that, I also like Tempest's design of using HTTP verbs to model route attributes like `#[Get]` and `#[Post]`, which is missing with Symfony. All of that to say, I like Symfony's approach, but I feel like there's even more room for improvement.

With the scene now being set, let's see the design we ended up with in Tempest.

## A Tempesty solution

A week ago, my production server suddenly died. After some debugging, I realized the problem had to do with my recent refactor from [my blog](https://stitcher.io) to Tempest. The RSS- and meta-image routes apparently started a session (which were stored as files), which eventually led to the server being overflooded with unused files after two weeks. I had forgotten to exclude these routes from the default web flow. 