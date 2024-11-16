---
title: Input and output
---

Every command can read and write from and to the terminal by injecting the `{php}Console` interface. You don't have to configure anything, Tempests takes care of injecting the right dependencies for you behind the scenes:

```php
// app/Package.php

use Tempest\Console\Console;
use Tempest\Console\ConsoleCommand;

final readonly class Package
{
    public function __construct(
        private Console $console,
    ) {}
    
    #[ConsoleCommand]
    public function all(): void {}
}
```

The `{php}Console` interface has a bunch of methods you can use:

- `{php}readln(): {:hl-type:string:}` — read one line
- `{php}read({:hl-type:int:} $bytes): {:hl-type:string:}` — read a specific amount of bytes
- `{php}write({:hl-type:string:} $contents): {:hl-type:Console:}` — write some text to the terminal
- `{php}writeln({:hl-type:string:} $line = ''): {:hl-type:Console:}` — write a line to the terminal
- `{php}ask({:hl-type:string:} $question, {:hl-type:?array:} $options = null, {:hl-type:bool:} $multiple = false, {:hl-type:array:} $validation = []): {:hl-type:string:}` — interactively ask a question
- `{php}confirm({:hl-type:string:} $question, {:hl-type:bool:} $default = false): {:hl-type:bool:}` — confirm with yes or no
- `{php}password({:hl-type:string:} $label = 'Password', {:hl-type:bool:} $confirm = false): {:hl-type:string:}` — retrieve a password, only works on terminals that support interactive components
- `{php}progressBar({:hl-type:iterable:} $data, {:hl-type:Closure:} $handler): {:hl-type:array:}` — show a progress bar while doing some work
- `{php}info({:hl-type:string:} $line): {:hl-type:Console:}` — write an info styled line to the terminal
- `{php}error({:hl-type:string:} $line): {:hl-type:Console:}` — write an error styled line to the terminal
- `{php}success({:hl-type:string:} $line): {:hl-type:Console:}` — write a success styled line to the terminal
- `{php}when({:hl-type:mixed:} $expression, {:hl-type:callable:} $callback): {:hl-type:Console:}` — only perform an action when a condition is met. The console is passed to the callable.
- `{php}component({:hl-type:ConsoleComponent:} $component, {:hl-type:array:} $validation = []): {:hl-type:mixed:}` — render a [console component](/docs/console/04-components).

## HasConsole

Instead of manually injecting the `Console` in your console command classes, you can also use the `HasConsole` trait. This trait will inject `Console` for you, and also provides shorthand methods on the class itself, instead of having to call `$this->console` manually:

```php
// app/Package.php

use Tempest\Console\HasConsole;
use Tempest\Console\ConsoleCommand;

final readonly class Package
{
    use HasConsole;
    
    #[ConsoleCommand]
    public function all(): void 
    {
        $answer = $this->ask('Please give your name');
        
        $this->writeln('Hello');
    }
}
```

## Output styling

This packages uses [`tempest/highlight`](https://github.com/tempestphp/highlight) to style console output. You can use HTML-like tags to style the output like so:

```php
$this->console->writeln("<success>{$line}</success>");
```

The following tags are available:

- `<em>Emphasize</em>` results in `{console}<em>Emphasize</em>`
- `<strong>Strong</strong>` results in `{console}<strong>Strong</strong>`
- `<u>Underline</u>` results in `{console}<u>Underline</u>`
- `<h1>Header 1</h1>` results in `{console}<h1>Header 1</h1>`
- `<h2>Header 2</h2>` results in `{console}<h2>Header 2</h2>`
- `<question>Question</question>` results in `{console}<question>Question</question>`
- `<error>Error</error>` results in `{console}<error>Error</error>`
- `<success>Success</success>` results in `{console}<success>Success</success>`
- `<comment>Comment</comment>` results in `{console}<comment>Comment</comment>`
- `<style="bg-red fg-blue underline">Styled</style>` results in `{console}<error><u><em>Styled</em></u></error>`

## Exit codes

Console commands may return an exit code if they wish to, but that's optional. If no exit code is provided, one will be automatically determined for you. 

```php
// app/Package.php

use Tempest\Console\ConsoleCommand;
use Tempest\Console\ExitCode

final readonly class Package
{
    #[ConsoleCommand]
    public function all(): ExitCode 
    {
        if (! $this->hasBeenSetup()) {
            return ExitCode::ERROR;
        }
        
        // …
        
        return ExitCode::SUCCESS;
    }
}
```