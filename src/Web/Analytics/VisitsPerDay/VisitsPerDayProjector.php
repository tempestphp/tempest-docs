<?php

declare(strict_types=1);

namespace App\Web\Analytics\VisitsPerDay;

use App\StoredEvents\Projector;
use App\Web\Analytics\PageVisited;
use Override;
use Tempest\Database\Builder\QueryBuilders\QueryBuilder;
use Tempest\EventBus\EventHandler;

final readonly class VisitsPerDayProjector implements Projector
{
    #[Override]
    public function replay(object $event): void
    {
        if ($event instanceof PageVisited) {
            $this->onPageVisited($event);
        }
    }

    #[Override]
    public function clear(): void
    {
        new QueryBuilder(VisitsPerDay::class)
            ->delete()
            ->allowAll()
            ->execute();
    }

    #[EventHandler]
    public function onPageVisited(PageVisited $pageVisited): void
    {
        $visitedAt = $pageVisited->visitedAt->setTime(0, 0);

        $day = VisitsPerDay::select()
            ->whereField('date', $visitedAt->format('Y-m-d H:i:s'))
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
