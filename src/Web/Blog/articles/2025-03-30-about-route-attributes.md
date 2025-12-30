---
title: About route attributes
description: Let's explore Tempest's route attributes in depth
author: brent
tag: Thoughts
---

Routing in Tempest is done with route attributes: each controller action can have one or more attributes assigned to them, and each attribute represents a route through which that action is accessible. Here's what that looks like:

```php
use Tempest\Router\Get;
use Tempest\Router\Post;
use Tempest\Router\Delete;
use Tempest\Http\Response;

final class BookAdminController
{
    #[Get('/books')]
    public function index(): Response { /* … */ }
    
    #[Get('/books/{book}/show')]
    public function show(Book $book): Response { /* … */ }
    
    #[Post('/books/new')]
    public function new(StoreBookRequest $request): Response { /* … */ }
    
    #[Post('/books/{book}/update')]
    public function update(BookRequest $bookRequest, Book $book): Response { /* … */ }
    
    #[Delete('/books/{book}/delete')]
    public function delete(Book $book): Response { /* … */ }
}
```

Not everyone agrees that route attributes are the better solution to configuring routes. I often get questions or arguments against them. However, taking a close look at route attributes reveals that they are superior to big route configuration files or implicit routing based on file names. So let's take a look at each argument against route attributes, and disprove them one by one.

## Route Visibility

The number one argument against route attributes compared to a route configuration file is that routes get spread across multiple files, which makes it difficult to get a global sense of which routes are available. People argue that having all routes listed within a single file is better, because all route configuration is bundled in that one place. Whenever you need to make routing changes, you can find all of them grouped together.

This argument quickly falls apart though. First, every decent framework offers a CLI command to list all routes, essentially giving you an overview of available routes and which controller action they handle. Whether you use route attributes or not, you'll always be able to generate a quick overview list of all routes.

```console
<em>// REGISTERED ROUTES</em>
These routes are registered in your application.

POST /books/new ................................. App\BookAdminController::new
DELETE /books/{book}/delete ..................... App\BookAdminController::delete
GET /books/{book}/show ......................... App\BookAdminController::show
POST /books/{book}/update ....................... App\BookAdminController::update
GET  /books ..................................... App\BookAdminController::index

<comment>…</comment>
```

The second reason this argument fails is that in real project, route files become a huge mess. Thousands of lines of route configuration isn't uncommon in projects, and they are definitely not "easier to comprehend". Moving route configuration and controller actions together actually counteracts this problem, since controllers are often already grouped together in modules, components, sub-folders, … Furthermore, to counteract the problem of "huge routing files", a common practice is to split huge route files into separate parts. In essence, that's exactly what route attributes force you to do by keeping the route attribute as close to the controller action as possible.

## Route Grouping

:::info
Since writing this blog post, route grouping in Tempest has gotten a serious update. Read all about it [here](/blog/route-decorators)
:::

The second-biggest argument against route attributes is the "route grouping" argument. A single route configuration file like for example in Laravel, allows you to reuse route configuration by grouping them together:

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

Laravel's approach is really useful because you can configure several routes as a single group, so that you don't have to repeat middleware configuration, prefixes, etc. for _every individual route_. With route attributes, you cannot do that — or can you?

Tempest has a concept called [route decorators](/2.x/essentials/routing#route-decorators-route-groups) which are a super convenient way to model route groups and share behavior. They look like this:

```php
#[{:hl-type:Admin:}, {:hl-type:Books:}]
final class BookAdminController
{
    #[Get('/books')]
    public function index(): View { /* … */ }
    
    #[Get('/books/{book}/show')]
    public function show(Book $book): View { /* … */ }
    
    #[Post('/books/new')]
    public function new(): View { /* … */ }
    
    #[Post('/books/{book}/update')]
    public function update(): View { /* … */ }
    
    #[Delete('/books/{book}/delete')]
    public function delete(): View { /* … */ }
}
```

You can read more about its design in [this blog post](/blog/route-decorators).

## Route Collisions

One of the few arguments against route attributes that I kind of understand, is how they deal with route collisions. Let's say we have these two routes:

```php
final class BookAdminController
{
    #[Get('/books/{book}')]
    public function show(Book $book): Response { /* … */ }
    
    #[Get('/books/new')]
    public function new(): Response { /* … */ }
}
```

Here we have a classic collision: when visiting `{txt}/books/new`, the router would detect it as matching the `/books/{book}` route, and, in turn, match the wrong action for that route. Such collisions occur rarely, but I've had to deal with them myself on the odd occasion. The solution, when they occur in the same file, is to simply switch their order:

```php
final class BookAdminController
{
    #[Get('/books/new')]
    public function new(): Response { /* … */ }
    
    #[Get('/books/{book}')]
    public function show(Book $book): Response { /* … */ }
}
```

This makes it so that `{txt}/books/new` is the first hit, and thus prevents the route collision. However, if these controller actions with colliding routes were spread across multiple files, you wouldn't be able to control their order. So then what?

First of all, there are a couple of ways to circumvent route collisions, using route files or attributes, all the same; that don't require you to rely on route ordering:

- You could change your URI, so that there are no potential collisions: `/books/{book}/show`; or
- you could use regex validation to only match numeric ids: `/books/{book:\d+}`.

Now, as a sidenote: in Tempest, `/books/{book}` and `{txt}/book/new` would never collide, no matter their order. That's because Tempest differentiates between static and dynamic routes, i.e. routes without or with variables. If there's a static route match, it will always get precedence over any dynamic routes that might match. That being said, there are still some cases where route collisions might occur, so it's good to know that, even with route attributes, there are multiple ways of dealing with those situations.

## Performance Impact

The argument of performance impact is easy to refute. People fear that having to scan a whole application to discover route attributes has a negative impact on performance compared to having one route file.

The answer in Tempest's case is simple: discovery is Tempest's core, not just for routing but for everything. It's super performant and properly cached. You can read more about it [here](/blog/discovery-explained).

## File-Based Routing

A completely different approach to route configuration is to simply use the document structure to define routes. So a URI like `/admin/books/{book}/show` would match `App\Controllers\Admin\BooksController::show()`. There are a number of issues file-based routing doesn't address: there's no way to solve the route group issue, you can't configure middleware on a per-route basis, and it's very limiting at scale to have your file structure be defined by the URL scheme.

On the other hand, there's a simplicity to file-based routing that I can appreciate as well.

## Single Responsibility

Finally, the argument that route attributes mix responsibility: a controller action and its route are two separate concerns and shouldn't be mixed in the same file. Personally I feel that's like saying "an id and a model don't belong together", and — to me — that makes no sense. A controller action is nothing without its route, because without its route, that controller action would never be able to run. That's the nature of controller actions: they are the entry points into your application, and for them to be accessible, you _need_ a route.

The best way to show this is to make a controller action. First you create a class and method, and then what? You make a route for it. Isn't it weird that you should go to another file to register the route, only to then return immediately to the controller file to continue your work?

Routes need controllers and controllers need routes. They cannot live without each other, and so keeping them together is the most sensible thing to do.

## Closing Thoughts

I hope it goes without saying, you choose what works best for you. If you decide that route attributes aren't your thing then, well, Tempest won't be your thing. That's ok. I do hope that I was able to present a couple of good arguments in favor of route attributes; and that they might have challenged your opinion if you were absolutely against them.
