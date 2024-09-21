```php
final readonly class Hello
{
    use HasConsole;

    #[ConsoleCommand]
    public function world(string $name): void
    {
        $this->success("Hello {$name}!");
    }
}
```