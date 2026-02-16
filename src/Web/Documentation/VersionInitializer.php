<?php

namespace App\Web\Documentation;

use Tempest\Container\Container;
use Tempest\Container\Initializer;
use Tempest\Router\MatchedRoute;

final class VersionInitializer implements Initializer
{
    public function initialize(Container $container): Version
    {
        $version = $container->get(MatchedRoute::class)?->params['version'] ?? null;

        if (! $version) {
            return Version::default();
        }

        return Version::tryFromString($version) ?? Version::default();
    }
}