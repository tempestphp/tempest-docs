<?php

declare(strict_types=1);

namespace App\Console;

use Tempest\Console\ConsoleCommand;
use Tempest\Console\HasConsole;

final readonly class DeployCommand
{
    use HasConsole;

    #[ConsoleCommand('deploy')]
    public function __invoke(): void
    {
        $this->info('Starting deploy');

        $this->task('Pulling changes', passthru("ssh forge@stitcher.io 'cd tempest.stitcher.io && git pull'"));

        $this->task('Running deploy script', passthru("ssh forge@stitcher.io 'cd tempest.stitcher.io && sh deploy.sh'"));

        $this->success('Deploy success');
    }
}
