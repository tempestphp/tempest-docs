---
title: Controllers
---

Controllers are the core of any web app, they route an HTTP request through the necessary layers of code to finally return a response.

### Routing

In Tempest, a controller action can be any class' method, as long as it's annotated with a `Route` attribute. Tempest offers some convenient Route attributes out of the box, and you can write your own if you need to.

Out of the box, these `Route` attributes are available:

- `Route`
- `Get`
- `Post`

You can use them like so:

```php
final readonly class HomeController
{
    #[Get(uri: '/home')]
    public function __invoke(): View
    {
        return view('home.view.php');
    }
}
```


### Requests

Any web app will soon need to validate and access request data. In Tempest, that data is available via request classes. Every public property on such a request class represents a value that's being sent from the client to the server. Tempest relies on PHP's type system to validate that data, and offers a bunch of validation attributes for more fine-tuned validation.

```php
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
final readonly class BookController
{
    #[Post(uri: '/books/create')]
    public function store(BookRequest $request): Response
    {
        $book = map($request)->to(Book::class)->save();
        
        return response()
            ->redirect(uri([self::class, 'show'], id: $book->id));
    }
}
```

A full overview of `Request` objects can be found [here](https://github.com/tempestphp/tempest-framework/blob/main/src/Http/Request.php).

#### A note on data mapping

The `map` function is another powerful feature that sets Tempest apart. We'll discuss it more in depth when looking at models, but it's already worth mentioning: Tempest can treat any kind of object as "a model", and is able to map data into those objects from different sources.

You could map a request class with its data to a model class, but you could also map a model object to a JSON array; you could map JSON data to models, a model to an array, and so on. The `map` function will detect what kind of data source its dealing with and what kind of target that data should be mapped into.

### Responses

Tempest controllers must return one of two objects: a `View` or a `Response`. Returning a view is a shorthand for returning a successful response _with_ that view.

```php
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

You can read all about views in the [next chapter](/03-views).

Returning responses can be done with the `response` function:


```php
final readonly class BookController
{
    #[Get(uri: '/books/{book}')]
    public function show(Book $book, User $user): Response
    {
        return response()
            ->setBody('raw body content')
            ->setView('Front/books/detail.view.php', book: $book)
            ->setStatus(Status::CREATED)
            ->addHeader('x-custom-header', 'value')
            ->addSession('session-name', 'value')
            ->removeSession('other-session-name')
            ->addCookie(new Cookie('cookie-name', 'value'))
            ->removeCookie('other-cookie-name')
            ->flash('All went fine!')
        ;
    }
}
```

A full overview of `Response` objects can be found [here](https://github.com/tempestphp/tempest-framework/blob/main/src/Http/Response.php).

#### Response Objects

You're free to implement your own custom responses, Tempest also provides some [convenient Response implementations](https://github.com/tempestphp/tempest-framework/tree/main/src/Http/Responses) for you out of the box (wip). 

```php
use Tempest\Http\IsResponse;
use Tempest\Http\Response;
use Tempest\Http\Status;

final class CreatedResponse implements Response
{
    use IsResponse;

    public function __construct() 
    {
        $this->status = Status::CREATED;
    }
}
```

### Custom Routes

Thanks to route attributes, you can make your own, custom `Route` implementations. These custom route classes can be used to make route groups that add middleware, do authorization checks, etc.

```php
#[Attribute]
final readonly class AdminRoute extends Route
{
    public function __construct(string $uri, Method $method)
    {
        parent::__construct(
            uri: $uri,
            method: $method,
            middelware: [
                AdminMiddleware::class,
                LogUserActionsMiddleware::class,
            ]
        );
    }
}
```

You can now use this `AdminRoute` attribute for all controller methods that should only be accessed by admins:

```php
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

### Generating URIs

You can generate URIs referencing controller methods by using the `uri` function:

```php
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

### Route Binding

Tempest will map IDs to model instances — a topic we'll cover in depth in the [Models chapter](/04-models).

```php
final readonly class BookController
{
    #[Get('/books/{book}')]
    public function show(Book $book): Response { /* … */ }
}
```