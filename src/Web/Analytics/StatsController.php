<?php

declare(strict_types=1);

namespace App\Web\Analytics;

use App\Web\Analytics\PackageDownloadsPerDay\PackageDownloadsPerDay;
use App\Web\Analytics\VisitsPerDay\VisitsPerDay;
use Tempest\Router\Get;
use Tempest\View\View;

use function Tempest\Database\query;
use function Tempest\Support\arr;
use function Tempest\View\view;

final readonly class StatsController
{
    #[Get('/stats')]
    public function __invoke(): View
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

        return \Tempest\View\view(
            __DIR__ . '/stats.view.php',

            visitsPerDay: new Chart(
                labels: $visitsPerDay->map(static fn (VisitsPerDay $item) => $item->date->format('Y-m-d')),
                values: $visitsPerDay->map(static fn (VisitsPerDay $item) => $item->count),
            ),

            packageDownloadsPerDay: new Chart(
                labels: $packageDownloadsPerDay->map(static fn (PackageDownloadsPerDay $item) => $item->date->format('Y-m-d')),
                values: $packageDownloadsPerDay->map(static fn (PackageDownloadsPerDay $item) => $item->total),
                min: $packageDownloadsPerDay->sortByCallback(static fn (PackageDownloadsPerDay $a, PackageDownloadsPerDay $_b) => $a->total)->first()->total,
            ),
        );
    }
}
