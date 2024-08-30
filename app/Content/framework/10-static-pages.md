---
title: Static Pages
---

Tempest comes with a built-in static site generator. When a controller action is tagged with `#[StaticPage]`, it can be compiled by Tempest as a static HTML page. These pages can then directly be served via your webserver.

```php
final readonly class HomeController
{
    #[StaticPage]
    #[Get('/')]
    public function home(): View
    {
        return view('home');
    }
}
```

Compiling all static pages is done with the `{txt}static:generate` command:

```
./tempest static:generate
```

You can also remove all statically generated pages with the `{txt}static:clean` command:

```
./tempest static:clean
```

## Data providers

Since most pages require some form of dynamic data, static pages can be assigned a data provider, which will generate multiple pages for one controller action.

Let's take a look at the controller action for this docs website:

```php
final readonly class DocsController
{
    #[StaticPage(DocsDataProvider::class)]
    #[Get('/{category}/{slug}')]
    public function show(string $category, string $slug, ChapterRepository $chapterRepository): View
    {
        return new DocsView(
            chapterRepository: $chapterRepository,
            currentChapter: $chapterRepository->find($category, $slug),
        );
    }
}
```

In this case, the `#[StaticPage]` attribute gets a reference to the `DocsDataProvider`, which implements the `\Tempest\Http\DataProvider` interface:

```php
final readonly class DocsDataProvider implements DataProvider
{
    public function provide(): Generator
    {
        // …
    }
}
```

A data provider's goal is to generate multiple pages for one controller action. It does so by yielding an array of controller action parameters for every page the needs to be generated. In case of the docs controller, the action needs a `$category` and `$slug`, as well as a `$chapterRepository`. That `$chapterRepository` is injected by the container, so we don't need to worry about it here. What we _do_ need to provide is a category and slug for each page we want to generate. 

In other words: we want to generate a page for every docs chapter. We can use the `ChapterRepository` to get a list of all available chapters. Eventually, our data provider looks like this:

```php
final readonly class DocsDataProvider implements DataProvider
{
    public function __construct(
        private ChapterRepository $chapterRepository
    ) {}

    public function provide(): Generator
    {
        foreach ($this->chapterRepository->all() as $chapter) {
            // Yield an array of parameters that should be passed to the controller action,
            yield [
                'category' => $chapter->category,
                'slug' => $chapter->slug,
            ];
        }
    }
}
```

The only thing left to do is to generate the static pages:

```console
./tempest static:generate

- <em>/framework/01-getting-started</em> > <u>/Users/brent/Dev/tempest-docs/public/framework/01-getting-started.html</u>
- <em>/framework/02-the-container</em> > <u>/Users/brent/Dev/tempest-docs/public/framework/02-the-container.html</u>
- <em>/framework/03-controllers</em> > <u>/Users/brent/Dev/tempest-docs/public/framework/03-controllers.html</u>
- <em>/framework/04-views</em> > <u>/Users/brent/Dev/tempest-docs/public/framework/04-views.html</u>
- <em>/framework/05-models</em> > <u>/Users/brent/Dev/tempest-docs/public/framework/05-models.html</u>
- <comment>…</comment>
```

## Server setup

Tempest will generate static HTML pages, but it won't take care of serving them. The goal of these pages is to not run PHP at all, and have your web server serve them directly.

### Nginx

```
{:hl-keyword:location:} / {
    {:hl-property:try_files:} {:hl-strong:$uri.html:} $uri $uri/ /index.php?$query_string;
}
```

### Other web servers

If you use other webservers and want their config listed on this page, feel free to send a [pull request](https://github.com/tempestphp/tempest-docs/tree/main/app/Content/framework/10-static-pages.md) to the docs site.

## Deployments

Finally, keep in mind that static pages should be regenerated on every deploy. You should add the `{txt}./tempest static:generate` command in your deployment pipeline.