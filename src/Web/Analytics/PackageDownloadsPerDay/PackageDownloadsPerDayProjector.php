<?php

namespace App\Web\Analytics\PackageDownloadsPerDay;

use App\StoredEvents\Projector;
use App\Web\Analytics\PackageDownloadsListed;
use PDOException;
use Tempest\Database\Builder\QueryBuilders\QueryBuilder;
use Tempest\EventBus\EventHandler;
use function Tempest\Database\query;

final readonly class PackageDownloadsPerDayProjector implements Projector
{
    #[\Override]
    public function clear(): void
    {
        new QueryBuilder(PackageDownloadsPerDay::class)
            ->delete()
            ->allowAll()
            ->execute();
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
        $date = $event->date->setTime(0, 0);

        $count = $event->total;

        $count = max($count, 0);

        $packageDownloadsPerDay = query(PackageDownloadsPerDay::class)
            ->select()
            ->where('date = ? AND package = ?', $date, $event->package)
            ->first();

        if (!$packageDownloadsPerDay) {
            $packageDownloadsPerDay = new PackageDownloadsPerDay(
                date: $date,
                package: $event->package,
                count: 0,
                total: 0,
            );
        }

        $packageDownloadsPerDay->total = $event->total;
        $packageDownloadsPerDay->count = $count;
        $packageDownloadsPerDay->save();
    }
}
