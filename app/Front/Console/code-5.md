```php
final readonly class Hello
{
    use HasConsole;

    #[ConsoleCommand]
    public function world(
        string $name, 
        string $greeting = 'Hello', 
        bool $continue = false,
    ): void {
        $this->success("Hello {$name}!");
        
        if (! $continue && ! $this->confirm('Continue?')) {
            $this->error('Stopped');
            
            return;
        }
        
        // …
    }
}
```