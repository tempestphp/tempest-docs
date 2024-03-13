---
title: Controllers
---

Controllers are the core of any web app, they route an HTTP request through the necessary layers of code to finally return a response.

### Routing

In Tempest, a controller action can be any class' method, as long as it's annotated with a `Route` attribute. Tempest offers some convenient Route attributes out of the box, and you can write your own if you need to.

Out of the box, these `<hljs type>Route</hljs>` attributes are available:

- `<hljs type>Route</hljs>`
- `<hljs type>Get</hljs>`
- `<hljs type>Post</hljs>`

You can use them like so:

```php
final <hljs keyword>readonly</hljs> class HomeController
{
    #[<hljs type>Get</hljs>(<hljs prop>uri</hljs>: '<hljs value>/home</hljs>')]
    public function __invoke(): View
    {
        return <hljs prop>view</hljs>('home.view.php');
    }
}
```


### Requests

Any web app will soon need to validate and access request data. In Tempest, that data is available via request classes. Every public property on such a request class represents a value that's being sent from the client to the server. Tempest relies on PHP's type system to validate that data, and offers a bunch of validation attributes for more fine-tuned validation.

```php
final class BookRequest implements Request
{
    use <hljs type>IsRequest</hljs>;
    
    #[<hljs type>Length</hljs>(<hljs prop>min</hljs>: <hljs value>10</hljs>, <hljs prop>max</hljs>: <hljs value>120</hljs>)]
    public <hljs type>string</hljs> $title;
    
    public ?<hljs type>DateTimeImmutable</hljs> $publishedAt = null;
    
    public <hljs type>string</hljs> $summary;
}
```

Note that this is a pattern you'll see often throughout Tempest: any class that interacts with the framework should implement an interface, and the framework provides a trait with a default implementation, just like `<hljs type>Request</hljs>` and `<hljs type>IsRequest</hljs>` in this case.

Once you've created your request class, you can add it as an argument to your controller method:

```php
final <hljs keyword>readonly</hljs> class BookController
{
    #[<hljs type>Post</hljs>(<hljs prop>uri</hljs>: '<hljs value>/books/create</hljs>')]
    public function store(<hljs type>BookRequest</hljs> $request): Response
    {
        $book = <hljs prop>map</hljs>($request)-><hljs prop>to</hljs>(<hljs type>Book</hljs>::class)-><hljs prop>save</hljs>();
        
        return <hljs prop>response</hljs>()
            -><hljs prop>redirect</hljs>(<hljs prop>uri</hljs>([<hljs keyword>self</hljs>::class, 'show'], <hljs prop>id</hljs>: $book-><hljs prop>id</hljs>));
    }
}
```

A full overview of `<hljs type>Request</hljs>` objects can be found [here](https://github.com/tempestphp/tempest-framework/blob/main/src/Http/Request.php).

#### A note on data mapping

The `<hljs prop>map</hljs>` function is another powerful feature that sets Tempest apart. We'll discuss it more in depth when looking at models, but it's already worth mentioning: Tempest can treat any kind of object as "a model", and is able to map data into those objects from different sources.

You could map a request class with its data to a model class, but you could also map a model object to a JSON array; you could map JSON data to models, a model to an array, and so on. The `<hljs prop>map</hljs>` function will detect what kind of data source its dealing with and what kind of target that data should be mapped into.

### Responses

Tempest controllers must return one of two objects: a `<hljs type>View</hljs>` or a `<hljs type>Response</hljs>`. Returning a view is a shorthand for returning a successful response _with_ that view.

```php
final <hljs keyword>readonly</hljs> class BookController
{
    #[<hljs type>Get</hljs>(<hljs prop>uri</hljs>: '<hljs value>/books/{book}</hljs>')]
    public function show(<hljs type>Book</hljs> $book, <hljs type>User</hljs> $user): View
    {
        return <hljs prop>view</hljs>('Front/books/detail.view.php',
            <hljs prop>book</hljs>: $book,
            <hljs prop>user</hljs>: $user,
        );
    }
}
```

You can read all about views in the [next chapter](/03-views).

Returning responses can be done with the `<hljs prop>response</hljs>` function:


```php
final <hljs keyword>readonly</hljs> class BookController
{
    #[<hljs type>Get</hljs>(<hljs prop>uri</hljs>: '<hljs value>/books/{book}</hljs>')]
    public function show(<hljs type>Book</hljs> $book, <hljs type>User</hljs> $user): Response
    {
        return <hljs prop>response</hljs>()
            -><hljs prop>setBody</hljs>('raw body content')
            -><hljs prop>setView</hljs>('Front/books/detail.view.php', <hljs prop>book</hljs>: $book)
            -><hljs prop>setStatus</hljs>(<hljs type>Status</hljs>::<hljs prop>CREATED</hljs>)
            -><hljs prop>addHeader</hljs>('x-custom-header', 'value')
            -><hljs prop>addSession</hljs>('session-name', 'value')
            -><hljs prop>removeSession</hljs>('other-session-name')
            -><hljs prop>addCookie</hljs>(new <hljs type>Cookie</hljs>('cookie-name', 'value'))
            -><hljs prop>removeCookie</hljs>('other-cookie-name')
            -><hljs prop>flash</hljs>('All went fine!')
        ;
    }
}
```

A full overview of `<hljs type>Response</hljs>` objects can be found [here](https://github.com/tempestphp/tempest-framework/blob/main/src/Http/Response.php).

#### Response Objects

You're free to implement your own custom responses, Tempest also provides some [convenient Response implementations](https://github.com/tempestphp/tempest-framework/tree/main/src/Http/Responses) for you out of the box (wip). 

```php
use <hljs type>Tempest\Http\IsResponse</hljs>;
use <hljs type>Tempest\Http\Response</hljs>;
use <hljs type>Tempest\Http\Status</hljs>;

final class CreatedResponse implements Response
{
    use <hljs type>IsResponse</hljs>;

    public function __construct() 
    {
        $this-><hljs prop>status</hljs> = <hljs type>Status</hljs>::<hljs prop>CREATED</hljs>;
    }
}
```

### Custom Routes

Thanks to route attributes, you can make your own, custom `<hljs type>Route</hljs>` implementations. These custom route classes can be used to make route groups that add middleware, do authorization checks, etc.

```php
#[<hljs type>Attribute</hljs>]
final <hljs keyword>readonly</hljs> class AdminRoute extends Route
{
    public function __construct(<hljs type>string</hljs> $uri, <hljs type>Method</hljs> $method)
    {
        parent::<hljs prop>__construct</hljs>(
            <hljs prop>uri</hljs>: $uri,
            <hljs prop>method</hljs>: $method,
            <hljs prop>middelware</hljs>: [
                <hljs type>AdminMiddleware</hljs>::class,
                <hljs type>LogUserActionsMiddleware</hljs>::class,
            ]
        );
    }
}
```

You can now use this `<hljs type>AdminRoute</hljs>` attribute for all controller methods that should only be accessed by admins:

```php
final <hljs keyword>readonly</hljs> class BookController
{
    // …
    
    #[<hljs type>AdminRoute</hljs>('<hljs value>/books</hljs>', <hljs type>Method::</hljs><hljs prop>POST</hljs>)]
    public function store(<hljs type>BookRequest</hljs> $request): Response
    {
        // …
    }
}
```

### Generating URIs

You can generate URIs referencing controller methods by using the `<hljs prop>uri</hljs>` function:

```php
// Invokable classes can be referenced directly:
<hljs prop>uri</hljs>(<hljs type>HomeController</hljs>::class); 
// /home

// Classes with named methods are referenced using an array
<hljs prop>uri</hljs>([<hljs type>BookController</hljs>::class, 'store']); 
// /books

// Additional URI parameters are passed in as named arguments:
<hljs prop>uri</hljs>([<hljs type>BookController</hljs>::class, 'show'], <hljs prop>id</hljs>: $book-><hljs prop>id</hljs>); 
// /books/1
```

### Route Binding

Tempest will map IDs to model instances — a topic we'll cover in depth in the [Models chapter](/04-models).

```php
final <hljs keyword>readonly</hljs> class BookController
{
    #[<hljs type>Get</hljs>('<hljs value>/books/{book}</hljs>')]
    public function show(<hljs type>Book</hljs> $book): Response { /* … */ }
}
```