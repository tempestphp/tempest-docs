<?php

namespace App\Web\Blog;

use App\Web\Meta\MetaImageController;
use DateTimeImmutable;

use function Tempest\Router\uri;

/**
 * @mago-expect maintainability/too-many-properties
 */
final class BlogPost
{
    public string $slug;
    public string $title;
    public ?Author $author;
    public string $content;
    public DateTimeImmutable $createdAt;
    public ?BlogPostTag $tag = null;
    public ?string $description = null;
    public bool $published = true;
    public array $meta = [];
    public string $uri {
        get => uri([BlogController::class, 'show'], slug: $this->slug);
    }
    public string $metaImageUri {
        get => uri([MetaImageController::class, 'blog'], slug: $this->slug);
    }
}
