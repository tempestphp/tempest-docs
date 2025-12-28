<?php

declare(strict_types=1);

namespace App\Web\Meta;

use function Tempest\Router\uri;

enum MetaType: string
{
    case HOME = 'home';
    case BLOG = 'blog';

    public function uri(): string
    {
        return uri([MetaImageController::class, 'default'], type: $this->value);
    }

    public function getViewPath(): string
    {
        return match ($this) {
            self::BLOG => __DIR__ . '/views/blog-index.view.php',
            default => __DIR__ . '/views/default.view.php',
        };
    }
}
