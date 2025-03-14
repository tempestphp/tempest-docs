---
title: Views
---

Tempest supports three templating engines: Tempest View, Twig, and Blade. Tempest View is a new templating engine, while Twig and Blade have widespread support because of Symfony and Laravel. Tempest View is the default templating engine when creating Tempest projects, but the end of this page discusses how to install and switch to Twig or Blade instead.

## View files

Here's an example of what a Tempest view looks like:

```html
<x-base title="Home">
    <x-post :foreach="$this->posts as $post">
        {!! $post->title !!}

        <span :if="$this->showDate($post)">
            {{ $post->date }}
        </span>
        <span :else>
            -
        </span>
    </x-post>
    <div :forelse>
        <p>It's quite empty here…</p>
    </div>

    <x-footer />
</x-base>
```

You can think of `tempest/view` as a superset of HTML, we'll dive deeper into the syntax later in this chapter.

## Returning Views

Returning views from controllers can be done in two ways: either by using the `{php}view()` function, or by returning a `{php}View` object.

```php
// app/HomeController.php

use Tempest\Router\Get;
use Tempest\View\View;
use function Tempest\view;

final readonly class HomeController
{
    #[Get(uri: '/home')]
    public function __invoke(): View
    {
        return view('Views/home.view.php')
            ->data(
                name: 'Brent',
                date: new DateTime(),
            );

        // Or

        return new HomeView(
            name: 'Brent',
            date: new DateTime(),
        );
    }
}
```

The `{php}view()` function will construct a generic view object for you. It's more flexible, but custom view objects offer some benefits.

### View paths

View files can be referenced in multiple ways. You can use absolute paths:

```php
view(__DIR__ . '/../Views/home.view.php');
```

Or you can use relative paths:


```php
view('Views/home.view.php');
```

When you're using relative paths, Tempest will search all registered [discovery locations](/docs/internals/discovery), as well as files relative to the controller where the view was created. For example, if you write `view('./home.view.php)`, Tempest will first check whether there's a view file within the same directory as where the controller requiring this view is located.

## Escaping data

Tempest supports both a custom echo tag, and raw PHP tags to write data to views:

```html
{{ $var }}
{!! $var !!}

<?= $var ?>
```

Note how the `{{ $var }}` notation will escape values automatically, while the `<?= $var ?>` will not. There's also the `{!! $var !!}` equivalent, which will print out a variable without escaping.

## View objects

The benefit of view objects — a dedicated class that represents a view — is that view object will improve static insights both in your controllers and view files, and offer more flexibility for view-specific data and methods.

A view object is a class that implements the `View` interface, it can optionally set a path to a fixed view file, and provide data in its constructor.

```php
// app/HomeView.php

use Tempest\View\View;
use Tempest\View\IsView;

final class HomeView implements View
{
    use IsView;

    public function __construct(
        public string $name,
        public DateTime $date,
    ) {
        $this->path = __DIR__ . '/home.view.php';
    }
}
```

The view file itself looks like this, note how we add a docblock to indicated that `$this` is an instance of `HomeView`.

```html
<!-- app/home.view.php -->

<?php /** @var \App\Modules\Home\HomeView $this */ ?>

Hello, {{ $this->name }}
```

Not only variables, but also view object methods are available within view file. Let's say our view object has a method `formatDate()`:

```php
// app/HomeView.php

use Tempest\View\View;

final class HomeView implements View
{
    // …

    public function formatDate(DateTimeImmutable $date): string
    {
        return $date->format('Y-m-d');
    }
}
```

Then a view file can access it like so:

```html
{{ $this->formatDate($post->date) }}
```

View objects are an excellent way of encapsulating view-related logic and complexity, moving it away from controllers, while simultaneously improving static insights.


## View components

Tempest views don't have concepts like _extending_ or _including_ other views. Instead, Tempest follows a component-based approach, and tries to stay as close to HTML as possible. A component can be a view file or PHP class, which eventually is referenced within other view files as HTML elements.

Let's say you want a base layout that can be used by all other views. You could create a base component like so:

```html
<!-- app/components/x-base.view.php -->

<x-component name="x-base">
    <html lang="en">
        <head>
            <title :if="$title">{{ $title }} | Tempest</title>
            <title :else>Tempest</title>
        </head>
        <body>

        <x-slot />

        </body>
    </html>
</x-component>
```

This component will be automatically discovered. Note that, in order for view components to be discovered, they must be suffixed with `.view.php`. If you want to, you can also prefix the filename with `x-`, in which case the view component's name will be inferred from the file name, and you don't have to use the explicit `<x-component>` tag anymore:

```html
<!-- app/components/x-base.view.php -->

<html lang="en">
    <head>
        <title :if="$title">{{ $title }} | Tempest</title>
        <title :else>Tempest</title>
    </head>
    <body>

    <x-slot />

    </body>
</html>

<!-- This view component will be accessible as <x-base> -->
```

Once a view component is discovered, you can use it in any other view. In our example, you can wrap any view you want within the `{html}<x-base></x-base>` tags, and the view's content will be injected within the base layout:

```html
<!-- app/home.view.php -->

<x-base :title="$this->post->title">
    <article>
        {{ $this->post->body }}
    </article>
</x-base>
```

As you can see, data to the parent component can be passed via attributes: all attributes on a view component element will be available within the view component. Attributes prefixed with a colon `:` will be evaluated as PHP code, while normal attributes will be treated as hard-coded values:

```html
<x-base :title="$this->post->title"></x-base>

<x-base title="Hello World"></x-base>
```

Both attributes in the above example will be available as `$title` in the `{html}<x-base/>` component:

```html
<!-- app/components/x-base.view.php -->

<x-component name="x-base">
    <title :if="$title">{{ $title }} | Tempest</title>
    <title :else>Tempest</title>
</x-component>
```

Please note some limitations of attribute mapping to PHP variables:

1. camelCase or PascalCase attribute names are automatically converted to all-lowercase variables, this is due to limitations in PHP's DOM extension: all attribute names are automatically converted to lowercase:

```html
<x-base metaType="test" />
```

```html
<x-component name="x-base">
    {{ $metatype }}
</x-component>
```

2. kebab-cased attributes are converted to camelCase variables:

```html
<x-parent meta-type="test" />
```

```html
<x-component name="x-base">
    {{ $metaType }}
</x-component>
```

3. snake_cased attributes are converted to camelCase variables:

```html
<x-parent meta_type="test" />
```

```html
<x-component name="x-base">
    {{ $metaType }}
</x-component>
```

Because of these limitations, **it is recommended to always use kebab-cased attribute names.**

## View inheritance and inclusion

Instead of extending or including views, Tempest relies on view components. From a technical point of view, there's no difference between extending or including components: each component can be embedded within a view or another component, and each component can define one or more slots to inject data in.

Here's an example of inheritance with view components:

```html
<!-- app/components/x-base.view.php -->

<x-component name="x-base">
    <html lang="en">
        <head>
            <title :if="$title">{{ $title }} | Tempest</title>
            <title :else>Tempest</title>
        </head>
        <body>

        <x-slot />

        </body>
    </html>
</x-component>
```

```html
<!-- app/home.view.php -->

<x-base title="Hello World">
    Contents
</x-base>
```

And here's an example of inclusion with view components:

```html
<!-- app/components/x-input.view.php -->

<x-component name="x-input">
    <div>
        <label :for="$name">{{ $label }}</label>

        <input :type="$type" :name="$name" :id="$name" />
    </div>
</x-component>
```

```html
<!-- home.view.php -->

<x-input name="user_email" type="email" label="Provide your email address" />
```

### Named slots

When using views components for inheritance, you can define zero, one, or more slots. Slots are used to inject data in from the view that's using this component. There's a default slot named `<x-slot />`, but you can define an arbitrary amount of named slots as well.

```html
<!-- app/components/x-base.view.php -->

<x-component name="x-base">
    <html lang="en">
        <head>
            <!-- … -->

            <x-slot name="styles" />
        </head>
        <body>

        <x-slot />

        </body>
    </html>
</x-component>
```

```html
<!-- app/home.view.php -->

<x-base title="Hello World">
    <!-- This part will be injected into the styles slot -->
    <x-slot name="styles">
        <style>
            body {
                /* … */
            }
        </style>
    </x-slot>

    <!-- Everything not living in a slot will be injected into the default slot -->
    <p>
        Hello World
    </p>
</x-base>
```

### Dynamic slots

Within a view component, there will always be a variable called `$slots`, which allows you to dynamically access the available named slots within the component:

```html
<x-component name="x-tabs">
    <x-codeblock :foreach="$slots as $slot">
        <h1>{{ $slot->name }}</h1>
        
        <h2>{{ $slot->attributes['language'] }}</h2>
        <!-- Or -->
        <h2>{{ $slot->language }}</h2>
        
        <div>{!! $slot->content !!}</div>
    </x-codeblock>
</x-component>

<x-tabs>
    <x-slot name="php" language="PHP">This is the PHP tab</x-slot>
    <x-slot name="js" language="JavaScript">This is the JS tab</x-slot>
    <x-slot name="html" language="HTML">This is the HTML tab</x-slot>
</x-tabs>
```

A `$slot` variable is an instance of `\Tempest\View\Slot`, and has a handful of properties:

- `$slot->name`: the slot's name
- `$slot->attributes`: all the attributes defined on the slot, they can also be accessed directly via `$slot->attributeName`
- `$slot->content`: the compiled content of the slot

### Dynamic attributes

Besides `$slots`, there will also be a variable called `$attributes` in every view component, which allows you to dynamically access the attributes that were passed to a view component. Note that only data attributes will be accessible, expression attributes will not. Also, attribute names will always be written as `kebab-case`.

```html
<x-component name="x-with-attributes">
    <div x-data="custom {{ $attributes['x-data'] }}"></div>
</x-component>

<x-with-attributes x-data="too"></x-with-attributes>

<!-- <div x-data="custom too"></div> -->
```

### Fallthrough attributes

There are a handful of special attributes that will always be merged within the view component's root element: `{html}class`, `{html}style`, and `{html}id`. 

```html
<x-component name="x-with-fallthrough-attributes">
    <div></div>
</x-component>

<x-with-fallthrough-attributes class="foo" style="background: red;" id="bar"></x-with-fallthrough-attributes>

<!-- <div class="foo" style="background: red;" id="bar"></div> -->
```

Note that `{html}class` and `{html}style` attributes will be merged together, while the `id` attribute will overwrite any original value defined in the view component:

```html
<x-component name="x-with-fallthrough-attributes">
    <div class="bar" style="text-decoration: underline;" id="original"></div>
</x-component>

<x-with-fallthrough-attributes class="foo" style="background: red;" id="overwrite"></x-with-fallthrough-attributes>

<!-- <div class="bar foo" style="text-decoration: underline; background: red;" id="overwrite"></div> -->
```


## View component classes

View components can live solely within a `.view.php` file, in which case they are called **anonymous view components**. However, it's also possible to define a class to represent a view component. One of the main benefits of doing so, is that **view component classes** are resolved via the container, meaning they can request any dependency available within your project, and Tempest will autowire it for you. View component classes are also discovered automatically, and must implement the `ViewComponent` interface.

For example, here's the implementation of `{html}<x-input>`, a view component shipped with Tempest that will render an input field, together with its original values and errors. It needs access to the `Session` to retrieve validation errors. This is a good use case for a view component class:

```php
use Tempest\View\ViewComponent;
use Tempest\View\Elements\ViewComponentElement;

final readonly class Input implements ViewComponent
{
    public function __construct(
        private Session $session,
    ) {
    }

    public static function getName(): string
    {
        return 'x-input';
    }

    public function compile(ViewComponentElement $element): string
    {
        $name = $element->getAttribute('name');
        $label = $element->getAttribute('label');
        $type = $element->getAttribute('type');
        $default = $element->getAttribute('default');

        $errors = $this->getErrorsFor($name);

        $errorHtml = '';

        if ($errors) {
            $errorHtml = '<div>' . implode('', array_map(
                fn (Rule $failingRule) => "<div>{$failingRule->message()}</div>",
                $errors,
            )) . '</div>';
        }

        return <<<HTML
<div>
    <label for="{$name}">{$label}</label>
    <input type="{$type}" name="{$name}" id="{$name}" value="{$this->original($name, $default)}" />
    {$errorHtml}
</div>
HTML;
    }

    public function original(string $name, mixed $default = ''): mixed
    {
        return $this->session->get(Session::ORIGINAL_VALUES)[$name] ?? $default;
    }

    /** @return \Tempest\Validation\Rule[] */
    public function getErrorsFor(string $name): array
    {
        return $this->session->get(Session::VALIDATION_ERRORS)[$name] ?? [];
    }
}
```

## Expression attributes

Expression attributes are attributes that will be evaluated as PHP code. We've already mentioned how expression attributes can be used to pass data into view components:

```html
<x-base :title="$this->post->title"></x-base>
```

But they can also be used to print out data as attribute values:

```html
<div :data-lang="$language"></div>

<!-- Will be rendered as -->
<div data-lang="php"></div>
```

There are a couple of rules to take into account when using expression attributes.

1. You can use PHP expressions in expression attributes. For example:

```html
<a :href="strtoupper('string')"></a>

<!-- Or -->
<a :href="$url ?? 'default'"></a>
```

2. You're not allowed to use echo tags in expression attributes:

```html
<!-- InvalidExpressionAttribute -->

<a :href="{{ $href }}"></a>
<a :href="<?php $href ?>"></a>
<a :href="<?= $href ?>"></a>
```

3. Expression attributes can be used to pass data — including objects to view components:

```html
<x-post :post="$post"></x-post>
```

4. However, only stringable values or expressions that evaluate to something printable can be used on normal HTML elements:

```html
<!-- Invalid: cannot print $post -->
<div :post="$post"></div>
```

5. Expression attributes on view components will never be automatically be printed anywhere within the view component. They will be available as variables though:

```html
<x-component>
    <h2>{{ $post->title }}</h2>
    <p>{{ $post->description }}</p>
</x-component>
```

## Boolean attributes

The HTML spec describes a special kind of attributes called [boolean attributes](https://html.spec.whatwg.org/multipage/common-microsyntaxes.html#boolean-attribute). These attributes don't have a value, but indicate `true` whenever they are present. The most common example is probably the `selected` attribute on `{html}<option>` tags, though there are a lot more (you can find them listed among others in [this table](https://html.spec.whatwg.org/multipage/indices.html#attributes-3)).

Tempest comes with built-in support to toggle boolean attributes on and off, you use expression attributes combined with boolean variables like so:

```html
<option :value="$value" :selected="$selected">{{ $label }}</option>
```

This will be compiled to this:

```html
<option value="value" {:hl-property:selected:}>Label</option>
```

Or this:

```html
<option value="value">Label</option>
```

Depending on whether `$selected` evaluates to `true` or `false`.

## Templates

Tempest views come with a built-in `{html}<x-template>` tag, which can be used to add control structures without rendering an actual HTML tag:

```html
<x-template :foreach="$posts as $post">
    <div>{{ $post->title }}</div>
</x-template>
```

Will be compiled to

```html
<div>Post A</div>
<div>Post B</div>
<div>Post C</div>
```

## View caching

Tempest views are compiled to plain PHP code before being rendered. In production, these compiled views should be cached. Enabling the view cache on production can be done by setting the `{txt}{:hl-property:CACHE:}` environment variable:

```env
{:hl-comment:# .env:}

{:hl-property:CACHE:}={:hl-keyword:true:}
```

For production projects with view caching enabled, it'll be important to **clear that view cache on deployment**. You do that by running `tempest cache:clear --all` on every deploy. You can read more about caching and view caching in [the cache chapter](/docs/framework/caching).

## Using Twig

In case you prefer to use Twig, you can switch to Twig exactly like Blade above. First, install Twig:

```
composer require twig/twig
```

Next, create a twig config file:

```php
// app/Config/twig.config.php

return new TwigConfig(
    viewPaths: [
        __DIR__ . '/../views/',
    ],

    cachePath: __DIR__ . '/../views/cache/',
);
```

Finally, switch over to using the Twig renderer:

```php
// app/Config/view.config.php

return new ViewConfig(
    rendererClass: \Tempest\View\Renderers\TwigViewRenderer::class,
);
```

## Using Blade

In case you prefer to use Blade instead of Tempest views, you can switch to Blade with a couple of steps. First, install Blade:

```
composer require tempest/blade
```

Next, create a blade config file:

```php
// app/Config/blade.config.php

return new BladeConfig(
    viewPaths: [
        __DIR__ . '/../views/',
    ],

    cachePath: __DIR__ . '/../views/cache/',
);
```

Finally, switch over to using the Blade renderer:

```php
// app/Config/view.config.php

return new ViewConfig(
    rendererClass: \Tempest\View\Renderers\BladeViewRenderer::class,
);
```
