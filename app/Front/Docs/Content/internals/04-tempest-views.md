---
title: Tempest views
---

This page describes the technical specification for Tempest views.

## General

A couple of **general naming conventions**:

- All Tempest view files are **suffixed with `.view.php`**.
- View components are always **prefixed with `x-`**: `{html}<x-base />`

Views can be created directly by using the `view()` function (this will create a `GenericView` object behind the scenes). Data is available within the view by passing **named arguments** in the `view()` function.

```php
return view(__DIR__ . '/home.view.php', variable: 'Hello World');
```

Views can also be created by returning an object that implements the `View` interface, all public properties and methods of this object are available to the view file:

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
    
    public function formatDate(DateTimeImmutable $date): string
    {
        return $date->format('Y-m-d');
    }
}
```

```html
{{ $name }}

{{ $this->formatDate($post->date) }}
```

View paths can be **absolute paths**, as well as **relative to any discovery location** available within Tempest.

```php
// Absolute path
return view(__DIR__ . '/home.view.php');

// Will search all registered discovery locations with this relative path
return view('home.view.php');
```

## Echo

The **shorthand echo** tag will print and automatically escape data:

```html
<h1>
    {{ $this->post->title }}
    {{ $this->raw($this->post->title) }}
</h1>
```

The **shorthand raw echo** tag will print data without escaping it:

```html
<h1>
    {!! $this->post->title !!}
    {!! $this->raw($this->post->title) !!}
</h1>
```

## Data scoping

Variables defined in views can be accessed either via `$this->variable` or `$variable` directly:

```php
view(__DIR__ . '/home.view.php', variable: 'hello');
```

```html
{{ $this->variable }}
{{ $variable }}
```

Variables passed to view components are only available directly via `$variable`, they are only **locally available within that view component**.

```html
<!-- home.view.php -->
<x-base title="Hello world"></x-base>
```

```html
<!-- x-base.view.php -->
<x-component name="x-base">
    {{ $title }}
    
    <!-- Won't work: -->
    {{ $this->title }}
</x-component>
```

A view can define local variables that are available within the whole view:

```html
<!-- home.view.php -->

<?php
$this->newVariable = 'foo';
?>
```

TODO: work in progress ([https://github.com/tempestphp/tempest-framework/issues/579](https://github.com/tempestphp/tempest-framework/issues/579))

## Attributes

All attributes prefixed with `:` will be parsed as PHP code, these are called **expression attributes**. Expression attributes can be added on any element: both on regular HTML elements, and view component elements. 

```html
<a :href="$this->post->uri"></a>
```

Attributes passed to view components will be available as variables within that component. These are called **attribute variables**:

```html
<!-- home.view.php -->
<x-base title="Hello world"></x-base>
```

```html
<!-- x-base.view.php -->
<x-component name="x-base">
    {{ $title }}
</x-component>
```

Attribute variables can also be defined as expression attributes:

```html
<!-- home.view.php -->
<x-base :title="$this->post->title"></x-base>
```

```html
<!-- x-base.view.php -->
<x-component name="x-base">
    {{ $title }}
</x-component>
```

Attribute to variable mapping adheres to the following **naming conventions**:

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

## Control structures

### If

```html
<div :if="$this->post->published">Published</div>
<div :elseif="$this->post->archived">Archived</div>
<div :else>Not published</div>
```

### Foreach

```html
<div :foreach="$this->posts as $post">{{ $post->title }}</div>
<div :forelse>Nothing</div>
```

## View components

Tempest components can be defined in two ways. First you can create PHP classes:

```php
final readonly class MyViewComponent implements ViewComponent
{
    public static function getName(): string
    {
        return 'x-my';
    }

    public function compile(ViewComponentElement $element): string
    {
        $foo = $element->getAttribute('foo');
        $bar = $element->getAttribute('bar');

        if ($foo && $bar) {
            return "<div foo=\"{$foo}\" bar=\"{$bar}\"><x-slot /></div>";
        }

        return '<div><x-slot /></div>';
    }
}
```

Or you can create anonymous components in `.view.php` files.

```html
<x-component name="x-my">
    <div :if="$this->foo && $this->bar" :foo="$this->foo" :bar="$this->bar">
        <x-slot />
    </div>
    <div :else>
        <x-slot />
    </div>
</x-component>
```

## View Component Slots

The default slot `{html}<x-slot />` will contain the contents of a component:

```html
<x-my>
    <p>These contents will end up in the default slot</p>
</x-my>
```

You can define multiple named slots:

```html
<x-component name="x-my">
    <header>
        <x-slot name="header" />
    </header>
    
    <x-slot />
    
    <footer>
        <x-slot name="footer" />
    </footer>
</x-component>
```

```html
<x-my>
    This goes in the default slot
    
    <x-slot name="header">
        The title
    </x-slot>

    <x-slot name="footer">
        The footer
    </x-slot>
    
    This will be appended into the main slot
</x-my>
```