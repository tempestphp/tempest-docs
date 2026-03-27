<?php

declare(strict_types=1);

namespace App\Advocacy;

use App\Advocacy\Discord\Discord;
use App\Advocacy\Reddit\Reddit;
use Tempest\Console\ConsoleCommand;
use Tempest\Console\HasConsole;
use Tempest\Console\Schedule;
use Tempest\Console\Scheduler\Every;

final readonly class AdvocacyPullCommand
{
    use HasConsole;

    public function __construct(
        private Reddit $reddit,
        private Discord $discord,
    ) {}

    #[ConsoleCommand('advocacy:pull'), Schedule(Every::HALF_HOUR)]
    public function __invoke(): void
    {
        $messages = $this->reddit->fetch();

        foreach ($messages as $message) {
            $this->discord->notify($message);
        }
    }
}
