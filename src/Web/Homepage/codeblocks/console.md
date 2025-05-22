```php
final readonly class BooksCommand
{
    use HasConsole;
    
    public function __construct(
        private BookRepository $repository,
    ) {}
    
    #[ConsoleCommand]
    public function find(): void
    {
        $book = $this->search(
            'Find your book',
            $this->repository->find(...),
        );
    }

    #[ConsoleCommand(middleware: [CautionMiddleware::class])]
    public function delete(string $title, bool $verbose = false): void 
    { /* … */ }
}
```