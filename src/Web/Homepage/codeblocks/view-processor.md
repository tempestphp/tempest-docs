```php src/StarCountViewProcessor.php
final class StarCountViewProcessor implements ViewProcessor
{
    public function __construct(
        private readonly GitHub $github,
    ) {}

    public function process(View $view): View
    {
        if (! $view instanceof WithStarCount) {
            return $view;
        }

        return $view->data(starCount: $this->github->getStarCount());
    }
}
```
