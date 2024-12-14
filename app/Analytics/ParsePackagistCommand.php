<?php

namespace App\Analytics;

use DateTimeImmutable;
use Tempest\Cache\Cache;
use Tempest\Console\ConsoleCommand;
use Tempest\Console\HasConsole;
use Tempest\Console\Schedule;
use Tempest\Console\Scheduler\Every;
use Tempest\HttpClient\HttpClient;
use Throwable;
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
            'app',
            'app-console',
            'cache',
            'console',
            'clock',
            'highlight',
            'command-bus',
            'core',
            'database',
            'debug',
            'http',
            'http-client',
            'log',
            'mapper',
            'reflection',
            'router',
            'support',
            'validation',
            'view',
        ];

        foreach ($packages as $package) {
            $url = "https://packagist.org/packages/tempest/{$package}.json";

            try {
                $data = $this->cache->resolve("packagist-{$package}", function () use ($url) {
                    return json_decode($this->httpClient->get($url)->body, associative: true);
                }, new DateTimeImmutable('+ 30 minutes'));

                event(new PackageDownloadsListed(
                    package: $package,
                    monthly: $data['package']['downloads']['monthly'] ?? null,
                    daily: $data['package']['downloads']['daily'] ?? null,
                    total: $data['package']['downloads']['total'] ?? null,
                ));

                $this->success("tempest/{$package}");
            } catch (Throwable $e) {
                $this->error("tempest/{$package}");
                $this->writeln($e->getMessage());
            }
        }
    }
}