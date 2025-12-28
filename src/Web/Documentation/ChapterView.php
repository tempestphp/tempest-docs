<?php

declare(strict_types=1);

namespace App\Web\Documentation;

use Tempest\Support\Arr\ImmutableArray;
use Tempest\Support\Str;
use Tempest\View\IsView;
use Tempest\View\View;

use function Tempest\Support\Arr\map_iterable;
use function Tempest\Support\str;
use function Tempest\Support\Str\strip_tags;

/**
 * @mago-expect lint:kan-defect
 */
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

    public function getSubChapters(): array
    {
        // TODO(@innocenzi): clean up
        preg_match_all('/<h2.*>.*<a.*href="(?<uri>.*?)".*?<\/span>(?<title>.*)<\/a><\/h2>/', $this->currentChapter->body, $h2Matches);
        preg_match_all('/<h3.*>.*<a.*href="(?<h3uri>.*?)".*?<\/span>(?<h3title>.*)<\/a><\/h3>/', $this->currentChapter->body, $h3Matches);

        $subChapters = [];

        foreach ($h2Matches[0] as $key => $match) {
            $h2Uri = $h2Matches['uri'][$key];
            $h2Title = strip_tags($h2Matches['title'][$key]);
            $subChapters[$h2Uri] = [
                'title' => $h2Title,
                'children' => [],
            ];
        }

        foreach ($h3Matches[0] as $key => $match) {
            $h3Uri = $h3Matches['h3uri'][$key];
            $h3Title = strip_tags($h3Matches['h3title'][$key]);
            $parentH2Uri = null;
            $h3Position = strpos($this->currentChapter->body, $match);

            foreach ($h2Matches[0] as $h2Key => $h2Match) {
                $h2Position = strpos($this->currentChapter->body, $h2Match);
                if ($h2Position < $h3Position) {
                    $parentH2Uri = $h2Matches['uri'][$h2Key];
                } else { // @mago-expect lint:no-else-clause
                    break;
                }
            }

            if ($parentH2Uri !== null && isset($subChapters[$parentH2Uri])) {
                $subChapters[$parentH2Uri]['children'][$h3Uri] = $h3Title;
            } else { // @mago-expect lint:no-else-clause
                $subChapters[$h3Uri] = [
                    'title' => $h3Title,
                    'children' => [],
                ];
            }
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
        return map_iterable(
            array: glob(__DIR__ . "/content/{$this->version->getUrlSegment()}/*", flags: GLOB_ONLYDIR),
            map: static fn (string $path) => str($path)->afterLast('/')->replaceRegex('/^\d+-/', '')->toString(),
        );
    }
}
