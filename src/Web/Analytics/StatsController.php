<?php

namespace App\Web\Analytics;

use App\Web\Analytics\PackageDownloadsPerDay\PackageDownloadsPerDay;
use App\Web\Analytics\VisitsPerDay\VisitsPerDay;
use Tempest\Clock\Clock;
use Tempest\DateTime\Duration;
use Tempest\Router\Get;
use Tempest\View\View;

use function Tempest\Database\query;
use function Tempest\Support\arr;
use function Tempest\view;

final readonly class StatsController
{
    #[Get('/stats')]
    public function __invoke(Clock $clock): View
    {
        $limit = 30;

        $visitsPerDay = arr(
            query(VisitsPerDay::class)->select()->orderBy('date DESC')->limit($limit)->all(),
        )->reverse();

        $packageDownloadsPerDay = arr(
            query(PackageDownloadsPerDay::class)
                ->select()
                ->whereField('package', 'framework')
                ->orderBy('date DESC')
                ->limit($limit)
                ->all(),
        )->reverse();

        return view(
            __DIR__ . '/stats.view.php',

            visitsPerDay: new Chart(
                labels: $visitsPerDay->map(fn (VisitsPerDay $item) => $item->date->format('Y-m-d')),
                values: $visitsPerDay->map(fn (VisitsPerDay $item) => $item->count),
            ),

            packageDownloadsPerDay: new Chart(
                labels: $packageDownloadsPerDay->map(fn (PackageDownloadsPerDay $item) => $item->date->format('Y-m-d')),
                values: $packageDownloadsPerDay->map(fn (PackageDownloadsPerDay $item) => $item->total),
                min: $packageDownloadsPerDay->sortByCallback(fn (PackageDownloadsPerDay $a, PackageDownloadsPerDay $b) => $a->total)->first()->total,
            ),
        );
    }
}
