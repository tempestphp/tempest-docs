```php src/Books/BookController.php
final readonly class BookController
{
    #[Post('/books')]
    public function store(CreateBookRequest $request): Response
    {
        $book = map($request)->to(Book::class)->save();

        return new Redirect(uri([self::class, 'show'], book: $book->id));
    }
}
```
