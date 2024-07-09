```php
class PhpLanguage implements Language
{
    // …

    public function getPatterns(): array
    {
        return [
            new KeywordPattern('return'),
            new KeywordPattern('new'),
            new KeywordPattern('match'),
            // …
        ];
    }
}
```