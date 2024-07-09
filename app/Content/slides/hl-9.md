```php
final readonly class SqlHeredocInjection implements Injection
{
    public function parse(string $content, Highlighter $highlighter): string
    {
        preg_match('/<<<SQL(.|\n)*?SQL/', $content, $match);
        
        foreach ($matches as $match) {
            return $highlighter->parse($match, 'sql');
        }
    }
}
```