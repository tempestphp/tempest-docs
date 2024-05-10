---
title: Middleware
---

`tempest/console` has support for middleware, a well known concept within the context of web applications, which also makes building a lot of console features easier.

Console middleware can be applied both globally (to all console commands), or on a per-command basis.

The global middleware stack is defined within `{php}ConsoleConfig`. Note that the default stack is provided out of the box, you only need to add a custom config file if you want to change this stack.

```php
// Config/console.php

use Tempest\Console\ConsoleConfig;

return new ConsoleConfig(
    // …
    
    middleware: [
        OverviewMiddleware::class,
        ResolveOrRescueMiddleware::class,
        ConsoleExceptionMiddleware::class,
        HelpMiddleware::class,
    ],
);
```

Individual middleware can be added on top of this stack by passing it into the `{php}#[ConsoleCommand]` attribute:

```php
final readonly class ForceCommand
{
    #[ConsoleCommand(
        middleware: [ForceMiddleware::class]
    )]
    public function __invoke() { /* … */ }
}
```

Let's take a look at the built-in middleware that Tempest provides.

### OverviewMiddleware

Renders the console command overview when no specific command is provided.

```console
./tempest

<h1>Tempest</h1> 

<h2>General</h2> 
 <em>install</em> [<em>--force</em>=false] - Interactively install Tempest in your project
 <em>routes</em> - List all registered routes
 <em>serve</em> [<em>host</em>='localhost:8000'] [<em>publicDir</em>='public/'] - Start a PHP development server
 <em>deploy</em>

<comment>…</comment>
```

### ResolveOrRescueMiddleware

Shows a list of similar commands when you mistyped a command:

```console
./tempest migrate

<error>Command migrate not found</error> 
<h2>Did you mean to run one of these?</h2> 
[x] <em>migrate:down</em>
[ ] migrate:up

Press <em>enter</em> to confirm, <em>ctrl+c</em> to cancel
```

### ConsoleExceptionMiddleware

Handles exceptions that while running a console command.

```console
./tempest fail

<error>Exception</error> 
<error>A message from the exception default</error> 

18 }
19 
20 function failingFunction(string $string)
21 {
22     throw new Exception("A message from the exception {$string}"); <error><</error> 
23 }

<u>/Users/brent/Dev/tempest-console/tests/Fixtures/FailCommand.php:22</u>

```

### HelpMiddleware

Adds the global `--help` and `-h` flags to all commands.

```console
./tempest serve --help  
<h2>Usage</h2> <em>serve</em> [<em>host</em>='localhost:8000'] [<em>publicDir</em>='public/'] - Start a PHP development server
```

### ForceMiddleware

Adds the global `--force` and `-f` flags to all commands. Using these flags will cause tempest to skip all `{php}$console->confirm()` calls.

```php
#[ConsoleCommand(
    middleware: [ForceMiddleware::class]
)]
public function __invoke()
{
    // This part will be skipped when the `-f` flag is applied
    if (! $this->console->confirm('continue?')) {
        return;
    }

    $this->console->writeln('continued');
}
```

### CautionMiddleware

Adds a warning before running the command in production or staging.

```php
#[ConsoleCommand(
    middleware: [CautionMiddleware::class]
)]
public function __invoke()
{
    $this->console->error('something cautionous');
}
```

```console
<h2>Caution! Do you wish to continue?</h2> [<em><u>yes</u></em>/no] 
<error>something cautionous</error> 
```

## Building your own middleware

You can create your own middleware by implementing the `{php}ConsoleMiddleware` interface:

```php
final readonly class HelloWorldMiddleware implements ConsoleMiddleware
{
    public function __construct(private Console $console)
    {
    }

    public function __invoke(Invocation $invocation, callable $next): void
    {
        if ($invocation->argumentBag->get('hello')) {
            $this->console->writeln('Hello world!')
        }

        $next($invocation);
    }
}
```

Middleware classes will be autowired by the container, so you can use the constructor to inject any dependency you'd like. The `{php}Invocation` object contains everything you need about the context for the current console command invocation:

- `{php}$invocation->argumentBag` contains the argument bag with all the input provided by the user.
- `{php}$invocation->consoleCommand` an instance of the `{php}#[ConsoleCommand]` attribute for the matched console command. This property will be `null` if you're not using `{php}ResolveOrRescueMiddleware` or if your middleware runs before it.
