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

    public function compile(ViewComponentElement $element): string
    {
        $name = $element->getAttribute('name');
        $label = $element->getAttribute('label');
        $type = $element->getAttribute('type');
        $default = $element->getAttribute('default');

        $errorHtml = '';

        if ($errors = ($this->session->get(Session::VALIDATION_ERRORS)[$name] ?? null)) {
            $errorHtml = '<div>' . implode('', array_map(
                fn (Rule $failingRule) => "<div>{$failingRule->message()}</div>",
                $errors,
            )) . '</div>';
        }
        
        $original = $this->session->get(Session::ORIGINAL_VALUES)[$name] ?? $default;

        return <<<HTML
<div>
    <label for="{$name}">{$label}</label>
    <input type="{$type}" name="{$name}" id="{$name}" value="{$original}" />
    {$errorHtml}
</div>
HTML;
    }
    
    // …
}
```