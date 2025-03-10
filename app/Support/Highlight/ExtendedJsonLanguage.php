<?php

declare(strict_types=1);

namespace App\Support\Highlight;

use Tempest\Highlight\Languages\Json\JsonLanguage;

class ExtendedJsonLanguage extends JsonLanguage
{
    #[\Override]
    public function getName(): string
    {
        return 'json_extended';
    }

    #[\Override]
    public function getPatterns(): array
    {
        return [
            ...parent::getPatterns(),
            new JsonNullPattern(),
        ];
    }
}
