---
title: Chasing Bugs down Rabbit Holes
description: I had to debug the most interesting bug in Tempest to date
author: Brent
---

It all started with me noticing the favicon of this website (the blog you're reading right now) was missing. My first thought was that the favicon file somehow got removed from the server, but a quick network inspection told me that wasn't the case: it showed no favicon request at all.

"Weird," I thought, I didn't remember making any changes to the layout code in ages. However, this website uses `tempest/view`, a new PHP templating engine, and I had been making lots of tweaks and fixes to it these past two weeks. It's still alpha, and naturally things break now and then. That's exactly the reason why I built this website with `tempest/view` from the very start: what better way to find bugs than to dogfood your own code?

So, next option: it's probably a bug in `tempest/view`. But where exactly? I inspected the source of the page — the compiled output of `tempest/view` — and discovered that the favicon was actually there:

```html
<link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png"/>
```

So why wasn't it rendering? A closer inspection of the page source made it clear: _somehow_ the `{html}<link>` tag ended up in the `{html}<body>` of the HTML document:

```html
<html>
    <head>
        <title>Chasing Bugs down Rabbit Holes</title>

        <!-- … -->
    </head>
    <body>
        <!-- This shouldn't be here… -->
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png"/>
    </body>
</html>
```

Well, that's not good. Why does a tag that clearly belongs in `{html}<head>`, ends up in `{html}<body>`? I doubt I misplaced it. I opened the source and — as expected — it's in the correct place. I simplified the code a bit, but it's good enough to understand what's going on:

```html
<x-component name="x-base">
    <html lang="en">

        <head>
            <title :if="$title ?? null">{{ $title }} | Tempest</title>
            <title :else>Tempest</title>

            <link href="/main.css" rel="stylesheet"/>

            <x-slot name="styles"/>

            <!-- Clearly in head: -->
            <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png"/>

            <x-slot name="head" />
        </head>

        <body>
            <x-slot/>

            <x-slot name="scripts" />
        </body>

    </html>
</x-component>
```

So what to do to debug a weird bug as this one? Create as small as possible a reproducible scenario in which the error occurs, and take it from there. So I commented out everything but the link tag and refreshed. Now it did end up in `{html}<head>`!

Weird.

So let's comment out a little less. Back and forth and back and forth; a little bit of commenting later and I discovered what set it off: whenever I removed that `{html}<x-slot name="styles"/>` tag before the `{html}<link>` tag, it worked. If I moved the `{html}<x-slot>` tag beneath the `{html}<link>` tag, it worked as well!

```html
<x-component name="x-base">
    <html lang="en">
        <head>
            <!-- … -->

            <!-- Removing this slot solves the issue: -->
            <!-- <x-slot name="styles"/> -->

            <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png"/>

            <!-- Moving it downstairs also solved it: -->
            <x-slot name="styles"/>
        </head>
    </html>
</x-component>
```

This is the worst case scenario: apparently there's something wrong with slot rendering in `tempest/view`! Now, if you don't know, slots are a way to inject content into parent templates from within a child template. The `styles` slot, for example, can be used by any template that relies on the `{html}<x-base>` layout to inject styles into the right place:

```html
<!-- home.view.php -->

<x-base>
    Just some normal content ending up in body

    <x-slot name="styles">
        <!-- Additional styles injected into the parent's slot: -->

        <style>
            body {
                background: red;
            }
        </style>
    </x-slot>
</x-base>
```

Slots are one of the most complex parts of `tempest/view`, so naturally I dreaded heading back into that code. Especially since I wrote it about two months ago — an eternity it felt, no way I remembered how it worked. Luckily, I have gotten pretty good at source diving over the years, so after half an hour, I was up to speed again with my own code.

Important to know is that `tempest/view` relies on PHP's DOM parser to render templates. In contrast to most other PHP template engines who parse their templates with regex, `tempest/view` will parse everything into a DOM, and perform operations on that DOM. This approach gives a lot more flexibility, for example when it comes to attribute expressions like `{html}<div :foreach="$books as $book">`, but parsing a DOM is also more complex than regex find/replace operations.

My assumption was that either something went wrong in the DOM parser, or that `tempest/view` converting the DOM back into an HTML file messed something up. Since DOM parsing is done by PHP 8.4's built-in parser, I assumed I was at fault instead of PHP. However, no matter how far I searched, I could not find any place that would result in a tag being moved from `{html}<head>` to `{html}<body>`! In a final attempt, I decided to debug the DOM, regardless of my assumption that it couldn't be wrong. I took a compiled template from Tempest, passed it to PHP's built-in DOM parser, and observed what happened.

I made this component in Tempest:

```html
<x-component name="x-base">
    <html lang="en">
    <head>
        <x-slot name="styles" />
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png"/>
    </head>
    </html>
</x-component>
```

I then used that component in a template and dumped the compiled output:

```php
$compiled = $this->compiler->compile(<<<HTML
<x-base>
    <slot name="styles">Styles</slot>
</x-base>
HTML);

ld($compiled);
```

Finally, I manually passed that compiled output to PHP's DOM parser:

```php
$compiled = <<<HTML
<html lang="en">
<head>
    Styles
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png"/>
</head>
</html>
HTML;

$dom = HTMLDocument::createFromString($compiled, LIBXML_NOERROR | HTML_NO_DEFAULT_NS)
```

Now I made a mistake here which in the end turned out very lucky, because otherwise I would probably have spent a lot more time debugging: I injected the text `Styles` into the styles slot, instead of a valid style tag. This was just me being lazy, but it turned out to be the key to solving this problem.

I noticed that `Styles` caused the parsing to break somehow, because the parsed DOM looked like this:

```html
<html lang="en">
<head>
</head>
<body>
    Styles
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png"/>
</body>
</html>
```

This is when I realized: the DOM parser _probably_ only allows HTML tags in the `{html}<head>`, instead of any text! So I changed my `Styles` to `{html}<style></style>`, and suddenly it worked!

```html
<html lang="en">
<head>
</head>
<body>
    <style></style>
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png"/>
</body>
</html>
```

Ok, that makes sense: the parser kind of breaks when it encounters invalid text in `{html}<head>` (or so I thought); fair enough. In case of this website, there are probably some invalid styles injected into that slot, causing it to break.

"But hang on," I thought, "the page where it breaks doesn't have injected styles!" This is where the final piece of the puzzle came to be: the DOM parser doesn't just prevent text from being in `{html}<head>`, it prevents _any_ tag that doesn't belong in `{html}<head>` to be there!

_Whenever a slot is empty, `tempest/view` will keep the slot element untouched. It's a custom HTML element without any styling, it's basically nothing and doesn't matter_ — was my thinking two months ago.

Except when it ends up in the `{html}<head>` tag of an HTML document! See, this is invalid HTML:

```html
<html lang="en">
    <head>
        <x-slot name="styles" />
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png"/>
    </head>
    <body>
    </body>
</html>
```

That's because `{html}<x-slot>` isn't a tag allowed within `{html}<head>`! And what does the DOM parser do when it encounters an element that doesn't belong in `{html}<head>`? It will simply close the `{html}<head>` and start the `{html}<body>`. Apparently that's part of [the spec](https://www.w3.org/TR/2011/WD-html5-20110113/tokenization.html#parsing-main-inhead) (thanks to Enzo for pointing that out)!

Why is it part of the spec? As far as I understand, HTML5 allows you to write something like this (note that there's no closing `{html}</head>` tag):

```html
<hmtl>
    <head>
        <title>Chasing Bugs down Rabbit Holes</title>
    <body>
        <h1>This is the body</h1>
    </body>
</hmtl>
```

Because `{html}<head>` only allows a specific set of tags that can't exist in `{html}<body>`, the DOM parser can infer when the `{html}<head>` is done, even if it doesn't have a closing tag. That's why custom elements like `{html}<x-slot name="styles" />` can't live in `{html}<head>`: as soon as the DOM parser encounters it, it'll assume it has entered the body, despite there being an explicit `{html}</head>` further down below.

This is one of these things where I think "this behaviour is bound to cause more problems than it solves." But it is part of the spec, and people much smarter than me have thought this through, so… ¯\\\_(ツ)_/¯

In the end… the fix was simple: don't render slots when they don't have any content. Or comment them out so that they are still visible in the source code. That's what I settled on eventually:

```php
if ($slot === null) {
    // A slot doesn't have any content, so we'll comment it out.
    // This is to prevent DOM parsing errors (slots in <head> tags is one example, see #937)
    return '<!--' . $matches[0] . '-->';
}
```

A pretty simple fix after a pretty intense debugging session. Had I known the HTML5 spec by heart, I would probably have caught this earlier. But hey, we live and learn, and the feeling when I finally fixed it was pretty nice as well!

Until next time!
