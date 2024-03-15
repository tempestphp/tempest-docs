<?php

namespace App\Chapters;

use App\Front\DocsController;
use function Tempest\uri;

final readonly class Chapter
{
    public function __construct(
        public string $slug,
        public string $body,
        public string $title,
    ) {
    }

    public function getUri(): string
    {
        return uri([DocsController::class, 'show'], slug: $this->slug);
    }
}
