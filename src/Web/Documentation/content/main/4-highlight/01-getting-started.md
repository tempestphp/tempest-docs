---
title: Getting started
---

`tempest/highlight` is a package for server-side, high-performance, and flexible code highlighting. [**Give it a ⭐️ on GitHub**](https://github.com/tempestphp/highlight)!

## Quickstart

Require `tempest/highlight` with composer:

```
composer require tempest/highlight
```

And highlight code like this:

```php
$highlighter = new \Tempest\Highlight\Highlighter();

$code = $highlighter->parse($code, 'php');
```

## Supported languages

All supported languages can be found in the [GitHub repository](https://github.com/tempestphp/highlight/tree/main/src/Languages).

## Themes

There are a [bunch of themes](https://github.com/tempestphp/highlight/tree/main/src/Themes/Css) included in this package. You can load them either by importing the correct CSS file into your project's CSS file, or you can manually copy a stylesheet.

```css
@import "../../../../../vendor/tempest/highlight/src/Themes/Css/highlight-light-lite.css";
```

You can build your own CSS theme with just a couple of classes, copy over [the base stylesheet](https://github.com/tempestphp/highlight/tree/main/src/Themes/Css/highlight-light-lite.css), and make adjustments however you like. Note that `pre` tag styling isn't included in this package.

### Inline themes

If you don't want to or can't load a CSS file, you can opt to use the `InlineTheme` class. This theme takes the path to a CSS file, and will parse it into inline styles:

```php
$highlighter = new Highlighter(new InlineTheme(__DIR__ . '/../src/Themes/Css/solarized-dark.css'));
```

### Terminal themes

Terminal themes are simpler because of their limited styling options. Right now there's one terminal theme provided: `LightTerminalTheme`. More terminal themes are planned to be added in the future.

```php
use Tempest\Highlight\Highlighter;
use Tempest\Highlight\Themes\LightTerminalTheme;

$highlighter = new Highlighter(new LightTerminalTheme());

echo $highlighter->parse($code, 'php');
```

![](/img/terminal.png)

## Gutter

This package can render an optional gutter if needed.

```php
$highlighter = new Highlighter()->withGutter(startAt: 10);
```

The gutter will show additions and deletions, and can start at any given line number:

```php{10}
  public function before(TokenType $tokenType): string
  {
      $style = match ($tokenType) {
{-          TokenType::KEYWORD => TerminalStyle::FG_DARK_BLUE,
          TokenType::PROPERTY => TerminalStyle::FG_DARK_GREEN,
          TokenType::TYPE => TerminalStyle::FG_DARK_RED,-}
          TokenType::GENERIC => {+TerminalStyle::FG_DARK_CYAN+},
          TokenType::VALUE => TerminalStyle::FG_BLACK,
          TokenType::COMMENT => TerminalStyle::FG_GRAY,
          TokenType::ATTRIBUTE => TerminalStyle::RESET,
      };

      return TerminalStyle::ESC->value . $style->value;
  }
```

Finally, you can enable gutter rendering on the fly if you're using [commonmark code blocks](#commonmark-integration) by appending <code>{startAt}</code> to the language definition:

<pre>
&#96;&#96;&#96;php{10}
echo 'hi'!
&#96;&#96;&#96;
</pre>

```php{10}
echo 'hi'!
```

## Special highlighting tags

This package offers a collection of special tags that you can use within your code snippets. These tags won't be shown in the final output, but rather adjust the highlighter's default styling. All these tags work multi-line, and will still properly render its wrapped content.

Note that highlight tags are not supported in terminal themes.

### Emphasize, strong, and blur

You can add these tags within your code to emphasize or blur parts:

- <code>{_ content _}</code> adds the <code>.hl-em</code> class
- <code>{* content *}</code> adds the <code>.hl-strong</code> class
- <code>{~ content ~}</code> adds the <code>.hl-blur</code> class

<pre>
{_Emphasized text_}
{*Strong text*}
{~Blurred text~}
</pre>

This is the end result:

```txt
{_Emphasized text_}
{*Strong text*}
{~Blurred text~}
```

### Additions and deletions

You can use these two tags to mark lines as additions and deletions:

- <code>{+ content +}</code> adds the `.hl-addition` class
- <code>{- content -}</code> adds the `.hl-deletion` class

<pre>
{-public class Foo {}-}
{+public class Bar {}+}
</pre>

```php
{-public class Foo {}-}
{+public class Bar {}+}
```

As a reminder: all these tags work multi-line as well:

```php{1}
  public function before(TokenType $tokenType): string
  {
      $style = match ($tokenType) {
{-          TokenType::KEYWORD => TerminalStyle::FG_DARK_BLUE,
          TokenType::PROPERTY => TerminalStyle::FG_DARK_GREEN,
          TokenType::TYPE => TerminalStyle::FG_DARK_RED,
          TokenType::GENERIC => TerminalStyle::FG_DARK_CYAN,
          TokenType::VALUE => TerminalStyle::FG_BLACK,
          TokenType::COMMENT => TerminalStyle::FG_GRAY,
          TokenType::ATTRIBUTE => TerminalStyle::RESET,-}
      };

      return TerminalStyle::ESC->value . $style->value;
  }
```

### Custom classes

You can add any class you'd like by using the <code>{:classname: content :}</code> tag:

<pre>
&lt;style&gt;
.hl-a {
    background-color: #FFFF0077;
}

.hl-b {
    background-color: #FF00FF33;
}
&lt;/style&gt;

&#96;&#96;&#96;php
{:hl-a:public class Foo {}:}
{:hl-b:public class Bar {}:}
&#96;&#96;&#96;
</pre>

```php
{:hl-a:public class Foo {}:}
{:hl-b:public class Bar {}:}
```

### Inline languages

Within inline Markdown code tags, you can specify the language by prepending it between curly brackets:

<pre>
&#96;{php}public function before(TokenType $tokenType): string&#96;
</pre>

You'll need to set up [commonmark](#commonmark-integration) properly to get this to work.

## CommonMark integration

If you're using `league/commonmark`, you can highlight codeblocks and inline code like so:

```php
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\MarkdownConverter;
use Tempest\Highlight\CommonMark\HighlightExtension;

$environment = new Environment();

$environment
    ->addExtension(new CommonMarkCoreExtension())
    ->addExtension(new HighlightExtension());

$markdown = new MarkdownConverter($environment);
```

Keep in mind that you need to manually install `league/commonmark`:

```
composer require league/commonmark;
```
