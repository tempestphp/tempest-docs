<?php

namespace App\Web\Community;

use Symfony\Component\Yaml\Yaml;
use Tempest\Support\Arr\ImmutableArray;

use function Tempest\Support\arr;

final class CommunityPostsRepository
{
    public function all(): ImmutableArray
    {
        return arr(Yaml::parseFile(__DIR__ . '/communityPosts.yaml'))
            ->mapTo(CommunityPost::class);
    }
}
