<?php

namespace App\Web\Analytics\PackageDownloadsPerDay;

use App\StoredEvents\Projector;
use App\Web\Analytics\PackageDownloadsListed;
use Tempest\Database\Query;
use Tempest\EventBus\EventHandler;

final readonly class PackageDownloadsPerDayProjector implements Projector
{
    #[\Override]
    public function clear(): void
    {
        $query = new Query(sprintf(
            'DELETE FROM %s',
            PackageDownloadsPerDay::table(),
        ));

        $query->execute();
    }

    #[\Override]
    public function replay(object $event): void
    {
        if ($event instanceof PackageDownloadsListed) {
            $this->onPackageDownloadsListed($event);
        }
    }

    #[EventHandler]
    public function onPackageDownloadsListed(PackageDownloadsListed $event): void
    {
        $count = $event->total;

        $count = max($count, 0);

        PackageDownloadsPerDay::updateOrCreate(
            [
                'date' => $event->date,
                'package' => $event->package,
            ],
            [
                'total' => $event->total,
                'count' => $count,
            ],
        );
    }
}
