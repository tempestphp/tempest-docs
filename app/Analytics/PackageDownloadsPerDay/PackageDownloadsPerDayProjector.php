<?php

namespace App\Analytics\PackageDownloadsPerDay;

use App\Analytics\PackageDownloadsListed;
use App\Support\StoredEvents\Projector;
use Tempest\Database\Query;
use Tempest\EventBus\EventHandler;

final readonly class PackageDownloadsPerDayProjector implements Projector
{
    public function clear(): void
    {
        $query = new Query(sprintf(
            "DELETE FROM %s",
            PackageDownloadsPerDay::table(),
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
        $previousDay = PackageDownloadsPerDay::query()
            ->whereField('package', $event->package)
            ->orderBy('date DESC')
            ->first();

        if ($previousDay) {
            $count = $event->total - $previousDay->total;
        } else {
            $count = $event->total;
        }

        $count = max($count, 0);

        PackageDownloadsPerDay::updateOrCreate(
            [
                'date' => $event->date->format('Y-m-d 00:00:00'),
                'package' => $event->package,
            ],
            [
                'total' => $event->total,
                'count' => $count,
            ],
        );
    }
}