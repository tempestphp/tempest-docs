---
title: Building Console Commands
---

Any method tagged with the `{php}#[ConsoleCommand]` attribute will be automatically available via the console. By default, you don't have to pass in any parameters, as Tempest will use the class and method names to generate a command name:

```php
use App\ConsoleCommand;

final readonly class Package
{
    #[ConsoleCommand]
    public function all(): void {}
    
    #[ConsoleCommand]
    public function info(string $name): void {}
}
```

```console
~ ./console

<h2>Package</h2>
 <strong><em>package:all</strong></em>
 <strong><em>package:info</strong></em> <<em>name</em>>
```

Note how a method's parameter list is used to define the command's definition:

```php
final readonly class Package
{
    #[ConsoleCommand]
    public function make(
        string $name, 
        string $description = '', 
        bool $force = false
    ): void {}
}
```

```console
~ ./console

<h2>Package</h2>
 <strong><em>package:all</strong></em>
 <strong><em>package:info</strong></em> <<em>name</em>>
 <strong><em>package:make</strong></em> <<em>name</em>> [<em>description</em>=''] [<em>--force</em>=false]
```

For more fine-grained control, the `{php}#[ConsoleCommand]` attribute takes a couple of optional parameters:

```php
final readonly class Package
{
    #[ConsoleCommand(
        name: 'packages:all',
        description: 'List all packages',
        aliases: ['pa'],
        help: 'Extended help text explaining what this command does.'
    )]
    public function all(): void {}
}
```