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
            PackageDownloadsListed::table(),
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
    }
}