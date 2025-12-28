<?php

declare(strict_types=1);

namespace App\Web;

use App\GitHub\GetStargazersCount;
use Override;
use Tempest\View\View;
use Tempest\View\ViewProcessor;

final readonly class StargazersViewProcessor implements ViewProcessor
{
    public function __construct(
        private GetStargazersCount $getStargazersCount,
    ) {}

    #[Override]
    public function process(View $view): View
    {
        return $view->data(stargazers_count: ($this->getStargazersCount)());
    }
}
