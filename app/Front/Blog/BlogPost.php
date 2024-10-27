<?php

namespace App\Front\Blog;

use DateTimeImmutable;
use function Tempest\uri;

final class BlogPost
{
    public function __construct(
        public string $slug,
        public string $title,
        public string $author,
        public string $content,
        public DateTimeImmutable $createdAt,
        public ?string $description = null,
    ) {}

    public function getUri(): string
    {
        return uri([BlogController::class, 'show'], slug: $this->slug);
    }
}