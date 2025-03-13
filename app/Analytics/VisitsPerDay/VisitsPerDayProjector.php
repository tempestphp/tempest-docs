<?php

namespace App\Analytics\VisitsPerDay;

use App\Analytics\PageVisited;
use App\Support\StoredEvents\Projector;
use Tempest\Database\Query;
use Tempest\EventBus\EventHandler;

final readonly class VisitsPerDayProjector implements Projector
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
            VisitsPerDay::table(),
        ));

        $query->execute();
    }

    #[EventHandler]
    public function onPageVisited(PageVisited $pageVisited): void
    {
        $visitedAt = $pageVisited->visitedAt->setTime(0, 0);

        $day = VisitsPerDay::query()
            ->whereField('date', $visitedAt->format(DATE_ATOM))
            ->first();

        if (! $day) {
            $day = new VisitsPerDay(
                date: $visitedAt,
                count: 0,
            );
        }

        $day->increment()->save();
    }
}
