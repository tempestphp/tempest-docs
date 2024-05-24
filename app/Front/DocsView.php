<?php

declare(strict_types=1);

namespace App\Front;

use App\Chapters\Chapter;
use App\Chapters\ChapterRepository;
use Tempest\View\IsView;
use Tempest\View\View;

final class DocsView implements View
{
    use IsView;

    public function __construct(
        /** @var Chapter[] $chapters */
        public array $chapters,
        public Chapter $currentChapter,
        public ChapterRepository $chapterRepository,
    ) {
        $this->extends('Front/base.view.php', title: $this->currentChapter->title);
        $this->path('Front/docs.view.php');
    }

    public function isCurrent(Chapter $other): bool
    {
        return
            $this->currentChapter->category === $other->category
            && $this->currentChapter->slug === $other->slug;
    }

    public function chaptersForCategory(string $category): array
    {
        return $this->chapterRepository->all($category);
    }

    public function nextChapter(): ?Chapter
    {
        $current = null;

        foreach ($this->chaptersForCategory($this->currentChapter->category) as $chapter) {
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
