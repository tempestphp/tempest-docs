```php
final readonly class MarkdownInitializer implements Initializer
{
    public function initialize(Container $container): MarkdownConverter
    {
        $highlighter = new Highlighter(new CssTheme())
            ->addLanguage(new TempestViewLanguage());
        
        $environment = new Environment()
            ->addRenderer(Code::class, new InlineCodeBlockRenderer($highlighter));

        return new MarkdownConverter($environment);
    }
}
```
