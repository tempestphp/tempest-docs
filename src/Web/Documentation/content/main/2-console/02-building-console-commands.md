---
title: Building console commands
---

Any method tagged with the `{php}#[ConsoleCommand]` attribute will be automatically discovered and be available within the console application. By default, you don't have to pass in any parameters to the `{php}#[ConsoleCommand]` attribute, since Tempest will use the class and method names to generate a command name:

```php
// app/Package.php

use Tempest\Console\ConsoleCommand;

final readonly class Package
{
    #[ConsoleCommand]
    public function all(): void {}

    #[ConsoleCommand]
    public function info(string $name): void {}
}
```

These two methods will be accessible via the `package:all` and `package:info` commands:

```console
~ ./tempest

<h2>Package</h2>
 <strong><em>package:all</strong></em>
 <strong><em>package:info</strong></em> <<em>name</em>>
```

Tempest will use method's parameter list to define the command's definition. For example, this parameter list:

```php
// app/Package.php

use Tempest\Console\ConsoleCommand;

final readonly class Package
{
    #[ConsoleCommand]
    public function make(
        string $name,
        string $description = '',
        bool $force = false,
    ): void {}
}
```

Will generate this command definition:

```console
~ ./tempest

<h2>Package</h2>
 <strong><em>package:make</strong></em> <<em>name</em>> [<em>description</em>=''] [<em>--force</em>=false]
```

For more fine-grained control, the `{php}#[ConsoleCommand]` attribute takes a couple of optional parameters:

```php
// app/Package.php

use Tempest\Console\ConsoleCommand;

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

Finally, you can add optional `{php}#[ConsoleArgument]` attributes to parameters as well:

```php
use Tempest\Console\ConsoleArgument;

public function info(
    #[ConsoleArgument(
        description: 'The name of the package',
        help: 'Extended help text for this argument',
        aliases: ['n'],
    )]
    string $name,
): void {}
```
