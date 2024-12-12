<?php

namespace App\Support\StoredEvents;

interface Projector
{
    public function replay(object $event): void;

    public function clear(): void;
}