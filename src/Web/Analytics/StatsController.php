<?php

namespace App\Web\Analytics;

use App\Web\Analytics\PackageDownloadsPerHour\PackageDownloadsPerHour;
use App\Web\Analytics\VisitsPerDay\VisitsPerDay;
use App\Web\Analytics\VisitsPerHour\VisitsPerHour;
use DateInterval;
use DateTimeImmutable;
use Tempest\Clock\Clock;
use Tempest\Database\Query;
use Tempest\Router\Get;
use Tempest\View\View;

use function Tempest\Support\arr;
use function Tempest\view;

final readonly class StatsController
{
    #[Get('/stats')]
    public function __invoke(Clock $clock): View
    {
        $now = $clock->now();

        $visitsPerHour = arr(
            VisitsPerHour::query()
                ->where('date >= ?', $now->sub(DateInterval::createFromDateString('24 hours'))->format('Y-m-d H:i:s'))
                ->all(),
        );

        $visitsPerDay = arr(
            VisitsPerDay::query()
                ->where('date >= ?', $now->sub(DateInterval::createFromDateString('30 days'))->format('Y-m-d H:i:s'))
                ->all(),
        );

        $packageDownloadsPerHour = arr(new Query(<<<SQL
        SELECT `date`, SUM(`count`) as `count` FROM package_downloads_per_hours WHERE `date` >= :date GROUP BY `date`
        SQL)->fetch(
            date: $now->sub(DateInterval::createFromDateString('24 hours'))->format('Y-m-d H:i:s'),
        ));

        $packageDownloadsPerDay = arr(new Query(<<<SQL
        SELECT `date`, SUM(`count`) as `count` FROM package_downloads_per_days WHERE date >= :date GROUP BY `date`
        SQL)->fetch(
            date: $now->sub(DateInterval::createFromDateString('30 days'))->format('Y-m-d H:i:s'),
        ));

        return view(
            __DIR__ . '/stats.view.php',

            visitsPerHour: new Chart(
                labels: $visitsPerHour->map(fn (VisitsPerHour $item) => $item->date->format('H:i')),
                values: $visitsPerHour->map(fn (VisitsPerHour $item) => $item->count),
            ),

            visitsPerDay: new Chart(
                labels: $visitsPerDay->map(fn (VisitsPerDay $item) => $item->date->format('Y-m-d')),
                values: $visitsPerDay->map(fn (VisitsPerDay $item) => $item->count),
            ),

            packageDownloadsPerHour: new Chart(
                labels: $packageDownloadsPerHour->map(fn (array $item) => new DateTimeImmutable($item['date'])->format('H:i')),
                values: $packageDownloadsPerHour->map(fn (array $item) => $item['count']),
            ),

            packageDownloadsPerDay: new Chart(
                labels: $packageDownloadsPerDay->map(fn (array $item) => new DateTimeImmutable($item['date'])->format('Y-m-d')),
                values: $packageDownloadsPerDay->map(fn (array $item) => $item['count']),
            ),
        );
    }
}
