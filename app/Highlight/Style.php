<?php

namespace App\Highlight;

final readonly class Style
{
    public function __construct(
        private ?string $color = null,
        private ?string $background = null,
        private bool $italic = false,
        private bool $bold = false,
        private bool $underline = false,
        private bool $blur = false,
    ) {}

    public function apply(string $content): string
    {
        $styles = [];

        if ($this->color) {
            $styles[] = "color: {$this->color};";
        }

        if ($this->background) {
            $styles[] = "background-color: {$this->color};";
        }

        if ($this->italic) {
            $styles[] = 'font-style: italic;';
        }

        if ($this->bold) {
            $styles[] = 'font-weight: bold;';
        }

        if ($this->underline) {
            $styles[] = 'text-decoration: underline;';
        }

        if ($this->blur) {
            $styles[] = 'filter: blur(2px);';
        }

        $style = implode(' ', $styles);

        return "<span style=\"{$style}\">{$content}</span>";
    }
}