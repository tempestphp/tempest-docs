---
title: "Opt-in features"
---

`tempest/highlight` has a couple of opt-in features, if you need them.

## Markdown support

```
composer require league/commonmark;
```

```php
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\MarkdownConverter;
use Tempest\Highlight\CommonMark\HighlightExtension;

$environment = new Environment();

$environment
    ->addExtension(new CommonMarkCoreExtension())
    ->addExtension(new HighlightExtension(/* You can manually pass in configured highlighter as well */));

$markdown = new MarkdownConverter($environment);
```

## Ellison: word complexity

Ellison is a simple library that helps identify complex sentences and poor word choices. It uses similar heuristics to Hemingway, but it doesn't include any calls to third-party APIs or LLMs. Just a bit of PHP:

```ellison
The app highlights lengthy, complex sentences and common errors; if you see a yellow sentence, shorten or split it. If you see a red highlight, your sentence is so dense and complicated that your readers will get lost trying to follow its meandering, splitting logic — try editing this sentence to remove the red.

You can utilize a shorter word in place of a purple one. Click on highlights to fix them.

Adverbs and weakening phrases are helpfully shown in blue. Get rid of them and pick words with force, perhaps.

Phrases in green have been marked to show passive voice. 
```

You can enable Ellison support by installing [`assertchris/ellison`](https://github.com/assertchris/ellison-php):

```
composer require assertchris/ellison
```

You'll have to add some additional CSS classes to your stylesheet as well:

```css
.hl-moderate-sentence {
    background-color: #fef9c3;
}

.hl-complex-sentence {
    background-color: #fee2e2;
}

.hl-adverb-phrase {
    background-color: #e0f2fe;
}

.hl-passive-phrase {
    background-color: #dcfce7;
}

.hl-complex-phrase {
    background-color: #f3e8ff;
}

.hl-qualified-phrase {
    background-color: #f1f5f9;
}

pre[data-lang="ellison"] {
    text-wrap: wrap;
}
```

The `ellison` language is now available:

<pre>
```ellison
Hello world!
```
</pre>

You can play around with it [here](/ellison).