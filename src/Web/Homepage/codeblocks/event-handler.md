```php
final readonly class BookObserver
{
    #[EventHandler]
    public function onBookPublished(BookPublished $event): void
    {
        // …
    }
}
```