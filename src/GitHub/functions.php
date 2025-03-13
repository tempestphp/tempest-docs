<?php

namespace Tempest\GitHub;

use function Tempest\invoke;

function get_stargazers_count(): ?string
{
    return invoke(GetStargazersCount::class);
}
