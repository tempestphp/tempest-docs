---
title: Major updates to Tempest View
description: Tempest 1.5 released with some major improvements to its templating engine
author: brent
tag: Release
---

Today we released Tempest version 1.5, which includes a bunch of improvements to [Tempest View](/docs/essentials/views), the templating engine that ships by default with the framework. Tempest also has support for Blade and Twig, but we designed Tempest View to take a unique approach to templating with PHP, and I must say: it looks excellent! (I might be biased.)

Designing a new language is hard, even if it's "only" a templating language, which is why we marked Tempest View as experimental when Tempest 1.0 released. This meant the package could still change over time, although we try to keep breaking changes at a minimum. 

With the release of Tempest 1.5, we did have to make a handful of breaking changes, but overall they shouldn't have a big impact. And I believe both changes are moving the language forward in the right direction. In this post, I want to highlight the new Tempest View features and explain why they needed a breaking change or two.

Let's take a look!

## Scoped variables

The first change has to do with view component variable scoping. We didn't properly handle variable scoping before, which often lead to leaked variables into the wrong scope. That has now been solved though, and variable scoping now follows almost exactly the same rules as normal PHP closures would.

With these changes, local variables defined within a view component cannot be leaked to the outer scope anymore:

```html
<x-post>
    <?php $title = str($post->title)->title(); ?>
    
    <h1>{{ $title }}</h1>
</x-post>

<!-- $title won't be available outside the view component. -->
```

And likewise, view components won't have access to variables from the outer scope, unless explicitly passed in:

```html
<!-- $title will need to be passed in explicitly: -->

<x-post :title="$title"></x-post> 
```

There's one exception to this rule: variables defined by the view itself are directly accessible from within view components. This can be useful when you're using view components that are tied to one specific view, but extracted to a component to avoid code repetition.

```html x-home-highlight.view.php
<div class="<!-- … -->">
    {!! $highlights[$name] !!}
</div>

<!-- in home.view.php -->
<x-home-highlight name="orm" />
```

```php
final class HomeController
{
    #[Get('/')]
    public function __invoke(HighlightRepository $highlightRepository): View
    {
        return view(
            'home.view.php',
             highlights: $highlightRepository->all(),
         );
    }
}
```

Variable scoping now works by compiling view components to PHP closures instead of what we used to do: manage variable scope ourselves. Besides fixing some bugs, it also [simplified view component rendering significantly](https://github.com/tempestphp/tempest-framework/pull/1435), which is great! 

## Installable view components

The second feature made some changes to view component discovery. We now have an installation command for components: you can use a selection of built-in components that ship with the framework like `{html}<x-markdown />`, `{html}<x-icon />`, `{html}<x-input />`, etc.; but you can also publish those components into your project. This means that, for quick prototyping, you can use the built-in components without any setup; and for real projects, you can publish the necessary components to style and change them to your liking.

```console
./tempest install view-components

 <dim>│</dim> <em>Select which view components you want to install</em>
 <dim>│</dim> / <dim>Filter...</dim>
 <dim>│</dim> → ⋅ x-csrf-token
 <dim>│</dim>   ⋅ x-markdown
 <dim>│</dim>   ⋅ x-input
 <dim>│</dim>   ⋅ x-icon
 
<comment>…</comment>
```

This installation process will hook into _any_ third party package, by the way; so it will be trivial to make a third-party frontend component library, for example, Tempest's discovery will be doing the heavy lifting for you.

This feature came with a [pretty significant refactoring](https://github.com/tempestphp/tempest-framework/pull/1439). In order to keep the code clean, we decided to remove a bunch of old and undocumented features. The most significant one is that the `ViewComponent` interface is no more, and all view components must now be handled via their view files. Here's, for example, what the `{html}<x-input />` view component's source looks like:

```html
<?php
/**
 * @var string $name
 * @var string|null $label
 * @var string|null $id
 * @var string|null $type
 * @var string|null $default
 */

use Tempest\Http\Session\Session;

use function Tempest\get;
use function Tempest\Support\str;

/** @var Session $session */
$session = get(Session::class);

$label ??= str($name)->title();
$id ??= $name;
$type ??= 'text';
$default ??= null;

$errors = $session->getErrorsFor($name);
$original = $session->getOriginalValueFor($name, $default);
?>

<div>
    <label :for="$id">{{ $label }}</label>

    <textarea :if="$type === 'textarea'" :name="$name" :id="$id">{{ $original }}</textarea>
    <input :else :type="$type" :name="$name" :id="$id" :value="$original"/>

    <div :if="$errors !== []">
        <div :foreach="$errors as $error">
            {{ $error->message() }}
        </div>
    </div>
</div>
```

While this style might require some getting used to for some people, I think it is the right decision to make: class-based view components had a lot of compiler edge cases that we had to take into account, and often lead to subtle bugs when building new components. I do plan on writing an in-depth post on how to build reusable view components with Tempest soon. Stay tuned for that!  

## Work in progress IDE support

Then, the final (very much WORK IN PROGRESS) feature: Nicolas and Márk have stepped up to build an [LSP for Tempest](https://github.com/nhedger/tempest-ls), as well as plugins for [PhpStorm](https://plugins.jetbrains.com/plugin/27971-tempest/edit) and [VSCode](https://marketplace.visualstudio.com/items?itemName=nhedger.tempest).

There is a lot of work to be done, but it's amazing to see this moving forward. If you want to get involved, definitely [join our Discord server](/discord), and you can also check out the [Tempest View specification](/docs/internals/view-spec) to learn more about the language itself.

## What's next?

From the beginning I've said that IDE support is a must for any project to succeed. It now looks like there's a real chance of that happening, which is amazing. Besides IDE support, there are a couple of big features to tackle: I want Tempest to ship with some form of "standard component library" that people can use as a scaffold, we're looking into adding HTMX support (or something alike) to build async components, and we plan on making bridges for Laravel and Symfony so that you can use Tempest View in projects outside of Tempest as well. 

If you're inspired and interested to help out with any of these features, then you're more than welcome to [join the Tempest Discord](/discord) and take it from there.