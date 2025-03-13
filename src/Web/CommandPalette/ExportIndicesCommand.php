<?php

namespace Tempest\Web\CommandPalette;

use App\Front\Blog\BlogPost;
use App\Front\Docs\Chapter;
use App\Front\Docs\ChapterRepository;
use Tempest\Console\Console;
use Tempest\Console\ConsoleCommand;
use Tempest\Console\ExitCode;
use Tempest\Core\Kernel;
use Tempest\Web\Blog\BlogIndexer;
use Tempest\Web\Documentation\DocumentationIndexer;

use function Tempest\Support\Str\before_first;

final class ExportIndicesCommand
{
    public function __construct(
        private readonly Console $console,
        private readonly DocumentationIndexer $documentationIndexer,
        private readonly CommandIndexer $commandIndexer,
        private readonly BlogIndexer $blogIndexer,
    ) {
    }

    #[ConsoleCommand('command-palette:export-indices', 'Exports available commands to a JSON index file that can be consumed by the front-end.')]
    public function __invoke(): ExitCode
    {
        file_put_contents(
            __DIR__ . '/index.json',
            json_encode([
                ...($this->documentationIndexer)(before_first(Kernel::VERSION, search: '.') . '.x'),
                ...($this->blogIndexer)(),
                ...($this->commandIndexer)(),
            ]),
        );

        $this->console->success('Exported index.');

        return ExitCode::SUCCESS;
    }
}
