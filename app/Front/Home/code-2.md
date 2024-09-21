```php
final readonly class MigrateUpCommand
{
    public function __construct(
        private Console $console,
        private MigrationManager $migrationManager,
    ) {}

    #[ConsoleCommand(
        name: 'migrate:up',
        description: 'Run all new migrations',
        middleware: [ForceMiddleware::class, CautionMiddleware::class],
    )]
    public function __invoke(): void
    {
        $this->migrationManager->up();

        $this->console->success("Everything migrated");
    }

    #[EventHandler]
    public function onMigrationMigrated(MigrationMigrated $migrationMigrated): void
    {
        $this->console->writeln("- {$migrationMigrated->name}");
    }
}
```