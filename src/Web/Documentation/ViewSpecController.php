<?php

declare(strict_types=1);

namespace App\Web\Documentation;

use Tempest\Http\Responses\Redirect;
use Tempest\Router\Get;

final class ViewSpecController
{
    #[Get('/docs/view-spec')]
    public function __invoke(): Redirect
    {
        return new Redirect('/docs/internals/view-spec');
    }
}
