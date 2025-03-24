<?php

namespace App\Web\Analytics\VisitsPerDay;

use App\StoredEvents\Projector;
use App\Web\Analytics\PageVisited;
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
            ->whereField('date', $visitedAt)
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
