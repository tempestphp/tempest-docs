<?php

namespace App\Analytics;

use DateTimeImmutable;
use Tempest\Cache\Cache;
use Tempest\Console\ConsoleCommand;
use Tempest\Console\HasConsole;
use Tempest\Console\Schedule;
use Tempest\Console\Scheduler\Every;
use Tempest\HttpClient\HttpClient;
use function Tempest\event;

final readonly class ParsePackagistCommand
{
    use HasConsole;

    public function __construct(
        private HttpClient $httpClient,
        private Cache $cache,
    ) {}

    #[Schedule(Every::HOUR)]
    #[ConsoleCommand]
    public function __invoke(): void
    {
        $packages = [
            'framework',
            'view',
            'console',
        ];

        foreach ($packages as $package) {
            $url = "https://packagist.org/packages/tempest/{$package}.json";

            $data = $this->cache->resolve("packagist-{$package}", function () use ($url) {
                return json_decode($this->httpClient->get($url)->body, associative: true);
            }, new DateTimeImmutable('+ 30 minutes'));

            event(new PackageDownloadsListed(
                package: $package,
                monthly: $data['package']['downloads']['monthly'],
                daily: $data['package']['downloads']['daily'],
                total: $data['package']['downloads']['total'],
            ));

            $this->success("tempest/{$package}");
        }
    }
}