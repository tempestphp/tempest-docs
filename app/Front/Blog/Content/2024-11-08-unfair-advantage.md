---
title: Unfair Advantage
author: Brent
description: Why Tempest instead of Symfony or Laravel?
---

Someone asked me: [_why Tempest_](https://bsky.app/profile/laueist.bsky.social/post/3l7y5v3bm772y)? What areas do I expect Tempest to be better in than Laravel or Symfony? What gives me certainty that Laravel or Symfony won't just be able to copy what makes Tempest currently unique? What is Tempest's _unfair advantage_ compared to existing PHP frameworks?

I love this question: of course there will be a small group of people excited about Tempest and vocal about it, but does it really stand a chance against the real frameworks?

Ok so, here's my answer: Tempest's unfair advantage is **its ability to start from scratch and the courage to question and rethink the things we have gotten used to**.

Let me work through that with a couple of examples.

## The Curse

First is the curse of any mature project: with popularity comes the need for backwards compatibility. Laravel can't make 20 breaking changes over the course of one month. They have a huge userbase, and they prefer stability. If Tempest ever grows popular enough, we will have to deal with the same problem, but for now it opens opportunities.

Combine that with the fact that Tempest started out in 2023 instead of 2011 as Laravel did or 2005 as Symfony did. PHP and its ecosystem have evolved tremendously. Laravel's facades are a good example: there is a small group of hard-core fans of facades to this day; but my view on facades (or better: service locators with a magic static getter) is that they represent a pattern that made sense at a time when PHP didn't have a proper type system yet (so no easy autowiring), where IDEs were a lot less popular (so no autocompletion and auto importing), and where static analysis in PHP was non-existent.

That brings us back to the backwards compatibility curse. Facades have become so ingrained into the framework that it would be madness to remove them. It's naive to think the Tempest won't have their facades-like warts ten years from now — it will — but at this stage, we're lucky to be able to start from a clean slate where we can embrace modern PHP as the standard, not the exception. To make that concrete:

- Heavy reliance on attributes wherever possible
- Embracing enums from the start
- More reflection usage, which performance impact has become insignificant since the PHP 7 era
- Using the type system as much as possible: for dependency autowiring, console definitions, ORM and database models, event and command handlers, …

## The courage to question

The second part of Tempest's advantage is the courage to question and rethink and question the things we have gotten used to. One of the best examples to illustrate this is `symfony/console`: the de-facto standard for console applications in PHP for over a decade. It's used everywhere, it has the absolute monopoly when it comes to building console applications in PHP.

So I thought… what if I'd have to build a console framework today from scratch? What would that look like? Let's take a look at an example from the Symfony docs:

```php
#[AsCommand(name: 'make:user')]
class MakeUserCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED)
            ->addArgument('password', InputArgument::REQUIRED)
            ->addOption('admin', null, InputOption::VALUE_NONE);
    }
    
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $email = $this->getArgument('email');
        $password = $this->getArgument('password');
        $isAdmin = $this->getOption('admin');
        
        // …  
    
        return Command::SUCCESS;
    }
}
```

The same command in Laravel would look something like this:

```php
class MakeUser extends Command
{
    protected $signature = 'make:user {email} {password} {--admin}';
 
    public function handle(): void
    {
        $email = $this->argument('email');
        $password = $this->argument('password');
        $isAdmin = $this->option('admin');
    
        // …
    }
}
```

And here's Tempest's approach:

```php
use Tempest\Console\ConsoleCommand;
use Tempest\Console\HasConsole;

final readonly class Make
{   
    use HasConsole;
    
    #[ConsoleCommand]
    public function user(string $email, string $password, bool $isAdmin): void
    {
        // …
    }
}
```

Which differences do you notice? 

- In Tempest, console commands don't extend from any class — in fact nothing does — there's a very good reason for this, inspired by Rust. If you want to know the reasoning, you can watch me explain [it here](https://www.youtube.com/watch?v=HK9W5A-Doxc).
- Laravel's `Console` class extends from Symfony's implementation, which means there its constructor isn't free for dependency injection.
- Compare the verbose `configure()` method in Symfony vs Laravel's `$definition` string, vs Tempest's approach. Which one feels the most natural? The only thing you need to know in Tempest is PHP. In Symfony you need a separate configure method, while in Laravel you need to remember the textual syntax for the definition command. That's all unnecessary boilerplate. 
- Symfony's console commands must return an exit code — an integer. It's probably because of compatibility reasons that it's an int and not an enum. You can optionally return an exit code in Tempest as well, but of course it's an enum:

```php
use Tempest\Console\ConsoleCommand;
use Tempest\Console\HasConsole;
use Tempest\Console\ExitCode

final readonly class Package
{
    use HasConsole;
    
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

- Finally, 

```php
use Tempest\Console\Middleware\CautionMiddleware;

#[ConsoleCommand(
    middleware: [CautionMiddleware::class]
)]
public function __invoke(): void
{
    $this->console->error('something cautionous');
}
```

```console
<h2>Caution! Do you wish to continue?</h2> [<em><u>yes</u></em>/no] 
<error>something cautionous</error> 
```


## No legacy

## Embracing modern PHP

## OSS experience

## People want it

## Standalone components

https://x.com/mika_si/status/1852673145968136546

https://x.com/fifbear/status/1852635468879319318

https://bsky.app/profile/bagwaa.bsky.social/post/3l7xamytc742f

https://github.com/tempestphp/tempest-framework/issues/681

https://www.reddit.com/r/PHP/comments/1fi2dny/introducing_tempest_the_framework_that_gets_out/lngag06/