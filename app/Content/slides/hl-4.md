```php
public function getPattern(): string
{
    $keyword = $this->keyword;
    
    return "/\b(?<match>{$keyword})\b/";
}
```