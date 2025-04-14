<?php

namespace App\GitHub;

use Tempest\HttpClient\HttpClient;
use Throwable;

final class GetStargazersCount
{
    public function __construct(
        private HttpClient $httpClient,
    ) {
    }

    public function __invoke(): ?string
    {
        try {
            $body = $this->httpClient->get('https://api.github.com/repos/tempestphp/tempest-framework')->body;
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
