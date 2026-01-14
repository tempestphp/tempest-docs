<?php

declare(strict_types=1);

namespace App\Web\Community;

use Tempest\Router\Get;
use Tempest\Router\StaticPage;
use Tempest\View\View;

use function Tempest\View\view;

final class CommunityController
{
    #[StaticPage]
    #[Get('/community')]
    public function index(CommunityPostsRepository $repository): View
    {
        return \Tempest\View\view('community.view.php', communityPosts: $repository->all());
    }
}
