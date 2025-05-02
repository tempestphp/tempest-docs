```php
use Tempest\Console\ConsoleCommand;
use Tempest\Console\HasConsole;

final readonly class PackageCommand
{
    use HasConsole;
    
    #[ConsoleCommand]
    public function all(): void
    {
        $this->search('')
    }

    #[ConsoleCommand]
    public function info(string $name, bool $detailed = false): void 
    { /* … */ }

    #[ConsoleCommand(middleware: [CautionMiddleware::class])]
    public function delete(string $name): void 
    { /* … */ }
}
```