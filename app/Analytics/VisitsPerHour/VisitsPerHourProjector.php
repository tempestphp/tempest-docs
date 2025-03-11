<?php

namespace App\Analytics\VisitsPerHour;

use App\Analytics\PageVisited;
use App\Support\StoredEvents\Projector;
use Tempest\Database\Query;
use Tempest\EventBus\EventHandler;

final readonly class VisitsPerHourProjector implements Projector
{
    #[\Override]
    public function replay(object $event): void
    {
        if ($event instanceof PageVisited) {
            $this->onPageVisited($event);
        }
    }

    #[\Override]
    public function clear(): void
    {
        $query = new Query(sprintf(
            'DELETE FROM %s',
            VisitsPerHour::table(),
        ));

        $query->execute();
    }

    #[EventHandler]
    public function onPageVisited(PageVisited $pageVisited): void
    {
        $visitedAt = $pageVisited->visitedAt->setTime(
            hour: $pageVisited->visitedAt->format('H'),
            minute: 0,
        );

        $day = VisitsPerHour::query()
            ->whereField('date', $visitedAt->format(DATE_ATOM))
            ->first();

        if (! $day) {
            $day = new VisitsPerHour(
                date: $visitedAt,
                count: 0,
            );
        }

        $day->increment()->save();
    }
}
