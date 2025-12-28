<?php

declare(strict_types=1);

namespace App\Markdown;

use function Tempest\Support\Str\strip_tags;

final class MarkdownPost
{
    public function __construct(
        public string $content,
        public ?string $title = null,
        public ?string $description = null,
    ) {}

    public function getSubChapters(): array
    {
        preg_match_all('/<h2.*>.*<a.*href="(?<uri>.*?)".*?<\/span>(?<title>.*)<\/a><\/h2>/', $this->content, $h2_matches);
        preg_match_all('/<h3.*>.*<a.*href="(?<h3uri>.*?)".*?<\/span>(?<h3title>.*)<\/a><\/h3>/', $this->content, $h3_matches);

        $sub_chapters = $this->buildH2Chapters($h2_matches);

        $this->addH3ChaptersToParents($h3_matches, $h2_matches, $sub_chapters);

        return $sub_chapters;
    }

    private function buildH2Chapters(array $h2_matches): array
    {
        $chapters = [];

        foreach ($h2_matches[0] as $key => $match) {
            $chapters[$h2_matches['uri'][$key]] = [
                'title' => strip_tags($h2_matches['title'][$key]),
                'children' => [],
            ];
        }

        return $chapters;
    }

    private function addH3ChaptersToParents(array $h3_matches, array $h2_matches, array &$sub_chapters): void
    {
        foreach ($h3_matches[0] as $key => $h3_match) {
            $h3_uri = $h3_matches['h3uri'][$key];
            $h3_title = strip_tags($h3_matches['h3title'][$key]);
            $parent_h2_uri = $this->findParentH2Uri($h3_match, $h2_matches);

            if ($parent_h2_uri !== null && isset($sub_chapters[$parent_h2_uri])) {
                $sub_chapters[$parent_h2_uri]['children'][$h3_uri] = $h3_title;
                continue;
            }

            $sub_chapters[$h3_uri] = [
                'title' => $h3_title,
                'children' => [],
            ];
        }
    }

    private function findParentH2Uri(string $h3_match, array $h2_matches): ?string
    {
        $h3_position = strpos($this->content, $h3_match);
        $parent_h2_uri = null;

        foreach ($h2_matches[0] as $key => $h2_match) {
            $h2_position = strpos($this->content, $h2_match);

            if ($h2_position >= $h3_position) {
                break;
            }

            $parent_h2_uri = $h2_matches['uri'][$key];
        }

        return $parent_h2_uri;
    }
}
