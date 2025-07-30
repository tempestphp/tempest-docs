<?php

namespace App\Web\Community;

use Tempest\Router\Get;
use Tempest\Router\StaticPage;
use Tempest\View\View;

use function Tempest\view;

final class CommunityController
{
    #[StaticPage]
    #[Get('/community')]
    public function index(CommunityPostsRepository $repository): View
    {
        return view('community.view.php', communityPosts: $repository->all());
    }
}
