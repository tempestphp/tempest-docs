---
title: Views
---

Tempest supports two templating engines: Tempest views, and Blade. Tempest views is an experimental templating engine, while Blade has widespread support because of Laravel. Tempest views is the default templating engine. The end of this page discusses how to install Blade instead.

## View files

Tempest views are plain PHP files, though they also support a custom syntax. You can mix or choose a preferred style. 

This is the standard PHP style:

```html
<ul>
    <?php foreach ($this->posts as $post): ?>
        <li>
            <?= $post->title ?>
            
            <?php if($this->showDate($post)): ?>
                <span>
                    <?= $post->date ?>
                </span>
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
</ul>
```

And this is the custom syntax:

```html
<ul>
    <li :foreach="$this->posts as $post">
        {{ $post->title }}
        
        <span :if="$this->showDate($post)">
            {{ $post->date }}
        </span>
    </li>
</ul>
```

## Returning Views

Returning views from controllers can be done in two ways: either by using the `{php}view()` function, or by return a `{php}View` object.

```php
final readonly class HomeController
{
    #[Get(uri: '/home')]
    public function __invoke(): View
    {
        return view('Views/home.view.php')
            ->data(
                name: 'Brent',
                date: new DateTime(),
            )
            
        // Or
        
        return new HomeView(
            name: 'Brent',
            date: new DateTime(),
        )
    }
}
```

The `{php}view()` function will construct a generic view object for you. It's more flexible, but custom view objects offer some benefits.

## View objects

The benefit of creating separate classes per view, is that a such a custom view class will provide improved static insights both in your controllers and view files, and will allow you to expose custom methods to your views.

A view object is a class the implements `{php}View`, it can optionally set a path to a fixed view file, and provide data in its constructor.

```php
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

The view file itself looks like this:

```html
<?php /** @var \App\Modules\Home\HomeView $this */ ?>

Hello, <?= $this->name ?>
```

All variables and methods of your custom view class, are available within the view file:

```php
final class HomeView implements View
{
    // …
    
    public function formatDate(DateTimeImmutable $date): string
    {
        return $date->format('Y-m-d');
    }
}
```

Which can be used like so:

```html
<?= $this->formatDate($post->date) ?>
```

View objects are an excellent way of moving view-related complexity away from the controller, while simultaneously improving static insights.

Finally, view object can be passed into the `{php}response()` function, allowing you to control additional headers, the response's status code, etc.

```php
final readonly class HomeController
{
    #[Get(uri: '/')]
    public function __invoke(): Response
    {
        $view = new HomeView(
            name: 'Brent',
        );
        
        return response()
            ->setView($view)
            ->setStatus(Status::CREATED)
            ->addHeader('x-custom-header', 'value');
    }
}
```


## View components

Tempest views don't have concepts like _extending_ or _including_ views. Instead, all views are component-based, and try to stay as close to HTML as possible.

Let's say you want a base layout that can be used by all other views. You would create a base component like so:

```html
<x-component name="x-base">
    <html lang="en">
        <head>
            <title :if="$this->title">{{ $this->title }} | Tempest</title>
            <title :else>Tempest</title>
        </head>
        <body>
    
        <x-slot />
    
        </body>
    </html>
</x-component>
```

This component will be automatically discovered. From now on, you can wrap any view you want within the `{html}<x-base></x-base>` tag, and it'll be injected within the base layout view:

```html
<x-base :title="$this->post->title">
    <article>
        {{ $this->post->body }} 
    </article>
</x-base>
```

As you can see, data to the parent component can be passed via attributes.

From a technical point of view, there's no difference between extending or including components: each component can be embedded within a view, and each component can define one or more slots to inject data in. For example, here's a `{html}<x-input>` component:

```html
<x-component name="x-input">
    <div>
        <label :for="$name">
            <x-slot />
        </label>
        
        <input :type="$type" :name="$name" :id="$name" />
    </div>
</x-component>
```

And here's how you'd use it:

```html
<div>
    …
    
    <x-input name="user_email" type="email">
        Provide your email address
    </x-input>
</div>
```

### View component classes

Instead of defining view components directly within a view file, you a provide a class to represent the view components. The benefit of doing so is that you've got access to a lot more functionality from within PHP.

For example, here's the implementation of `{php}<x-input>`, a view component shipped with Tempest that will render an input field, together with its original values and errors.

```php
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

    public function render(GenericElement $element, ViewRenderer $renderer): string
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

Creating view components is as easy as making a class implement `{php}ViewComponent`. Tempest will automatically discover it for you. View components are resolved via the container, so autowiring is available within the constructor.

## Using Blade

In case you prefer to use Blade instead of Tempest views, you can switch to Blade with a couple of steps. First, install Blade:

```
composer require jenssegers/blade
composer require illuminate/view:~11.7.0
```

Next, create a blade config file:

```php
// app/Config/blade.php

return new BladeConfig(
    viewPaths: [
        __DIR__ . '/../views/',
    ],
    
    cachePath: __DIR__ . '/../views/cache/',
);
```

Finally, switch over to using the Blade renderer:

```php
// app/Config/view.php

return new ViewConfig(
    rendererClass: \Tempest\View\Renderers\BladeViewRenderer::class,
);
```

And that's it!