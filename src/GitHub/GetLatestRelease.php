<?php

namespace App\GitHub;

use Tempest\Core\Kernel;
use Tempest\HttpClient\HttpClient;
use Throwable;

use function Tempest\env;

final class GetLatestRelease
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

        // Default release to the currently running version of Tempest.
        $defaultRelease = sprintf('v%s', Kernel::VERSION);

        try {
            $body = $this->httpClient
                ->get(
                    uri: 'https://api.github.com/repos/tempestphp/tempest-framework/releases/latest',
                    headers: $headers
                )
                ->body;

            return json_decode($body)->tag_name ?? $defaultRelease;
        } catch (Throwable $e) {
            ll($e);
            return Kernel::VERSION;
        }
    }
}
