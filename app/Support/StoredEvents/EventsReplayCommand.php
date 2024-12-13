<?php

namespace App\Support\StoredEvents;

use Tempest\Console\Console;
use Tempest\Console\ConsoleCommand;
use Tempest\Console\HasConsole;
use Tempest\Console\Middleware\ForceMiddleware;
use Tempest\Container\Container;
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
    public function __invoke(): void
    {
        $storedEvents = StoredEvent::query()
            ->orderBy('createdAt ASC')
            ->all();

        $replay = $this->ask(
            question: 'Which projects should be replayed?',
            options: $this->storedEventConfig->projectors,
            multiple: true,
        );

        $replayCount = count($replay);

        if (! $replayCount) {
            $this->error('No projectors selected');

            return;
        }

        $confirm = $this->confirm(sprintf(
            'We\'re going to replay %d events on %d %s, this will take a while. Continue?',
            count($storedEvents),
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

            /** @var \App\Support\StoredEvents\Projector $projector */
            $projector = $this->container->get($projectorClass);

            $projector->clear();

            $this->writeln(sprintf('Replaying <em>%s</em>', $projectorClass));

            $this->progressBar($storedEvents, function (StoredEvent $storedEvent) use ($projector) {
                $projector->replay($storedEvent->getEvent());
            });
        }

        $this->success('Done');
    }
}