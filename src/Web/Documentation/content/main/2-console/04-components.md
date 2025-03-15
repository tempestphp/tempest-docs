---
title: Components
category: console
---

Console components are Tempest's way of providing interactivity via the console. There are a number
of built-in components provided, and it's super easy to build your own.

Components also provide an easy-to-use validation API, so that you can validate the user's input.

At the moment, cross-platform support is
still [a work in progress](https://github.com/tempestphp/tempest-console/issues/9).

## Built-in components

Tempest has a handful of built-in components for common use cases.

### The `ask` component

This component is used to get input from the user. It's actually a collection of three separate
components, depending on how you configure it.

You can use the `ask` component to ask textual questions:

```php
$name = $this->console->ask("What's your name?");
```

<video autoplay muted controls loop>
  <source src="/img/ask-a.mp4" type="video/mp4" />
</video>

You can pass in an array of options, which will make it so that the user must choose one:

```php
$result = $this->console->ask(
    question: 'Pick one:',
    options: ['a', 'b', 'c'],
);
```

<video autoplay muted controls loop>
  <source src="/img/ask-b.mp4" type="video/mp4" />
</video>

Finally, you can allow multiple choice options as well:

```php
$result = $this->console->ask(
    question: 'Pick several:',
    options: ['a', 'b', 'c'],
    multiple: true,
);
```

<video autoplay muted controls loop>
  <source src="/img/ask-c.mp4" type="video/mp4" />
</video>

### The `confirm` component

The `confirm` component is used to ask simple yes/no questions:

```php
$this->console->confirm('continue?');
```

<video autoplay muted controls loop>
  <source src="/img/confirm.mp4" type="video/mp4" />
</video>

The default answer will be `false`, but you can change it to true:

```
$this->{:hl-property:console:}->{:hl-property:confirm:}(
    {:hl-property:question:}: 'continue?',
    {:hl-property:default:}: {:hl-keyword:true:},
);
```

### The `password` component

The `password` component will ask a user to put in a password. The password is shown with `*` characters, but still available in plain text after submitted.

```php
$password = $this->console->password();
```

You can configure the component to ask for a password confirmation as well:

```php
$password = $this->console->password(confirm: true);
```

<video autoplay muted controls loop>
  <source src="/img/password.mp4" type="video/mp4" />
</video>

### The `progress` component

The `progress` components shows a progress bar while doing some work:

```php
$result = $this->console->progressBar(
    data: array_fill(0, 10, 'a'),
    handler: function ($i) {
        usleep(100000);

        return $i . $i;
    },
);
```

<video autoplay muted controls loop>
  <source src="/img/progress.mp4" type="video/mp4" />
</video>

### The `search` component

The `search` component shows an interactive search box with selectable results:

```php
$data = ['Brent', 'Paul', 'Aidan', 'Roman'];

$result = $this->console->search(
    'Search',
    function (string $query) use ($data): array {
        if ($query === '') {
            return [];
        }

        return array_filter(
            $data,
            fn (string $name) => str_contains(
                strtolower($name),
                strtolower($query)
            ),
        );
    }
);
```

<video autoplay muted controls loop>
  <source src="/img/search.mp4" type="video/mp4" />
</video>

## Making your own components

The docs for this section are still a work in progress, but you can check out the [existing components](https://github.com/tempestphp/tempest-console/tree/main/src/Components) to understand how they work.
