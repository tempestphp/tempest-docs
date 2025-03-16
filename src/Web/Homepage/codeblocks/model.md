```php src/Books/Book.php
final class Book implements DatabaseModel
{
    use IsDatabaseModel;

    public function __construct(
        #[Length(min: 1, max: 120)]
        public string $title,

        public ?Author $author = null,

        /** @var \App\Books\Chapter[] */
        public array $chapters = [],
    ) {}
}
```
