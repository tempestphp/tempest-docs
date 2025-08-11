<?php

declare(strict_types=1);

namespace App\Web\Documentation;

use App\Web\Meta\MetaImageController;

use function Tempest\uri;

final class Chapter
{
    public function __construct(
        public Version $version,
        public string $category,
        public string $slug,
        public string $body,
        public string $title,
        public string $path,
        public bool $hidden = false,
        public ?string $description = null,
    ) {}

    public function getUri(): string
    {
        return uri(
            DocumentationController::class,
            version: $this->version,
            category: $this->category,
            slug: $this->slug,
        );
    }

    public function getCanonicalUri(): string
    {
        return uri(
            DocumentationController::class,
            version: $this->version->default(),
            category: $this->category,
            slug: $this->slug,
        );
    }

    public function getMetaUri(): string
    {
        return uri([MetaImageController::class, 'documentation'], version: $this->version, category: $this->category, slug: $this->slug);
    }

    public function getEditPageUri(): string
    {
        return "https://github.com/tempestphp/tempest-docs/edit/main/{$this->path}";
    }
}
