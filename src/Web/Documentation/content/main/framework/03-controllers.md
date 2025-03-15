---
title: Controllers
category: framework
---

Controllers are the core of any web app, they route an HTTP request through the necessary layers of code to finally return a response.

## Routing

In Tempest, a controller action can be any class' method, as long as it's annotated with an attribute which implements the `Route` interface. Tempest offers some convenient Route attributes out of the box, and you can write your own if you need to.

Out of the box, these `Route` attributes are available:

- `\Tempest\Router\Get`
- `\Tempest\Router\Post`
- `\Tempest\Router\Delete`
- `\Tempest\Router\Put`
- `\Tempest\Router\Patch`
- `\Tempest\Router\Options`
- `\Tempest\Router\Connect`
- `\Tempest\Router\Trace`
- `\Tempest\Router\Head`

You can use them like so:

```php
// app/HomeController.php

use Tempest\Router\Get;
use Tempest\View\View;
use function view;

final readonly class HomeController
{
    #[Get(uri: '/home')]
    public function __invoke(): View
    {
        return view('home.view.php');
    }
}
```

### Dynamic routes
You can define dynamic segments in your route URIs by wrapping them in curly braces {}. The segment name inside the braces will be passed as a parameter to your controller method.

Here's an example:
```php
// app/UserController.php

use Tempest\Router\Get;
use Tempest\View\View;
use function Tempest\view;

final readonly class UserController
{
    #[Get(uri: '/users/{id}')]
    public function showUser(int $id): View
    {
        // Fetch the user by ID
        $user = $this->userService->getUserById($id);

        // Pass the user to the view
        return view('user.view.php', ['user' => $user]);
    }
}
```

### Custom regex

It is also possible to add custom regex for dynamic segments.

Here's an example:

```php
// app/UserController.php

use Tempest\Router\Get;
use Tempest\View\View;
use function Tempest\view;

final readonly class UserController
{
    #[Get(uri: '/users/{id:[0-9]+}')]
    public function showUser(int $id): View
    {
        // Fetch the user by ID
        $user = $this->userService->getUserById($id);

        // Pass the user to the view
        return view('user.view.php', ['user' => $user]);
    }
}
```

## Requests

Any web app will soon need to validate and access request data. In Tempest, that data is available via request classes. Every public property on such a request class represents a value that's being sent from the client to the server. Tempest relies on PHP's type system to validate that data, and offers a bunch of validation attributes for more fine-tuned validation.

```php
// app/BookRequest.php

use Tempest\Router\Request;
use Tempest\Router\IsRequest;
use Tempest\Validation\Rules\Length;

final class BookRequest implements Request
{
    use IsRequest;

    #[Length(min: 10, max: 120)]
    public string $title;

    public ?DateTimeImmutable $publishedAt = null;

    public string $summary;
}
```

Note that this is a pattern you'll see often throughout Tempest: any class that interacts with the framework should implement an interface, and the framework provides a trait with a default implementation, just like `Request` and `IsRequest` in this case.

Once you've created your request class, you can add it as an argument to your controller method:

```php
// app/BookController.php

use Tempest\Router\Post;
use Tempest\Router\Responses\Redirect;
use function Tempest\map;
use function Tempest\uri;

final readonly class BookController
{
    #[Post(uri: '/books/create')]
    public function store(BookRequest $request): Redirect
    {
        $book = map($request)->to(Book::class)->save();

        return new Redirect(uri([self::class, 'show'], id: $book->id));
    }
}
```

A full overview of `Request` objects can be found [here](https://github.com/tempestphp/tempest-framework/blob/main/src/Tempest/Router/src/Request.php).

### A note on data mapping

The `{php}map()` function is another powerful feature that sets Tempest apart. We'll discuss it more in depth when looking at models, but it's already worth mentioning: Tempest can treat any kind of object as "a model", and is able to map data into those objects from different sources.

You could map a request class with its data to a model class, but you could also map a model object to a JSON array; you could map JSON data to models, a model to an array, and so on. The `{php}map()` function will detect what kind of data source its dealing with and what kind of target that data should be mapped into.

## Middleware

Middleware can be applied to handle tasks in between receiving a request and sending a response. Middleware can be applied to routes via the attributes which implement the `Route` interface, such as `#[Get]`, `#[Post]` or more:

```php
// app/BookClass.php

use Tempest\Router\Get;
use Tempest\Router\Response;

final readonly class BookClass
{
    #[Get(
        uri: '/books',
        middleware: [BooksMiddleware::class],
    )]
    public function index(): Response
    {
        // …
    }
}
```

A middleware class, in turn, should implement the `\Tempest\Router\HttpMiddleware` interface:

```php
// app/BooksMiddleware.php

use Tempest\Router\HttpMiddleware;
use Tempest\Router\HttpMiddlewareCallable;
use Tempest\Router\Request;
use Tempest\Router\Response;

final readonly class BooksMiddleware implements HttpMiddleware
{
    public function __invoke(Request $request, HttpMiddlewareCallable $next): Response
    {
        $response = $next($request);

        $response->addHeader('x-book', 'true');

        return $response;
    }
}
```

[custom routes](#custom-routes)

## Responses

Tempest controllers must return one of two objects: a `{php}View` or a `{php}Response`. Returning a view is a shorthand for returning a successful response _with_ that view. As a shorthand, you can use the `{php}view()` function to construct a view.

```php
// app/BookController.php

use Tempest\Router\Get;
use Tempest\View\View;
use function Tempest\view;

final readonly class BookController
{
    #[Get(uri: '/books/{book}')]
    public function show(Book $book, User $user): View
    {
        return view('Front/books/detail.view.php',
            book: $book,
            user: $user,
        );
    }
}
```

You can read all about views in the [next chapter](/docs/framework/04-views).

If you're returning responses Tempest has a bunch of responses built-in:

- `{php}Created` — the 201 response with an optional body
- `{php}Download` — downloads a file from the browser
- `{php}File` — shows a file in the browser
- `{php}Invalid` — a response with form validation errors, redirecting to the previous page
- `{php}NotFound` — the 404 response with an optional body
- `{php}Ok` — the 200 response with an optional body
- `{php}Redirect` — the redirect response
- `{php}ServerError` — a 500 server error response

A full overview of responses can be found [here](https://github.com/tempestphp/tempest-framework/tree/main/src/Tempest/Router/src/Responses).

Returning responses from controllers looks like this:

```php
// app/AdminDownloadController.php

use Tempest\Router\Get;
use Tempest\Router\Responses\Download;
use Tempest\Router\Responses\Redirect;
use Tempest\Router\Response;

final readonly class AdminDownloadController
{
    #[Get('/admin/download/{path}')]
    public function download(string $path): Response
    {
        $allowed = /* … */;

        if (! $allowed) {
            return new Redirect('/');
        }

        $sanitizedPath = /* … */;

        return new Download($sanitizedPath);
    }
}
```

### Content types

Tempest will automatically infer the response's content type from the request's Accept header. You can override its content type manually though:

```php
// app/JsonController.php

use Tempest\Router\Get;
use Tempest\Router\ContentType;
use Tempest\Router\Response;
use Tempest\Router\Responses\Ok;

final readonly class JsonController
{
    #[Get('/json')]
    public function json(string $path): Response
    {
        $data = [ /* … */ ];

        return (new Ok($data))->setContentType(ContentType::JSON);
    }
}
```

Note that you don't have to worry about setting content types if the request has the `Accept` header specified (wip).

### Response objects

If you want to, you can create your own Response objects for your specific use cases:

```php
// app/BookCreated.php

use Tempest\Router\IsResponse;
use Tempest\Router\Response;
use Tempest\Router\Status;

final class BookCreated implements Response
{
    use IsResponse;

    public function __construct(Book $book)
    {
        $this->status = Status::CREATED;
        $this->addHeader('x-book-id', $book->id);
    }
}
```

## Custom routes

Thanks to the `Route` interface, you can make your own route attributes. These custom route classes can be used to make route groups that add middleware, do authorization checks, etc.

```php
// app/AdminRoute.php

use \Attribute;
use Tempest\Router\Route;
use Tempest\Router\Method;
use Tempest\Router\HttpMiddleware;

#[Attribute]
final readonly class AdminRoute implements Route
{
    /** @var HttpMiddleware[]  */
    public array $middleware;

    public function __construct(
        public string $uri,
        public Method $method,
    ) {
        $this->uri = $uri;
        $this->method = $method;
        $this->middleware = [
            AdminMiddleware::class,
            LogUserActionsMiddleware::class,
        ];
    }
}
```

You can now use this `AdminRoute` attribute for all controller methods that should only be accessed by admins:

```php
// app/BookController

use Tempest\Router\Method;
use Tempest\Router\Response;

final readonly class BookController
{
    // …

    #[AdminRoute('/books', Method::POST)]
    public function store(BookRequest $request): Response
    {
        // …
    }
}
```

## Generating URIs

You can generate URIs referencing controller methods by using the `\Tempest\uri` function:

```php
use function Tempest\uri;

// Invokable classes can be referenced directly:
uri(HomeController::class);
// /home

// Classes with named methods are referenced using an array
uri([BookController::class, 'store']);
// /books

// Additional URI parameters are passed in as named arguments:
uri([BookController::class, 'show'], id: $book->id);
// /books/1
```

## Route binding

Tempest will map IDs to model instances:

```php
// app/BookController.php

use Tempest\Router\Get;
use Tempest\Router\Response;

final readonly class BookController
{
    #[Get('/books/{book}')]
    public function show(Book $book): Response { /* … */ }
}
```

### Binding to an enum

Tempest can also bind to a string backed enum which causes all enum values to be available as routes.

```php
// app/Genre.php

enum Genre: string
{
    case Thriller = 'thriller';
    case Fantasy = 'fantasy';
    case ScienceFiction = 'science-fiction';
}

// app/BookController.php

use Tempest\Router\Get;
use Tempest\Router\Response;

final readonly class BookController
{
    #[Get('/books/{genre}')]
    public function show(Genre $genre): Response { /* … */ }
}
```

## Deferring tasks

Sometimes you might want to handle some tasks after a response has been sent to the client. For example: you want to send an email but don't want the client to wait until it has been sent. Or, you want to keep track of page visits, but don't want the client having to wait until you update your analytics database.

These cases are well suited for deferred tasks: tasks that are executed after the response already has been sent.

```php
// app/AuthController.php

use Tempest\Router\Post;
use Tempest\Router\Responses\Redirect;
use function Tempest\defer;

final readonly class AuthController
{
    #[Post('/register')]
    public function register(): Redirect
    {
        $user = // …

        defer(function () use ($user) {
            // Send mail to user
        });

        return new Redirect('/');
    }
}
```

```php
// app/PageVisitedMiddleware.php

use Tempest\Router\HttpMiddleware;
use Tempest\Router\Request;
use Tempest\Router\Response;
use function Tempest\defer;

final readonly class PageVisitedMiddleware implements HttpMiddleware
{
    public function __invoke(Request $request, callable $next): Response
    {
        defer(function () use ($request) {
            event(new PageVisited($request->getUri()));
        });

        return $next($request);
    }
}
```

Note that task deferring only works if [`fastcgi_finish_request()`](https://www.php.net/manual/en/function.fastcgi-finish-request.php) is available within your PHP installation. If it's not available, deferred tasks will still be run, but the client response will only complete after all tasks have been finished as well.
