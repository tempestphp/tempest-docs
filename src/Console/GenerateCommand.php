<?php

declare(strict_types=1);

namespace App\Console;

use Tempest\Console\Actions\ExecuteConsoleCommand;
use Tempest\Console\ConsoleCommand;
use Tempest\Console\Schedule;
use Tempest\Console\Scheduler\Every;

final readonly class GenerateCommand
{
    public function __construct(
        private ExecuteConsoleCommand $executeConsoleCommand,
    ) {
    }

    #[ConsoleCommand(name: 'generate')]
    #[Schedule(interval: Every::HALF_HOUR)]
    public function __invoke(): void
    {
        ($this->executeConsoleCommand)('static:generate');
    }
}
