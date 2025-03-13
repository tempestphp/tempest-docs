<?php

declare(strict_types=1);

namespace Tempest\Web\Documentation;

use function Tempest\uri;

final class Chapter
{
    public function __construct(
        public string $version,
        public string $category,
        public string $slug,
        public string $body,
        public string $title,
    ) {
    }

    public function getUri(): string
    {
        return uri(ChapterController::class, version: $this->version, category: $this->category, slug: $this->slug);
    }
}
