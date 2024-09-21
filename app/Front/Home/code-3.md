```php
final readonly class DocsController
{
    #[StaticPage(DocsDataProvider::class)]
    #[Get('/{category}/{slug}')]
    public function __invoke(string $category, string $slug): View
    {
        // …
    }
}
```