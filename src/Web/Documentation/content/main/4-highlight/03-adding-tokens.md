---
title: Adding tokens
---

<style>
.hl-null {
    color: red;
}
</style>

Some people or projects might want more fine-grained control over how specific words are coloured. A common example are `null`, `true`, and `false` in json files. By default, `tempest/highlight` will treat those value as normal text, and won't apply any special highlighting to them:

```json
{
  "null-property": null,
  "value-property": "value"
}
```

However, it's super trivial to add your own, extended styling on these kinds of tokens. Start by adding a custom language, let's call it `ExtendedJsonLanguage`:

```php
use Tempest\Highlight\Languages\Json\JsonLanguage;

class ExtendedJsonLanguage extends JsonLanguage
{
    public function getPatterns(): array
    {
        return [
            ...parent::getPatterns(),
        ];
    }
}
```

Next, let's add a pattern that matches `null`:

```php
use Tempest\Highlight\IsPattern;
use Tempest\Highlight\Pattern;
use Tempest\Highlight\Tokens\DynamicTokenType;
use Tempest\Highlight\Tokens\TokenType;

final readonly class JsonNullPattern implements Pattern
{
    use IsPattern;

    public function getPattern(): string
    {
        return '\: (?<match>null)';
    }

    public function getTokenType(): TokenType
    {
        return new DynamicTokenType('hl-null');
    }
}
```

Note how we return a `{php}DynamicTokenType` from the `{php}getTokenType()` method. The value passed into this object will be used as the classname for this token.

Next, let's add this pattern in our newly created `{php}ExtendedJsonLanguage`:

```php
class ExtendedJsonLanguage extends JsonLanguage
{
    public function getPatterns(): array
    {
        return [
            ...parent::getPatterns(),
            {*new JsonNullPattern(),*}
        ];
    }
}
```

Finally, register `{php}ExtendedJsonLanguage` into the highlighter:

```php
$highlighter->addLanguage(new ExtendedJsonLanguage());
```

Note that, because we extended `{php}JsonLanguage`, this language will target all code blocks tagged as `json`. You could provide a different name, if you want to make a distinction between the default implementation and yours (this is what's happening on this page):

```php
class ExtendedJsonLanguage extends JsonLanguage
{
    public function getName(): string
    {
        return 'json_extended';
    }

    // …
}
```

There we have it!

```json_extended
{
    "null-property": null,
    "value-property": "value"
}
```

You can add as many patterns as you like, you can even make your own `{php}TokenType` implementation if you don't want to rely on `{php}DynamicTokenType`:

```php

enum ExtendedTokenType: string implements TokenType
{
    case VALUE_NULL = 'null';
    case VALUE_TRUE = 'true';
    case VALUE_FALSE = 'false';

    public function getValue(): string
    {
        return $this->value;
    }

    public function canContain(TokenType $other): bool
    {
        return false;
    }
}
```
