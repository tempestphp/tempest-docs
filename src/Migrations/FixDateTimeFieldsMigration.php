<?php

namespace App\Migrations;

use App\Web\Analytics\VisitsPerDay\VisitsPerDay;
use App\Web\Analytics\VisitsPerHour\VisitsPerHour;
use Tempest\Console\ConsoleCommand;
use Tempest\Console\HasConsole;

final class FixDateTimeFieldsMigration
{
    use HasConsole;

    #[ConsoleCommand(name: 'fix:projections')]
    public function __invoke()
    {
        foreach (VisitsPerDay::all() as $visitsPerDay) {
            $visitsPerDay->save();
        }

        foreach (VisitsPerHour::all() as $visitsPerDay) {
            $visitsPerDay->save();
        }

        $this->success('Done');
    }
}