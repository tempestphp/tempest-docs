<?php

declare(strict_types=1);

namespace App\Front\Docs;

use function Tempest\uri;

final readonly class DocsChapter
{
    public function __construct(
        public string $category,
        public string $slug,
        public string $body,
        public string $title,
    ) {
    }

    public function getUri(): string
    {
        return uri(DocsController::class, category: $this->category, slug: $this->slug);
    }
}
