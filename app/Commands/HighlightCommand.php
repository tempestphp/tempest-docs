<?php

namespace App\Commands;

use App\Chapters\ChapterRepository;
use Tempest\Console\Console;
use Tempest\Console\ConsoleCommand;

class HighlightCommand
{
    public function __construct(
        private Console $console,
        private ChapterRepository $chapterRepository,
    ) {}

    #[ConsoleCommand(name: 'hl')]
    public function __invoke(): void
    {
        $chapter = $this->chapterRepository->find('00-test');

        $this->console->write($chapter->body);
    }
}