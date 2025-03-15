<?php

namespace App\StoredEvents;

use DateTimeImmutable;

interface HasCreatedAtDate
{
    public DateTimeImmutable $createdAt {
        get;
    }
}
