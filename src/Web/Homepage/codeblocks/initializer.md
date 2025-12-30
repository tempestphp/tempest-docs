```php src/Blog/MarkdownInitializer.php
final readonly class MarkdownInitializer implements Initializer
{
    #[Singleton]
    public function initialize(Container $container): MarkdownConverter
    {
        $highlighter = new Highlighter(new CssTheme())
            ->addLanguage(new TempestViewLanguage());
        
        $environment = new Environment()
            ->addRenderer(Code::class, new CodeBlockRenderer($highlighter));

        return new MarkdownConverter($environment);
    }
}
```
