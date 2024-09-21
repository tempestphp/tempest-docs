```php
final readonly class InteractiveCommand
{
    use HasConsole;

    #[ConsoleCommand('hello:world')]
    public function __invoke(string $name, bool $continue = false): void
    {
        $this->writeln("Hello {$name}!");
        
        if (! $continue && ! $this->confirm('Are you sure about this?')) {
            return;
        }
        
        $framework = $this->ask(
            question: 'What\'s your favourite framework?',
            options: [
                'Tempest',
                'Laravel',
                'Symfony',
            ],       
        );
        
        $this->success($framework);
    }
}
```