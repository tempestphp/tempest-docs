<?php

namespace App\Web\Analytics\PackageDownloadsPerHour;

use App\StoredEvents\Projector;
use App\Web\Analytics\PackageDownloadsListed;
use Tempest\Database\Query;
use Tempest\EventBus\EventHandler;

final readonly class PackageDownloadsPerHourProjector implements Projector
{
    #[\Override]
    public function clear(): void
    {
        $query = new Query(sprintf(
            'DELETE FROM %s',
            PackageDownloadsPerHour::table(),
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

        $date = $event->date->setTime($event->date->format('H'), 0, 0);

        PackageDownloadsPerHour::updateOrCreate(
            [
                'date' => $date->format(DATE_ATOM),
                'package' => $event->package,
            ],
            [
                'total' => $event->total,
                'count' => $count,
            ],
        );
    }
}
