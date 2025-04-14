<?php

namespace App\GitHub;

//

use DateTimeImmutable;
use Tempest\Cache\Cache;
use Tempest\Core\Kernel;
use Tempest\HttpClient\HttpClient;
use Throwable;

final class GetLatestRelease
{
    public function __construct(
        private HttpClient $httpClient,
        private Cache $cache,
    ) {
    }

    public function __invoke(): ?string
    {
        return $this->cache->resolve(
            key: 'tempest-latest-release',
            cache: function () {
                // Default release to the currently running version of Tempest.
                $defaultRelease = sprintf('v%s', Kernel::VERSION);

                try {
                    $body = $this->httpClient->get('https://api.github.com/repos/tempestphp/tempest-framework/releases/latest')->body;

                    return json_decode($body)->tag_name ?? $defaultRelease;
                } catch (Throwable $e) {
                    ll($e);
                    return Kernel::VERSION;
                }
            },
            expiresAt: new DateTimeImmutable('+12 hours'),
        );
    }
}