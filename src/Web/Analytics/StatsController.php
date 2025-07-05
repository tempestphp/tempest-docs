<?php

namespace App\Web\Analytics;

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
        $visitsPerDay = arr(
            query(VisitsPerDay::class)->select()->orderBy('date DESC')->limit(30)->all(),
        )->reverse();

        return view(
            __DIR__ . '/stats.view.php',

            visitsPerDay: new Chart(
                labels: $visitsPerDay->map(fn (VisitsPerDay $item) => $item->date->format('Y-m-d')),
                values: $visitsPerDay->map(fn (VisitsPerDay $item) => $item->count),
            ),
        );
    }
}
