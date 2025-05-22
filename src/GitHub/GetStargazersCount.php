<?php

namespace App\GitHub;

use PDO;
use Tempest\HttpClient\HttpClient;
use Throwable;

use function Tempest\env;

final class GetStargazersCount
{
    public function __construct(
        private HttpClient $httpClient,
    ) {
    }

    public function __invoke(): ?string
    {
        if ($stargazers = $this->getStargazersCount()) {
            return $stargazers > 999
                ? (round($stargazers / 1000, 1) . 'K')
                : $stargazers;
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
        } catch (\Throwable) {
            return null;
        }
    }
}
