<?php

namespace App\GitHub;

use Tempest\Core\Kernel;
use Tempest\HttpClient\HttpClient;
use Throwable;

final class GetLatestRelease
{
    public function __construct(
        private HttpClient $httpClient,
    ) {
    }

    public function __invoke(): ?string
    {
        // Default release to the currently running version of Tempest.
        $defaultRelease = sprintf('v%s', Kernel::VERSION);

        try {
            $body = $this->httpClient
                ->get('https://api.github.com/repos/tempestphp/tempest-framework/releases/latest')
                ->body;

            return json_decode($body)->tag_name ?? $defaultRelease;
        } catch (Throwable $e) {
            ll($e);
            return Kernel::VERSION;
        }
    }
}
