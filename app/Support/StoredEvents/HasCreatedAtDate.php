<?php

namespace App\Support\StoredEvents;

use DateTimeImmutable;

interface HasCreatedAtDate
{
    public DateTimeImmutable $createdAt {
        get;
    }
}