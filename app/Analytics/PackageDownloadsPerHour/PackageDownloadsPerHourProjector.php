<?php

namespace App\Analytics\PackageDownloadsPerHour;

use App\Analytics\PackageDownloadsListed;
use App\Support\StoredEvents\Projector;
use Tempest\Database\Query;
use Tempest\EventBus\EventHandler;

final readonly class PackageDownloadsPerHourProjector implements Projector
{
    public function clear(): void
    {
        $query = new Query(sprintf(
            "DELETE FROM %s",
            PackageDownloadsPerHour::table(),
        ));

        $query->execute();
    }

    public function replay(object $event): void
    {
        if ($event instanceof PackageDownloadsListed) {
            $this->onPackageDownloadsListed($event);
        }
    }

    #[EventHandler]
    public function onPackageDownloadsListed(PackageDownloadsListed $event): void
    {
        $previousDate = $event->date->modify('-1 hour');

        $previousHour = PackageDownloadsPerHour::query()
            ->whereField('date', $previousDate->format('Y-m-d H:00:00' ))
            ->whereField('package', $event->package)
            ->first();

        if ($previousHour) {
            $count = $event->total - $previousHour->count;
        } else {
            $count = $event->total;
        }

        PackageDownloadsPerHour::updateOrCreate(
            [
                'date' => $event->date->format('Y-m-d H:00:00'),
                'package' => $event->package,
            ],
            [
                'count' => $count,
            ]
        );
    }
}