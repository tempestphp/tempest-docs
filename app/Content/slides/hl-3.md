```php
final class KeywordPattern implements Pattern
{
    use IsPattern;
    
    public function __construct(private string $keyword) {}
    
    public function getPattern(): string
    {
        $keyword = $this->keyword;
        
        return "/\b(?<match>{$keyword})\b/";
    }

    public function getTokenType(): TokenTypeEnum
    {
        return TokenTypeEnum::KEYWORD;
    }
}
```