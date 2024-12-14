<?php

namespace App\Analytics\Migrations;

use App\Analytics\PackageDownloadsListed;
use App\Analytics\PageVisited;
use App\Support\StoredEvents\StoredEvent;
use Tempest\Database\DatabaseMigration;
use Tempest\Database\QueryStatement;
use Tempest\Database\QueryStatements\RawStatement;
use function Tempest\Support\arr;

final class MigratePageVisitedEvents implements DatabaseMigration
{
    public string $name = '2024-12-14_01_migrate_page_visited_date_field';

    public function up(): QueryStatement|null
    {
        $events = arr(StoredEvent::query()->where('eventClass = :eventClass', eventClass: PageVisited::class)->all());

        foreach ($events as $event) {
            $payload = json_decode($event->payload, true);

            if (! isset($payload['date']) || ! is_string($payload['date'])) {
                $payload['date'] = $event->createdAt->format('c');
            }

            $event->payload = json_encode($payload);
            $event->save();
        }

        return new RawStatement('-- nothing');
    }

    public function down(): QueryStatement|null
    {
        return null;
    }
}