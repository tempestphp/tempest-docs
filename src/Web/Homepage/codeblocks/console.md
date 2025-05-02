```php
final readonly class PackagesCommand
{
    use HasConsole;
    
    public function __construct(
        private PackageRepository $repository,
    ) {}
    
    #[ConsoleCommand]
    public function find(): void
    {
        $package = $this->search(
            'Find your package',
            $this->repository->find(...),
        );
    }

    #[ConsoleCommand(middleware: [CautionMiddleware::class])]
    public function delete(string $name, bool $verbose = false): void 
    { /* … */ }
}
```