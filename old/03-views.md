Tempest views are plain PHP files. Every view has access to its data via `$this` calls. By adding an `@var` docblock to your view files, you'll get static insights and autocompletion.

```php
// View/home.view.php

Hello, <?= $this->name ?>
```

```php
final readonly class HomeController
{
    #[Get(uri: '/home')]
    public function __invoke(): View
    {
        return view('Views/home.view.php')
            ->data(
                name: 'Brent',
            )
    }
}
```

### Extending views

You can extend from other views like so:

```php
<?php 
// View/home.view.php

/** @var \Tempest\View\GenericView $this */ 
$this->extends('View/base.view.php');
?>

Hello, <?= $this->name ?>
```

You can pass in optional data to the parent view as well:

```php
$this->extends('View/base.view.php', title: 'Blog');
```

The parent view, in turn, can look like this, where `$this->slot()` will the content from the child's view.

```php
<?php /** @var \Tempest\View\GenericView $this */?>
<html lang="en">
<head>
    <title><?= $this->title ?? 'Home' ?></title>
</head>
<body>
<?= $this->slot() ?? '' ?>
</body>
</html>
```

### Named slots

If you want even more flexibility between parent and child views, you can rely on named slots to pass HTML between different parts of your views. Let's consider this parent view:

```php
<?php /** @var \Tempest\View\GenericView $this */?>
<html lang="en">
<head>
    <title><?= $this->title ?? 'Home' ?></title>
    
    <?= $this->slot('styles') ?>

    <?= $this->slot('scripts') ?>
</head>
<body>
<?= $this->slot() ?>
</body>
</html>
```

This parent view allows child views to dynamically inject styles and scripts in the right places, while still using familiar HTML syntax. It looks like this:

```php
<?php 
/** @var \Tempest\View\GenericView $this */ 
$this->extends('View/base.view.php');
?>

<x-slot name="styles">
    <style>
        body {
            background-color: red;
        }
    </style>
</x-slot>

<x-slot name="scripts">
    <script>
        console.log('hi');
    </script>
</x-slot>

The body of the view
```

Keep in mind that named slots are flexible. You don't have to declare all of them, and they don't need to be ordered the same way as the parent declared them:


```php
<?php 
/** @var \Tempest\View\GenericView $this */ 
$this->extends('View/base.view.php');
?>

The first part of the body.

<x-slot name="scripts">
    <script>
        console.log('hi');
    </script>
</x-slot>

The second part, the styles slot isn't present in this example.
```

### Including views

If you want to render a view within a view, you can do so by including it:

```php
<?= $this->include('Views/include-child.php', title: "other title", body: "Hello world") ?>
```

### View Models

Calling the `view()` helper function in controllers means you'll use the `GenericView` implementation provided by Tempest.

Many views however might benefit by using a dedicated class — a View Model. View Models will provide improved static insights both in your controllers and view files, and will allow you to expose custom methods to your views.

A View Model is a class the implements `View`, it can optionally set a path to a fixed view file, and provide data in its constructor. 

```php
final class HomeView implements View
{
    use BaseView;

    public function __construct(
        public readonly string $name,
    ) {
        $this->path = 'Modules/Home/home.view.php';
    }
}
```

Once you've made a View Model, you can use it in your controllers like so:

```php
final readonly class HomeController
{
    #[Get(uri: '/')]
    public function __invoke(): HomeView
    {
        return new HomeView(
            name: 'Brent',
        );
    }
}
```

Its view file would look like this:

```php
<?php
/** @var \App\Modules\Home\HomeView $this */

$this->extends('Views/base.view.php');
?>

Hello, <?= $this->name ?>
```

Note that you could also extend from within the View Model:

```php
final class HomeView implements View
{
    use BaseView;

    public function __construct(
        public readonly string $name,
    ) {
        $this->path = 'Modules/Home/home.view.php';
        $this->extends('Views/base.view.php');
    }
}
```

So that its view file would look like this:

```php
<?php /** @var \App\Modules\Home\HomeView $this */ ?>

Hello, <?= $this->name ?>
```

On top of that, View Models can expose methods to view files:

```php
final class BlogPostView implements View
{
    // …
    
    public function formatDate(DateTimeImmutable $date): string
    {
        return $date->format('Y-m-d');
    }
}
```

Which can be used like so:

```php
<?php /** @var \App\Modules\Home\HomeView $this */ ?>

<?= $this->formatDate($post->date) ?>
```

View Models are an excellent way of moving view-related complexity away from the controller, while simultaneously improving static insights.