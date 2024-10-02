```html
final class BookRequest implements Request
{
    use IsRequest;

    #[Length(min: 10, max: 100)]
    public string $title;

    public ?DateTimeImmutable $publishedAt = null;

    public string $summary;
}
```