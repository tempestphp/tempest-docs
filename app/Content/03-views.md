---
title: Views
---

Tempest views are plain PHP files. Every view has access to its data via `$this` calls. By adding an `@var` docblock to your view files, you'll get static insights and autocompletion.

```php
<?php /** @var <hljs type>\Tempest\View\GenericView</hljs> $this */ ?>

Hello, <?= $this-><hljs prop>name</hljs> ?>
```

```php
final <hljs keyword>readonly</hljs> class HomeController
{
    #[<hljs type>Get</hljs>(<hljs prop>uri</hljs>: '<hljs value>/home</hljs>')]
    public function __invoke(): View
    {
        return <hljs prop>view</hljs>('Views/home.view.php')
            -><hljs prop>data</hljs>(
                <hljs prop>name</hljs>: 'Brent',
                <hljs prop>date</hljs>: new <hljs type>DateTime</hljs>(),
            )
    }
}
```

### Extending views

Views can extend from other views like so:

```php
<?php 
/** @var <hljs type>\Tempest\View\GenericView</hljs> $this */ 
$this-><hljs prop>extends</hljs>('View/base.view.php');
?>

Hello, <?= $this-><hljs prop>name</hljs> ?>
```

You can pass in optional data to the parent view as well:

```php
$this-><hljs prop>extends</hljs>('View/base.view.php', <hljs prop>title</hljs>: 'Blog');
```

The parent view, can look define _slots_ by using `$this->slot()`. Each slot can render content from the child's view. The default slot can be called without any name.

```php
<?php /** @var <hljs type>\Tempest\View\GenericView</hljs> $this */?>

<<hljs keyword>html</hljs> <hljs prop>lang</hljs>="en">

    <<hljs keyword>head</hljs>>
        <<hljs keyword>title</hljs>><?= $this-><hljs prop>title</hljs> ?? 'Home' ?></<hljs keyword>title</hljs>>
    </<hljs keyword>head</hljs>>
    
    <<hljs keyword>body</hljs>>
        <hljs comment><!-- 
            Everything rendered in the child view 
            will be placed inside this slot: 
        --></hljs>
        
        <?= $this-><hljs prop>slot</hljs>() ?>
    </<hljs keyword>body</hljs>>

</<hljs keyword>html</hljs>>
```

### Named slots

If you want even more flexibility between parent and child views, you can rely on named slots to pass HTML between different parts of your views. Let's consider this parent view:

```php
<<hljs keyword>head</hljs>>
    <<hljs keyword>title</hljs>><?= $this-><hljs prop>title</hljs> ?? 'Home' ?></<hljs keyword>title</hljs>>
    
    <?= $this-><hljs prop>slot</hljs>('styles') ?>

    <?= $this-><hljs prop>slot</hljs>('scripts') ?>
</<hljs keyword>head</hljs>>

<<hljs keyword>body</hljs>>
    <?= $this-><hljs prop>slot</hljs>() ?>
</<hljs keyword>body</hljs>>
```

This parent view allows child views to dynamically inject styles and scripts in the right places, while still using familiar HTML syntax. It looks like this:

```php
<?php 
/** @var <hljs type>\Tempest\View\GenericView</hljs> $this */ 
$this-><hljs prop>extends</hljs>('View/base.view.php');
?>

<<hljs keyword>x-slot</hljs> <hljs prop>name</hljs>="styles">
    <<hljs keyword>style</hljs>>
        <hljs keyword>body</hljs> {
            <hljs prop>background-color</hljs>: red;
        }
    </<hljs keyword>style</hljs>>
</<hljs keyword>x-slot</hljs>>

<<hljs keyword>x-slot</hljs> <hljs prop>name</hljs>="scripts">
    <<hljs keyword>script</hljs>>
        console.<hljs prop>log</hljs>('hi');
    </<hljs keyword>script</hljs>>
</<hljs keyword>x-slot</hljs>>

The body of the view
```

Keep in mind that named slots are flexible. You don't have to declare all of them, and they don't need to be ordered the same way as the parent declared them:


```txt
The first part of the body.

<<hljs keyword>x-slot</hljs> <hljs prop>name</hljs>="scripts">
    <<hljs keyword>script</hljs>>
        console.<hljs prop>log</hljs>('hi');
    </<hljs keyword>script</hljs>>
</<hljs keyword>x-slot</hljs>>

The second part, the styles slot isn't present in this example.
```

### Including views

If you want to render a view within a view, you can do so by including it:

```php
<?= $this-><hljs prop>include</hljs>('Views/include-child.php', <hljs prop>title</hljs>: "other title", <hljs prop>body</hljs>: "Hello world") ?>
```

### View Models

Calling the `<hljs prop>view</hljs>()` helper function in controllers means you'll use the `<hljs type>GenericView</hljs>` implementation provided by Tempest.

Many views however might benefit by using a dedicated class — a View Model. View Models will provide improved static insights both in your controllers and view files, and will allow you to expose custom methods to your views.

A View Model is a class the implements `<hljs type>View</hljs>`, it can optionally set a path to a fixed view file, and provide data in its constructor. 

```php
use <hljs type>Tempest\View\View</hljs>;
use <hljs type>Tempest\View\IsView</hljs>;

final class HomeView implements View
{
    use <hljs type>IsView</hljs>;

    public function __construct(
        <hljs keyword>public readonly</hljs> <hljs type>string</hljs> <hljs prop>$name</hljs>,
    ) {
        $this-><hljs prop>path</hljs>('Modules/Home/home.view.php');
    }
}
```

Once you've made a View Model, you can use it in your controllers like so:

```php
final <hljs keyword>readonly</hljs> class HomeController
{
    #[<hljs type>Get</hljs>(<hljs prop>uri</hljs>: '/')]
    public function __invoke(): <hljs type>HomeView</hljs>
    {
        return new <hljs type>HomeView</hljs>(
            <hljs prop>name</hljs>: 'Brent',
        );
    }
}
```

Its view file would look like this:

```php
<?php
/** @var <hljs type>\App\Modules\Home\HomeView</hljs> $this */
$this-><hljs prop>extends</hljs>('Views/base.view.php');
?>

Hello, <?= $this-><hljs prop>name</hljs> ?>
```

Note that you could also extend from within the View Model:

```php
final class HomeView implements View
{
    use <hljs type>IsView</hljs>;

    public function __construct(
        <hljs keyword>public readonly</hljs> <hljs type>string</hljs> $name,
    ) {
        $this-><hljs prop>path</hljs>('Modules/Home/home.view.php');
        $this-><hljs prop>extends</hljs>('Views/base.view.php');
    }
}
```

So that its view file would look like this:

```php
<?php /** @var <hljs type>\App\Modules\Home\HomeView</hljs> $this */ ?>

Hello, <?= $this-><hljs prop>name</hljs> ?>
```

On top of that, View Models can expose methods to view files:

```php
final class BlogPostView implements View
{
    // …
    
    public function formatDate(<hljs type>DateTimeImmutable</hljs> $date): string
    {
        return $date-><hljs prop>format</hljs>('Y-m-d');
    }
}
```

Which can be used like so:

```php
<?php /** @var <hljs type>\App\Modules\Home\HomeView</hljs> $this */ ?>

<?= $this-><hljs prop>formatDate</hljs>($post-><hljs prop>date</hljs>) ?>
```

View Models are an excellent way of moving view-related complexity away from the controller, while simultaneously improving static insights.

Finally, View Models can be passed into the `<hljs prop>response</hljs>` function, allowing you to control additional headers, the response's status code, etc.

```php
final <hljs keyword>readonly</hljs> class HomeController
{
    #[<hljs type>Get</hljs>(<hljs prop>uri</hljs>: '/')]
    public function __invoke(): <hljs type>Response</hljs>
    {
        $view = new <hljs type>HomeView</hljs>(
            <hljs prop>name</hljs>: 'Brent',
        );
        
        return <hljs prop>response</hljs>()
            -><hljs prop>setView</hljs>($view)
            -><hljs prop>setStatus</hljs>(<hljs type>Status</hljs>::<hljs prop>CREATED</hljs>)
            -><hljs prop>addHeader</hljs>('x-custom-header', 'value');
    }
}
```

### Forms

// TODO