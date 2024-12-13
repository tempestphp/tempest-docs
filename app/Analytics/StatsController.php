<?php

namespace App\Analytics;

use App\Analytics\VisitsPerDay\VisitsPerDay;
use App\Analytics\VisitsPerHour\VisitsPerHour;
use DateInterval;
use Tempest\Clock\Clock;
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

        $visitsPerHour = arr(VisitsPerHour::query()
            ->where('date >= ?', $now->sub(DateInterval::createFromDateString('24 hours'))->format('Y-m-d H:i:s'))
            ->all());

        $visitsPerDay = arr(VisitsPerDay::query()
            ->where('date >= ?', $now->sub(DateInterval::createFromDateString('30 days'))->format('Y-m-d H:i:s'))
            ->all());

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
        );
    }
}