<?php

declare(strict_types=1);

namespace App\Front\Docs;

use App\Chapters\Chapter;
use App\Chapters\ChapterRepository;
use Tempest\View\IsView;
use Tempest\View\View;

final class DocsView implements View
{
    use IsView;

    public function __construct(
        public ChapterRepository $chapterRepository,
        public Chapter $currentChapter,
    ) {
        $this->path = __DIR__ . '/docs.view.php';
    }

    public function isCurrent(Chapter $other): bool
    {
        return
            $this->currentChapter->category === $other->category
            && $this->currentChapter->slug === $other->slug;
    }

    public function getSubChapters(): array
    {
        preg_match_all('/<h2 id="(?<uri>.*)">(?<title>.*)<a href/', $this->currentChapter->body, $matches);

        $subChapters = [];

        foreach ($matches[0] as $key => $match) {
            $subChapters['#' . $matches['uri'][$key]] = $matches['title'][$key];
        }

        return $subChapters;
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

    public function categories(): array
    {
        return ['intro', 'framework', 'console', 'highlight', 'internals'];
    }
}
