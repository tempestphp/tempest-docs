<?php

namespace App\Support\StoredEvents;

use Tempest\Core\Discovery;
use Tempest\Core\DiscoveryLocation;
use Tempest\Core\IsDiscovery;
use Tempest\Reflection\ClassReflector;

final class ProjectionDiscovery implements Discovery
{
    use IsDiscovery;

    public function __construct(
        private readonly StoredEventConfig $config,
    ) {}

    public function discover(DiscoveryLocation $location, ClassReflector $class): void
    {
        if ($class->implements(Projector::class)) {
            $this->discoveryItems->add($location, $class->getName());
        }
    }

    public function apply(): void
    {
        foreach ($this->discoveryItems as $className) {
            $this->config->projectors[] = $className;
        }
    }
}