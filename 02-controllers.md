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
    public function __invoke(): Response
    {
        return response()->ok();
    }
}
```

Keep in mind that you're allowed to make your own Route attributes, which can be a very convenient way to handle authentication. One example could be routes that are only accessible to admin users:

```php
#[Attribute]
final readonly class AdminRoute extends Route
{
    public function __construct(string $uri, Method $method)
    {
        // Check whether the currently logged-in user is an admin
        // Throw an exception if not 
    
        parent::__construct(
            uri: $uri,
            method: $method,
        );
    }
}
```

```php
final readonly class BlogPostController
{
    // …
    
    #[AdminRoute('/blog', Method::POST)]
    public function store(BlogPostRequest $request): Response
    {
        // …
    }
}
```

### Generating URIs

In order to generate URIs, you can use the `uri` function like so:

```php
// Invokable classes can be referenced directly:
uri(HomeController::class); 
// /home

// Classes with named methods are referenced using an array
uri([BlogPostController::class, 'store']); 
// /blog

// Additional URI parameters are passed in as named arguments:
uri([BlogPostController::class, 'show'], id: $post->id); 
// /blog/1
```

### Route mapping

URI parameters will be automatically mapped into method parameters:

```php
final readonly class BlogPostController
{
    #[Post(uri: '/blog/{id}/update')]
    public function store(int $id): Response
    {
        // …
        
        return response()->redirect(uri([BlogPostController::class, 'show'], id: $id)) 
    }
}
```

Tempest can also map ids to model instances — a topic we'll cover in depth soon.

```php
final readonly class BlogPostController
{
    #[Get('/blog/{post}')]
    public function show(Post $post): Response { /* … */ }
}
```

### Request classes

Request classes can be used to validate incoming data:

```php
final class BlogPostRequest implements Request
{
    use BaseRequest;
    
    #[Length(min: 10, max: 120)]
    public string $title;
    
    public ?DateTimeImmutable $publishedAt = null;
    
    public string $body;
}
```

Note that this is a pattern you'll see often throughout Tempest: any class that interacts with the framework should implement an interface, and the framework provides a trait with a default implementation, just like `Request` and `BaseRequest` in this case. 

```php
final readonly class BlogPostController
{
    #[Post(uri: '/blog/{id}/update')]
    public function store(int $id, BlogPostRequest $request): Response
    {
        $book = map($request)->to(Post::class)->save();
        
        return response()->redirect(uri([BlogPostController::class, 'show'], id: $id)) 
    }
}
```

### A note on data mapping

The `map` function is another powerful feature that sets Tempest apart. We'll discuss it more in depth when looking at models, but it's already worth mentioning: Tempest can treat any kind of object a "a model", and is able to map data into those objects from different sources. 

You could map a request class with its data to a model class, but you could also map a model object to a JSON array; you could map JSON data to models, a model to an array, and so on. The `map` function will detect what kind of data source its dealing with and what kind of target that data should be mapped into. 