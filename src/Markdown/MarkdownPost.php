<?php

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
        preg_match_all('/<h2.*>.*<a.*href="(?<uri>.*?)".*?<\/span>(?<title>.*)<\/a><\/h2>/', $this->content, $h2Matches);
        preg_match_all('/<h3.*>.*<a.*href="(?<h3uri>.*?)".*?<\/span>(?<h3title>.*)<\/a><\/h3>/', $this->content, $h3Matches);

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
            $h3Position = strpos($this->content, $match);

            foreach ($h2Matches[0] as $h2Key => $h2Match) {
                $h2Position = strpos($this->content, $h2Match);
                if ($h2Position < $h3Position) {
                    $parentH2Uri = $h2Matches['uri'][$h2Key];
                } else {
                    break;
                }
            }

            if ($parentH2Uri !== null && isset($subChapters[$parentH2Uri])) {
                $subChapters[$parentH2Uri]['children'][$h3Uri] = $h3Title;
            } else {
                $subChapters[$h3Uri] = [
                    'title' => $h3Title,
                    'children' => [],
                ];
            }
        }

        return $subChapters;
    }
}
