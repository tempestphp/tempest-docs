<?php

namespace App\GitHub;

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
        if ($stargazers = env('TEMPEST_BUILD_STARGAZERS')) {
            return $stargazers;
        }

        try {
            $body = $this->httpClient
                ->get(uri: 'https://api.github.com/repos/tempestphp/tempest-framework')
                ->body;
            $stargazers = json_decode($body)->stargazers_count ?? null;

            return $stargazers > 999
                ? (round($stargazers / 1000, 1) . 'K')
                : $stargazers;
        } catch (Throwable $e) {
            ll($e);
            return null;
        }
    }
}
