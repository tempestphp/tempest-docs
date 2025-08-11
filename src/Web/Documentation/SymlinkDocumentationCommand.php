<?php

namespace App\Web\Documentation;

use RuntimeException;
use Symfony\Component\Process\Process;
use Tempest\Console\Console;
use Tempest\Console\ConsoleCommand;
use Tempest\Console\ExitCode;
use Tempest\Support\Filesystem;

use function Tempest\root_path;

final readonly class SymlinkDocumentationCommand
{
    public function __construct(
        private Console $console,
    ) {}

    #[ConsoleCommand('docs:symlink')]
    public function __invoke(string $path = '../tempest-framework', ?Version $version = null): ExitCode
    {
        $version ??= Version::default();
        $from = root_path($path, '/docs');
        $to = root_path('src/Web/Documentation/content/', $version->getUrlSegment());

        $this->console->header('Symlinking documentation');

        if (! Filesystem\exists($from)) {
            $this->console->error("The source path does not exist (tried {$from}).");

            return ExitCode::ERROR;
        }

        if (Filesystem\is_symbolic_link($to) || Filesystem\is_file($to)) {
            $this->console->task(
                label: "Removing existing symlink at {$to}.",
                handler: fn () => $this->run('rm -rf ' . escapeshellarg($to)),
            );
        }

        $this->console->task(
            label: "Creating symlink from {$from} to {$to}.",
            handler: fn () => $this->run('ln -s ' . escapeshellarg($from) . ' ' . escapeshellarg($to)),
        );

        $this->console->writeln();
        $this->console->success('Symlink created successfully.');

        return ExitCode::SUCCESS;
    }

    private function run(string $command): string
    {
        $process = Process::fromShellCommandline($command);
        $process->run();

        if (! $process->isSuccessful()) {
            throw new RuntimeException($process->getErrorOutput());
        }

        return $process->getOutput();
    }
}
