```php src/Support/MarkdownInitializer.php
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
