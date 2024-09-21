```php
final class Book implements DatabaseModel
{
    use IsDatabaseModel;
    
    public function __construct(
        #[Length(min: 1, max: 120)]
        public string $title,
    
        public ?Author $author = null,
    
        /** @var \App\Modules\Books\Models\Chapter[] */
        public array $chapters = [],
    ) {}
}
```