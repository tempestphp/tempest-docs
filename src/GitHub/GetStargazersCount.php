<?php

namespace App\GitHub;

use Tempest\HttpClient\HttpClient;
use Tempest\Support\Number;
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
            return Number\to_human_readable($stargazers, maxPrecision: 1);
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
