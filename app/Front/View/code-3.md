```html
<?php
/** @var \App\Front\BlogPostView $this */

use App\Front\Meta\MetaImageController;
use function Tempest\uri;

$title = 'Click me!';
?>

<a :href="uri(MetaImageController::class, $this->type)">
    {{ $title }}
</a>
```