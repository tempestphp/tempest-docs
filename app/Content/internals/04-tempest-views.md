---
title: Tempest views
---

This page describes the technical specification for Tempest views.

## Attributes

All attributes prefixed with `:` will be parsed as PHP code. 

```html
<a :href="$this->post->uri"></a>
```

## Echo

```html
<h1>
    {{ $this->post->title }}
    {{ $this->raw($this->post->title) }}
</h1>
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
<div :foreach>Nothing</div>
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

    public function render(GenericElement $element, ViewRenderer $renderer): string
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

### Component arguments

Data can be passed to components by using the `:attribute` syntax:

```html
<x-my :foo="$this->foo" :bar="$this->bar" />
```

### Slots

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