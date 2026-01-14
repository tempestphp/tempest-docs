<?php

declare(strict_types=1);

namespace App\Web\Documentation;

use App\Markdown\SubChapterExtractor;
use Tempest\Support\Arr\ImmutableArray;
use Tempest\Support\Str;
use Tempest\View\IsView;
use Tempest\View\View;

use function Tempest\Support\Arr\map;
use function Tempest\Support\str;

final class ChapterView implements View
{
    use IsView;

    public function __construct(
        public Version $version,
        public ChapterRepository $chapterRepository,
        public Chapter $currentChapter,
    ) {
        $this->path = __DIR__ . '/show.view.php';
        $this->data = [
            'title' => $this->currentChapter->title,
            'metaImageUri' => $this->currentChapter->getMetaUri(),
        ];
    }

    public function isCurrent(Chapter $other): bool
    {
        return $this->currentChapter->category === $other->category && $this->currentChapter->slug === $other->slug;
    }

    /**
     * @return array<string, array{title: string, children: array<string, string>}>
     */
    public function getSubChapters(): array
    {
        return SubChapterExtractor::extract($this->currentChapter->body);
    }

    public function chaptersForCategory(string $category): ImmutableArray
    {
        return $this->chapterRepository->all($this->version, $category);
    }

    public function nextChapter(): ?Chapter
    {
        $current = null;

        foreach ($this->chapterRepository->all($this->version) as $chapter) {
            if ($current) {
                return $chapter;
            }

            if ($chapter->category === $this->currentChapter->category && $chapter->slug === $this->currentChapter->slug) {
                $current = $chapter;
            }
        }

        return null;
    }

    public function previousChapter(): ?Chapter
    {
        $previous = null;

        foreach ($this->chapterRepository->all($this->version) as $chapter) {
            if ($chapter->category === $this->currentChapter->category && $chapter->slug === $this->currentChapter->slug) {
                return $previous;
            }

            $previous = $chapter;
        }

        return null;
    }

    public function categories(): array
    {
        return map(
            array: glob(__DIR__ . "/content/{$this->version->getUrlSegment()}/*", flags: GLOB_ONLYDIR),
            map: static fn (string $path) => str($path)->afterLast('/')->replaceRegex('/^\d+-/', '')->toString(),
        );
    }
}
