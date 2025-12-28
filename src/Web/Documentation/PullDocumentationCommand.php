<?php

declare(strict_types=1);

namespace App\Web\Documentation;

use RuntimeException;
use Symfony\Component\Process\Process;
use Tempest\Console\Console;
use Tempest\Console\ConsoleCommand;
use Tempest\Console\ExitCode;
use Tempest\Support\Filesystem;

use function Tempest\root_path;
use function Tempest\Support\Filesystem\delete_directory;
use function Tempest\Support\Filesystem\ensure_directory_empty;
use function Tempest\Support\Filesystem\move;
use function Tempest\Support\path;

final readonly class PullDocumentationCommand
{
    private const string CLONE_DIRECTORY = 'clone';
    private const string DOCS_DIRECTORY = 'docs';

    public function __construct(
        private Console $console,
    ) {}

    #[ConsoleCommand('docs:pull')]
    public function __invoke(?Version $version = null): ExitCode
    {
        $versions = $version ? [$version] : Version::cases();

        $this->console->header('Cloning documentation');

        foreach ($versions as $version) {
            $success = $this->console->task(
                label: "Fetching documentation for branch `{$version->getBranch()}`.",
                handler: fn () => $this->fetchVersionDocumentation($version),
            );

            if (! $success) {
                $this->console->writeln();
                $this->console->error("Failed to fetch documentation for version {$version->getBranch()}.");

                return ExitCode::ERROR;
            }
        }

        $this->console->writeln();
        $this->console->success('Documentation fetched successfully.');

        return ExitCode::SUCCESS;
    }

    private function fetchVersionDocumentation(Version $version): void
    {
        $versionedDocsDirectory = root_path('src/Web/Documentation/content/', $version->getUrlSegment());
        $temporaryDirectory = root_path('docs-clone');

        ensure_directory_empty($versionedDocsDirectory);
        ensure_directory_empty($temporaryDirectory);

        $this->run(
            command: sprintf(
                'git clone --no-checkout --depth=1 --filter=tree:0 -b %s https://github.com/tempestphp/tempest-framework %s',
                $version->getBranch(),
                self::CLONE_DIRECTORY,
            ),
            cwd: $temporaryDirectory,
        );

        $this->run(
            command: 'git sparse-checkout set --no-cone /' . self::DOCS_DIRECTORY,
            cwd: path($temporaryDirectory, self::CLONE_DIRECTORY),
        );

        $this->run(
            command: 'git checkout',
            cwd: path($temporaryDirectory, self::CLONE_DIRECTORY),
        );

        move(path($temporaryDirectory, self::CLONE_DIRECTORY, self::DOCS_DIRECTORY), $versionedDocsDirectory, overwrite: true);
        delete_directory($temporaryDirectory);
    }

    private function run(string $command, string $cwd): string
    {
        $process = Process::fromShellCommandline($command, $cwd);
        $process->run();

        if (! $process->isSuccessful()) {
            throw new RuntimeException($process->getErrorOutput());
        }

        return $process->getOutput();
    }
}
