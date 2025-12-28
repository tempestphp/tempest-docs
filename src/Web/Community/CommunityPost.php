<?php

declare(strict_types=1);

namespace App\Web\Community;

final class CommunityPost
{
    public string $uri;
    public string $title;
    public string $description;
    public ?CommunityPostType $type = null;
    public bool $wip = false;
}
