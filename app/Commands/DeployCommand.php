<?php

namespace App\Commands;

use App\Console;
use App\ConsoleCommand;

final readonly class DeployCommand
{
    public function __construct(private Console $console) {}

    #[ConsoleCommand('deploy')]
    public function __invoke(): void
    {
        $this->console->info("Starting deploy");

        passthru("ssh forge@stitcher.io 'cd tempest.stitcher.io && sh deploy.sh'");

        $this->console->info("Deploy success");
    }
}