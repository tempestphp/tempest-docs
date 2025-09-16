<?php

namespace App\GitHub;

use Throwable;
use function Tempest\Intl\Number\to_human_readable;
use Tempest\Cache\Cache;
use Tempest\HttpClient\HttpClient;
use Tempest\Intl\Number;

use function Tempest\env;

final class GetStargazersCount
{
    public function __construct(
        private HttpClient $httpClient,
        private Cache $cache,
    ) {}

    public function __invoke(): ?string
    {
        $stargazers = $this->cache->resolve(
            'stargazers',
            function () {
                return $this->getStargazersCount();
            },
        );

        if ($stargazers) {
            return to_human_readable($stargazers, maxPrecision: 1);
        }

        return null;
    }

    private function getStargazersCount(): ?int
    {
        if ($stargazers = env('TEMPEST_BUILD_STARGAZERS')) {
            return $stargazers;
        }

        try {
            $body = $this->httpClient
                ->get(uri: 'https://api.github.com/repos/tempestphp/tempest-framework')
                ->body;

            return $stargazers = json_decode($body)->stargazers_count ?? null;
        } catch (Throwable) {
            return null;
        }
    }
}
