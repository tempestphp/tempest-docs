<?php

declare(strict_types=1);

namespace App\Web\Documentation;

use Tempest\Support\Arr\ImmutableArray;
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
    }

    public function isCurrent(Chapter $other): bool
    {
        return $this->currentChapter->category === $other->category && $this->currentChapter->slug === $other->slug;
    }

    public function getSubChapters(): array
    {
        preg_match_all('/<h2.*>.*<a.*href="(?<uri>.*?)".*<\/span>(?<title>.*)<\/a><\/h2>/', $this->currentChapter->body, $matches);

        $subChapters = [];

        foreach ($matches[0] as $key => $match) {
            $subChapters[$matches['uri'][$key]] = $matches['title'][$key];
        }

        return $subChapters;
    }

    public function chaptersForCategory(string $category): ImmutableArray
    {
        return $this->chapterRepository->all($this->version, $category);
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

    public function previousChapter(): ?Chapter
    {
        $previous = null;

        foreach ($this->chaptersForCategory($this->currentChapter->category) as $chapter) {
            if ($this->isCurrent($chapter)) {
                return $previous;
            }

            $previous = $chapter;
        }

        return null;
    }

    public function categories(): array
    {
        return map(
            array: glob(__DIR__ . "/content/{$this->version->value}/*", flags: GLOB_ONLYDIR),
            map: fn (string $path) => str($path)->afterLast('/')->replaceRegex('/^\d+-/', '')->toString(),
        );
    }
}
