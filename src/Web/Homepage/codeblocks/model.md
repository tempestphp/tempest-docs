```php
final class Book
{
    use IsDatabaseModel;

    #[Length(min: 1, max: 120)]
    public string $title;

    public ?Author $author = null;

    /** @var \App\Books\Chapter[] */
    public array $chapters = [];
}
```
