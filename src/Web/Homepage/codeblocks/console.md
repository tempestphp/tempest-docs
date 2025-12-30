```php src/Books/BooksCommand.php
final readonly class BooksCommand
{
    public function __construct(
        private BookRepository $repository,
        private Console $console,
    ) {}
    
    #[ConsoleCommand]
    public function find(): void
    {
        $book = $this->search('Find your book', $this->repository->find(...));

        // …
    }

    #[ConsoleCommand(middleware: [CautionMiddleware::class])]
    public function delete(string $title, bool $verbose = false): void 
    {
        // …
    }
}
```
