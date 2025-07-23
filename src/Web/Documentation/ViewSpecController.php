<?php

namespace App\Web\Documentation;

use Tempest\Router\Get;
use Tempest\View\View;

final class ViewSpecController
{
    #[Get('/docs/view-spec')]
    public function __invoke(): View
    {
        return markdown(__DIR__ . '/view-spec.md');
    }
}