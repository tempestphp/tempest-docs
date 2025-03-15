<?php

namespace App\Web\CommandPalette;

use App\Web\Blog\BlogIndexer;
use App\Web\Documentation\DocumentationIndexer;
use App\Web\Documentation\Version;
use Tempest\Console\Console;
use Tempest\Console\ConsoleCommand;
use Tempest\Console\ExitCode;

final readonly class IndexCommandPaletteCommand
{
    public function __construct(
        private Console $console,
        private DocumentationIndexer $documentationIndexer,
        private CommandIndexer $commandIndexer,
        private BlogIndexer $blogIndexer,
    ) {
    }

    #[ConsoleCommand('command-palette:index', 'Exports available commands to a JSON index file that can be consumed by the front-end.')]
    public function __invoke(): ExitCode
    {
        file_put_contents(
            __DIR__ . '/index.json',
            json_encode([
                ...($this->documentationIndexer)(Version::default()),
                ...($this->blogIndexer)(),
                ...($this->commandIndexer)(),
            ]),
        );

        $this->console->success('Exported index.');

        return ExitCode::SUCCESS;
    }
}
