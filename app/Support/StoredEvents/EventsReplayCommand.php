<?php

namespace App\Support\StoredEvents;

use Tempest\Console\Console;
use Tempest\Console\ConsoleCommand;
use Tempest\Console\HasConsole;
use Tempest\Console\Middleware\ForceMiddleware;
use Tempest\Container\Container;
use Tempest\Database\Query;
use function Tempest\Support\str;

final readonly class EventsReplayCommand
{
    use HasConsole;

    public function __construct(
        private StoredEventConfig $storedEventConfig,
        private Console $console,
        private Container $container,
    ) {}

    #[ConsoleCommand(middleware: [ForceMiddleware::class])]
    public function __invoke(?string $replay = null): void
    {
        if ($replay) {
            $replay = [$replay];
        } else {
            $replay = $this->ask(
                question: 'Which projects should be replayed?',
                options: $this->storedEventConfig->projectors,
                multiple: true,
            );
        }

        $replayCount = count($replay);

        if (! $replayCount) {
            $this->error('No projectors selected');

            return;
        }

        $eventCount = new Query(sprintf('SELECT COUNT(*) as `count` FROM `%s`', StoredEvent::table()->tableName))->fetchFirst()['count'];

        $confirm = $this->confirm(sprintf(
            'We\'re going to replay %d events on %d %s, this will take a while. Continue?',
            $eventCount,
            $replayCount,
            str('projector')->pluralize($replayCount),
        ));

        if (! $confirm) {
            $this->error('Cancelled');

            return;
        }

        foreach ($this->storedEventConfig->projectors as $projectorClass) {
            if (! in_array($projectorClass, $replay)) {
                continue;
            }

            $this->info(sprintf('Replaying <style="underline">%s</style>', $projectorClass));

            /** @var \App\Support\StoredEvents\Projector $projector */
            $projector = $this->container->get($projectorClass);

            $projector->clear();

            StoredEvent::query()
                ->orderBy('createdAt ASC')
                ->chunk(function (array $storedEvents) use ($projector) {
                    $this->write('.');

                    foreach ($storedEvents as $storedEvent) {
                        $projector->replay($storedEvent->getEvent());
                    }
                }, 500);

            $this->writeln();
        }

        $this->success('Done');
    }
}