---
title: Exit code fallacy
author: brent
description: Was I wrong about exit codes?
tag: Thoughts
---

Last week I wrote [a blog post](https://tempestphp.com/blog/unfair-advantage/) comparing Symfony,
Laravel, and Tempest. It was very well received and I got a lot of great feedback. One thing stood
out though:
a [handful](https://x.com/_Codito_/status/1855210473706197276) [of](https://phpc.social/@wouterj/113453310817058010) [people](https://www.reddit.com/r/PHP/comments/1gmgpa2/unfair_advantage/lw2fntc/)
were adamant that the way I designed exit codes for console commands was absolutely wrong.

I was surprised that one little detail grabbed so much attention, after all it was just one example
amongst others, but it prompted people to respond, which led me to think: was I wrong?

I want to share my thought process today. I think it's a fascinating exercise in software design, and it will help me further process the feedback I got. It might inspire you as well, so in my mind, a win-win!

## Setting the scene

I designed console commands to feel very similar to web requests: a client sends a
request, or invokes a command. There's an optional payload — the body in case of a request, input
arguments in case of a console command. The request or invocation is mapped to a handler — the
controller action or command handler; and that handler eventually returns a response or exit code.

I like that symmetry between controller actions and command handlers. It makes Tempest feel more
cohesive and consistent because there is familiarity between different parts of the framework.
If you know one part, you'll have a much easier time learning another part. I believe
familiarity is a great selling point if you want people to try out something new.

In case of console commands though, I had to figure out how to deal with return types. Any PHP script that's run via the console must eventually exit with an exit code: a number between 0 and 255, indicating some kind of status. If you don't manually provide one, PHP will do it for you.

Exit codes might feel very similar to HTTP response codes: you return a number that has a meaning. In most cases, the exit code will be `0`, meaning success. In case of an error, the exit code can be anything between `1` and `255`, but `1` is considered "a standard" everywhere: it simply means there was some kind of failure. But apart from that?

> Apart from zero and the macros EXIT_SUCCESS and EXIT_FAILURE, the C standard does not define the
> meaning of return codes. Rules for the use of return codes vary on different platforms (see the
> platform-specific sections). — [Wikipedia](https://en.wikipedia.org/wiki/Exit_status)

That's a pretty important distinction between HTTP response status codes and console exit codes: an application is allowed to assign whatever meaning they want to any exit code. Luckily, some exit codes are now so commonly used that everyone agrees on their meaning: `0` for success, `1` for generic error, but also `2` for invalid command usage, `25` for a cancelled command, or `127` when a command wasn't found, and a handful more.

Apart from those few, an exit could mean anything depending on the context it originated from. A pretty vague system if you'd ask me, but hey, it is what it is.

Ideally though, I wanted Tempest's exit codes to be represented by an enum, just like HTTP status codes. I like the discoverability of an enum: you don't have to figure out how to construct it, it's just a collection of values. By representing exit codes like `0`, `1`, and `2` in an enum, developers have a much easier time understanding the meaning of "standard" exit codes:

```php
enum ExitCode: int
{
    case SUCCESS = 0;
    case ERROR = 1;
    case INVALID = 2;

    // …
}
```

Obviously, I should add a handful more exit codes here.

I like how a developers don't have to worry about learning the right exit codes, they could simply use the `ExitCode` enum and find what's right for them. It's "self-documented" code, and I like it.

```php
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

Apart from an enum, I also allowed console commands to return `void`. Whenever nothing is returned, Tempest considers the command to have successfully finished, and thus return `0`. Whenever an error occurs or exception is thrown, Tempest will convert it to `1`.

```php
use Tempest\Console\ConsoleCommand;
use Tempest\Console\ExitCode

final readonly class Package
{
    #[ConsoleCommand]
    public function all(): void
    {
        if (! $this->hasBeenSetup()) {
            throw new HasNotBeenSetup();
        }

        // Handle the command

        // Don't return anything
    }
}
```

When I talk about "focusing on the 95% case", this is a great example of what I
mean. 95% of console commands don't need fine-grained control over their exit codes. They take user
input, perform some actions, write output to the console, and will then exit successfully. Why
should developers be bothered with manually returning `0`, while it's only necessary to do so for edge cases? (I'm looking at you, Symfony 😅)

So, all in all, I like how the 95% case is solved:

- The `ExitCode` enum provides discoverability for commonly used exit codes.
- There's symmetry between HTTP status codes and console exit codes (both are enums in Tempest).
- Developers don't _have_ to return an exit code, Tempest will infer the most obvious one wherever possible.

But what about the real edge cases?

## My mistake

Whenever I say "focus on the 95% case", I also always add: "and make sure the other 5% is solvable, but it
doesn't have to be super convenient". And that's where I went wrong with my exit code design: I
wrapped the most common ones in an enum, but didn't account for all the other possibilities.

Ok, I actually did consider all other exit codes, but decided to ignore them "and revisit it later". This decision has led to a problem though, where the 5% use case cannot be solved! Developers simply can't return anything but those handful of predefined exit codes from a console command. That's a problem.

So, how to solve this? We brainstormed a couple of options on the [Tempest Discord](https://tempestphp.com/discord), and came up with two possible solutions:

#### 1. Exit codes as value objects

The downside of using an enum to model exit codes is that you can't have dynamic exit codes as they might differ in meaning depending on the context. An alternative to using an enum is to use a class instead — a value object:

```php
final readonly class ExitCode
{
    public function __construct(
        public int $code,
    ) {}

    public static function success(): self
    {
        return new self(0);
    }

    public static function error(): self
    {
        return new self(1);
    }
}
```

This way, you can still discover standard exit codes thanks to the static constructor, but you can also make custom ones wherever needed:

```php
class MyCommand
{
    #[ConsoleCommand]
    public function foo(): ExitCode
    {
        return ExitCode::success();
    }

    #[ConsoleCommand]
    public function bar(): ExitCode
    {
        return new ExitCode(48);
    }
}
```

On top of that, you could even throw an exception for invalid exit codes:

```php
final readonly class ExitCode
{
    public function __construct(
        public int $code,
    ) {
        if ($this->code < 0 || $this->code > 255) {
            throw new InvalidExitCode($this->code);
        }
    }

    // …
}
```

Not bad! Let's take a look at the other approach.

#### 2. Enums and ints

Let's say we keep our enum, but also allow console commands to return integers whenever people want to. In other words: the enum represents the exit codes that are "constant" or "standard", and all the other ones are represented by plain integers — if people really need them.

```php
class MyCommand
{
    #[ConsoleCommand]
    public function foo(): ExitCode
    {
        return ExitCode::SUCCESS;
    }

    #[ConsoleCommand]
    public function bar(): int
    {
        return 48;
    }
}
```

What are the benefits of this approach? To me, the biggest advantage here is the symmetry within the framework:

- There's already precedence of allowing multiple return types from command handlers and controller actions. Tempest knows how to deal with it. A controller action may return `Response` or `View`. A command handler may return `ExitCode` or `void`. Allowing `int` would be in line with that train of thought.
- HTTP response codes are modelled with an enum. Modelling exit codes with value objects would break symmetry. It would make the framework slightly less intuitive.
- Speaking of symmetry: Symfony and Laravel allow `int` as return types. Bash scripting requires an `int` to be returned. Allowing `int` is possibly something that people will instinctively reach for anyway. It would make sense.

Oh and, by the way: exit code validation could still be done with this approach, the only difference would be that the `InvalidExitCode` exception would be thrown from a different place, not when constructing the value object. The result for the end-user remains the same though: invalid exit codes will be blocked with an exception. Does it really matter to end users _where_ that exception originated from?

---

So those are the two options: value objects or enum + int. Of course, there are some possible variations like allowing both integers and value objects, using an interface and have the enum extend from it, or only allowing integers; but after lots of thinking, I settled on choosing between one of the two options I described.

And so the question is: now what? Well, I don't know, yet. I lean more towards the enum option because I value that symmetry most. But others disagree. I'd love to hear some more opinions though, so if you have something on your mind, feel free to share it [on the Tempest Discord](https://tempestphp.com/discord) (there's a discussion thread called "Console Command ExitCodes").

I hope to see you there, and be able to settle this question once and for all!

<img class="w-[1.66em] shadow-md rounded-full" src="/tempest-logo.png" alt="Tempest" />
