```php
final readonly class PublishBook
{
    public function __construct(
        private Mailer $mailer,
    ) {}
    
    function __invoke(Book $book, DateTimeImmutable $publishDate): PublishedBook
    {
        // …
    }
}
```