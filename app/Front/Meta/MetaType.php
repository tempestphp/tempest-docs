<?php

namespace App\Front\Meta;

use function Tempest\uri;

enum MetaType: string
{
    case CONSOLE = 'console';
    case VIEW = 'view';
    case FRAMEWORK = 'framework';
    case DOCS = 'docs';

    public function uri(): string
    {
        return uri([MetaImageController::class, 'default'], type: $this->value);
    }

    public function getViewPath(): string
    {
        return match ($this) {
            self::CONSOLE => __DIR__ . '/meta-console.view.php',
            self::VIEW => __DIR__ . '/meta-view.view.php',
            self::FRAMEWORK, self::DOCS => __DIR__ . '/meta-framework.view.php',
        };
    }
}
