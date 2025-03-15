<?php

namespace Tempest\Web\Blog;

use DateTimeImmutable;

use function Tempest\uri;

final class BlogPost
{
    public string $slug;
    public string $title;
    public string $author;
    public string $content;
    public DateTimeImmutable $createdAt;
    public ?string $tag = null;
    public ?string $description = null;
    public bool $published = true;
    public string $uri {
        get {
            return uri([BlogController::class, 'show'], slug: $this->slug);
        }
    }
}
