```php
final readonly class DocsController
{
    #[StaticPage(DocsDataProvider::class)]
    #[Get('/{category}/{slug}')]
    public function __invoke(string $category, string $slug): View
    { /* … */ }
}
```

```console
<em>tempest</em> static:generate

- <em>/docs/framework/01-getting-started</em> > <u>/home/forge/tempest.stitcher.io/public/docs/framework/01-getting-started.html</u>
- <em>/docs/framework/02-the-container</em> > <u>/home/forge/tempest.stitcher.io/public/docs/framework/02-the-container.html</u>
- <em>/docs/framework/03-controllers</em> > <u>/home/forge/tempest.stitcher.io/public/docs/framework/03-controllers.html</u>
- <comment>…</comment>

<success>Done</success>
```