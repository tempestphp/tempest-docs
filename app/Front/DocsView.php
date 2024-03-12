<?php

namespace App\Front;

use App\Chapters\Chapter;
use Tempest\View\IsView;
use Tempest\View\View;

final class DocsView implements View
{
    use IsView;

    public function __construct(
        /** @var Chapter[] $chapters */
        public array $chapters,
        public ?Chapter $currentChapter = null,
    ) {
        $this->extends('Front/base.view.php', title: $this->currentChapter?->title ?? null);
        $this->path('Front/docs.view.php');
    }

    public function isCurrent(Chapter $other): bool
    {
        return $this->currentChapter?->slug === $other->slug;
    }

    public function nextChapter(): ?Chapter
    {
        $current = null;

        foreach ($this->chapters as $chapter) {
            if ($current) {
                return $chapter;
            }

            if ($this->isCurrent($chapter)) {
                $current = $chapter;
            }
        }

        return null;
    }
}