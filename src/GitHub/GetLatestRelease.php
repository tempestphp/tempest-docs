<?php

namespace App\GitHub;

use Tempest\Cache\Cache;
use Tempest\Core\Kernel;
use Tempest\HttpClient\HttpClient;
use Throwable;

use function Tempest\env;

final class GetLatestRelease
{
    public function __construct(
        private HttpClient $httpClient,
        private Cache $cache,
    ) {}

    public function __invoke(): ?string
    {
        if ($latestRelease = env('TEMPEST_BUILD_LATEST_RELEASE')) {
            return $latestRelease;
        }

        // Default release to the currently running version of Tempest.
        $defaultRelease = sprintf('v%s', Kernel::VERSION);

        try {
            return $this->cache->resolve(
                'latest_release',
                function () {
                    $body = $this->httpClient
                        ->get('https://api.github.com/repos/tempestphp/tempest-framework/releases/latest')
                        ->body;

                    return json_decode($body)->tag_name ?? null;
                },
            ) ?? $defaultRelease;
        } catch (Throwable) {
            return $defaultRelease;
        }
    }
}
