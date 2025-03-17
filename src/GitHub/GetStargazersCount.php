<?php

namespace App\GitHub;

use DateTimeImmutable;
use Tempest\Cache\Cache;
use Tempest\HttpClient\HttpClient;
use Throwable;

final class GetStargazersCount
{
    public function __construct(
        private HttpClient $httpClient,
        private Cache $cache,
    ) {}

    public function __invoke(): ?string
    {
        return $this->cache->resolve(
            key: 'tempest-stargazers',
            cache: function () {
                $body = $this->httpClient->get('https://api.github.com/repos/tempestphp/tempest-framework')->body;
                $stargazers = json_decode($body)->stargazers_count ?? null;

                return $stargazers > 999
                    ? (round($stargazers / 1000, 1) . 'K')
                    : $stargazers;
            },
            expiresAt: new DateTimeImmutable('+12 hours'),
        );
    }
}
