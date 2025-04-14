<?php

namespace App\Web\Analytics\VisitsPerHour;

use App\StoredEvents\Projector;
use App\Web\Analytics\PageVisited;
use Tempest\Database\Builder\QueryBuilders\QueryBuilder;
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
        new QueryBuilder(VisitsPerHour::class)
            ->delete()
            ->execute();
    }

    #[EventHandler]
    public function onPageVisited(PageVisited $pageVisited): void
    {
        $visitedAt = $pageVisited->visitedAt->setTime(
            hour: $pageVisited->visitedAt->format('H'),
            minute: 0,
        );

        $day = VisitsPerHour::select()
            ->whereField('date', $visitedAt->format('Y-m-d H:i:s'))
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
