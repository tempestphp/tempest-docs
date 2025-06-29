---
title: Ten Tempest Tips
description: "Ten things you might now know about Tempest" 
author: brent
tag: Thoughts
---

With the release of Tempest 1.0, many people wonder what the framework is about. There is so much to talk about, and I decided to highlight a couple of features in this blog post. I hope it might intrigue you to give Tempest a try, and discover even more!

## 1. Make it your own

Tempest is designed with the flexibility to structure your projects whatever way you want. You can choose a classic MVC project, a DDD-inspired approach, hexagonal design, or anything else that suits your needs, without any configuration or framework adjustments. It just works the way you want.

```txt
.                                    .
└── src                              └── app
    ├── Authors                          ├── Controllers
    │   ├── Author.php                   │   ├── AuthorController.php
    │   ├── AuthorController.php         │   └── BookController.php
    │   └── authors.view.php             ├── Models
    ├── Books                            │   ├── Author.php
    │   ├── Book.php                     │   ├── Book.php
    │   ├── BookController.php           │   └── Chapter.php
    │   ├── Chapter.php                  ├── Services
    │   └── books.view.php               │   └── PublisherGateway.php
    ├── Publishers                       └── Views
    │   └── PublisherGateway.php             ├── authors.view.php
    └── Support                              ├── books.view.php
        └── x-base.view.php                  └── x-base.view.php
```

## 2. Discovery

The mechanism that allows such a flexible project structure is called [Discovery](/blog/discovery-explained). With Discovery, Tempest will scan your whole project and infer an incredible amount of information by reading your code, so that you don't have to configure the framework manually. On top of that, Tempest's discovery is designed to be extensible for project developers and package authors. 

For example, I built a small event-sourcing implementation to keep track of website analytics [on this website](https://github.com/tempestphp/tempest-docs/blob/main/src/StoredEvents/ProjectionDiscovery.php). For that, I wanted to discover event projections within the app. Instead of manually listing classes in a config file somewhere. So I hooked into Tempest's discovery flow, which only requires implementing a single interface:

```php
final class ProjectionDiscovery implements Discovery
{
    use IsDiscovery;

    public function __construct(
        private readonly StoredEventConfig $config,
    ) {}

    public function discover(DiscoveryLocation $location, ClassReflector $class): void
    {
        if ($class->implements(Projector::class)) {
            $this->discoveryItems->add($location, $class->getName());
        }
    }

    public function apply(): void
    {
        foreach ($this->discoveryItems as $className) {
            $this->config->projectors[] = $className;
        }
    }
}
```

Of course, Tempest comes with a bunch of discovery implementations built in: routes, console commands, middleware, view components, event and command handlers, migrations, other discovery classes, and more. You can [read more about discovery here](/blog/discovery-explained).

## 3. Config classes

[Configuration](/docs/essentials/configuration#configuration-files) in Tempest is handled via classes. Any component that needs configuration will have one or more config classes. Config classes are simple data objects and don't require any setup. They might look something like this:

```php

final class MysqlConfig implements DatabaseConfig
{
    public string $dsn {
        get => sprintf(
            'mysql:host=%s:%s;dbname=%s',
            $this->host,
            $this->port,
            $this->database,
        );
    }

    public DatabaseDialect $dialect {
        get => DatabaseDialect::MYSQL;
    }

    public function __construct(
        #[SensitiveParameter]
        public string $host = 'localhost',
        #[SensitiveParameter]
        public string $port = '3306',
        #[SensitiveParameter]
        public string $username = 'root',
        #[SensitiveParameter]
        public string $password = '',
        #[SensitiveParameter]
        public string $database = 'app',
        // …
    ) {}
}
```

The first benefit of config classes is that the configuration schema is defined with class properties, which means you'll have proper static insight when defining and using configuration within Tempest:

```php database.config.php
use Tempest\Database\Config\MysqlConfig;
use function Tempest\env;

return new MysqlConfig(
    host: env('DB_HOST'),
    post: env('DB_PORT'),
    username: env('DB_USERNAME'),
    password: env('DB_PASSWORD'),
);
```

The second benefit of config classes is that their instances are discovered and registered in the container. Whenever a file ends with `.config.php` and returns a new config object, then that config object will be available via autowiring throughout your code:

```php app/stored-events.config.php
use App\StoredEvents\StoredEventConfig;

return new StoredEventConfig();
```

```php app/StoredEvents/EventsReplayCommand.php
use App\StoredEvents\StoredEventConfig;

final readonly class EventsReplayCommand
{
    public function __construct(
        private StoredEventConfig $storedEventConfig,
        // …
    ) {}
}
```

## 4. Static pages

Tempest has built-in support for generating [static websites](/blog/static-websites-with-tempest). The idea is simple: why boot the framework when all that's needed is the same HTML page for any request to a specific URI? All you need is to mark an existing controller with the `#[StaticPage]` attribute, optionally add a data provider for dynamic routes, and you're set:

```php
use Tempest\Router\StaticPage;

final readonly class BlogController
{
    // …

    #[StaticPage(BlogDataProvider::class)]
    #[Get('/blog/{slug}')]
    public function show(string $slug, BlogRepository $repository): Response|View
    {
        // …
    }
}
```

Finally, all you need to do is run the `{console}static:generate` command, and your static website is ready:

```console
~ tempest static:generate

- <u>/blog</u> > <u>/web/tempestphp.com/public/blog/index.html</u>
- <u>/blog/exit-codes-fallacy</u> > <u>/web/tempestphp.com/public/blog/exit-codes-fallacy/index.html</u>
- <u>/blog/unfair-advantage</u> > <u>/web/tempestphp.com/public/blog/unfair-advantage/index.html</u>
- <u>/blog/alpha-2</u> > <u>/web/tempestphp.com/public/blog/alpha-2/index.html</u>
<comment>…</comment>
- <u>/blog/alpha-5</u> > <u>/web/tempestphp.com/public/blog/alpha-5/index.html</u>
- <u>/blog/static-websites-with-tempest</u> > <u>/web/tempestphp.com/public/blog/static-websites-with-tempest/index.html</u>

<success>Done</success>
```

## 5. Console arguments

Console commands in Tempest require as little configuration as possible, and will be defined by the handler method's signature. Once again thanks to discovery, Tempest will infer what kind of input a console command needs, based on the [method's argument list](/docs/essentials/console-commands#command-arguments):

```php
final readonly class EventsReplayCommand
{
    // …

    #[ConsoleCommand]
    public function __invoke(?string $replay = null, bool $force = false): void
    { /* … */ }
}

// ./tempest events:replay PackageDownloadsPerDayProjector --force 
```

## 6. Response classes

While Tempest has a generic response class that can be returned from controller actions, you're encouraged to use one of the specific response implementations instead:

```php
use Tempest\Http\Response;
use Tempest\Http\Responses\Ok;
use Tempest\Http\Responses\Download;

final class DownloadController
{
    #[Get('/downloads')]
    public function index(): Response
    {
        // …
        
        return new Ok(/* … */);
    }
    
    #[Get('/downloads/{id}')]
    public function download(string $id): Response
    {
        // …
        
        return new Download($path);
    }
}
```

Making your own response classes is trivial as well: you must implement the `Tempest\Http\Response` interface and you're ready to go. For convenience, there's also an `IsResponse` trait:

```php
use Tempest\Http\Response
use Tempest\Http\IsResponse;

final class BookCreated implements Response
{
    use IsResponse;

    public function __construct(Book $book)
    {
        $this->setStatus(\Tempest\Http\Status::CREATED);
        $this->addHeader('x-book-id', $book->id);
    }
}
```

## 7. SQL migrations

Tempest has a database migration builder to manage your database's schema:

```php
use Tempest\Database\DatabaseMigration;
use Tempest\Database\QueryStatement;
use Tempest\Database\QueryStatements\CreateTableStatement;
use Tempest\Database\QueryStatements\DropTableStatement;

final class CreateBookTable implements DatabaseMigration
{
    public string $name = '2024-08-12_create_book_table';

    public function up(): QueryStatement|null
    {
        return new CreateTableStatement('books')
            ->primary()
            ->text('title')
            ->datetime('created_at')
            ->datetime('published_at', nullable: true)
            ->integer('author_id', unsigned: true)
            ->belongsTo('books.author_id', 'authors.id');
    }

    public function down(): QueryStatement|null
    {
        return new DropTableStatement('books');
    }
}
```

But did you know that Tempest also supports raw SQL migrations? Any `.sql` file within your application directory will be discovered automatically:

```sql app/Migrations/2025-01-01_create_publisher_table.sql
CREATE TABLE Publisher
(
    `id`   INTEGER,
    `name` TEXT NOT NULL
);
```

## 8. Console middleware

You might know middleware as a concept for HTTP requests, but Tempest's console also supports middleware. This makes it easy to add reusable functionality to multiple console commands. For example, Tempest comes with a `CautionMiddleware` and `ForceMiddleware` built-in. These middlewares add an extra warning before executing the command in production, and an optional `--force` flag to skip these kinds of warnings.

```php
use Tempest\Console\ConsoleCommand;
use Tempest\Console\Middleware\ForceMiddleware;
use Tempest\Console\Middleware\CautionMiddleware;

final readonly class EventsReplayCommand
{
    #[ConsoleCommand(middleware: [ForceMiddleware::class, CautionMiddleware::class])]
    public function __invoke(?string $replay = null): void
    { /* … */ }
}
```

You can also make your own console middleware, you can [find out how here](/docs/essentials/console-commands#middleware).

## 9. Interfaces everywhere

When you're diving into Tempest's internals, you'll notice how we prefer to use interfaces over abstract classes. The idea is simple: if there's something framework-related to hook into, you'll be able to implement an interface and register your own implementation in the container. Most of the time, you'll also find a default trait implementation. There's a good reason behind this design, and you can read all about it [here](https://stitcher.io/blog/extends-vs-implements).  

## 10. Initializers

Finally, let's talk about [dependency initializers](/docs/essentials/container#dependency-initializers). Initializers are tasked with setting up one or more dependencies in the container. Whenever you need a complex dependency available everywhere, your best option is to make a dedicated initializer class for it. Here's an example: setting up a Markdown converter that can be used throughout your app:  

```php
use Tempest\Container\Container;
use Tempest\Container\Initializer;

final readonly class MarkdownInitializer implements Initializer
{
    public function initialize(Container $container): MarkdownConverter
    {
        $environment = new Environment();
        $highlighter = new Highlighter(new CssTheme());

        $highlighter
            ->addLanguage(new TempestViewLanguage())
            ->addLanguage(new TempestConsoleWebLanguage())
            ->addLanguage(new ExtendedJsonLanguage());

        $environment
            ->addExtension(new CommonMarkCoreExtension())
            ->addExtension(new FrontMatterExtension())
            ->addRenderer(FencedCode::class, new CodeBlockRenderer($highlighter))
            ->addRenderer(Code::class, new InlineCodeBlockRenderer($highlighter));

        return new MarkdownConverter($environment);
    }
}
```

As with most things-Tempest, they are discovered automatically. Creating an initializer class and setting the right return type for the `initialize()` method is enough for Tempest to pick it up and set it up within the container.

## There's a lot more!

To truly appreciate Tempest, you'll have to write code with it. To get started, head over to [the documentation](/docs/getting-started/installation) and [join our Discord server](/discord)! 