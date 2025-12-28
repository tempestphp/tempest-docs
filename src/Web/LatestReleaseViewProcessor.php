<?php

declare(strict_types=1);

namespace App\Web;

use App\GitHub\GetLatestRelease;
use Override;
use Tempest\View\View;
use Tempest\View\ViewProcessor;

final readonly class LatestReleaseViewProcessor implements ViewProcessor
{
    public function __construct(
        private GetLatestRelease $getLatestRelease,
    ) {}

    #[Override]
    public function process(View $view): View
    {
        return $view->data(latest_release: ($this->getLatestRelease)());
    }
}
