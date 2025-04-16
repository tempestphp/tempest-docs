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
        // Added by Aidan Casey to combat the GitHub rate limits.
        // We will inject the GH_TOKEN using our workflow.
        $headers = [];

        if ($githubToken = env('GH_TOKEN')) {
            $headers['Authorization'] = 'Bearer ' . $githubToken;
        }

        try {
            $body = $this->httpClient->get(
                uri: 'https://api.github.com/repos/tempestphp/tempest-framework',
                headers: $headers
            )->body;
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
