<?php

namespace App\Web\Community;

use App\Web\CommandPalette\Command;
use App\Web\CommandPalette\Type;
use Tempest\Support\Arr\ImmutableArray;

final readonly class CommunityPostIndexer
{
    public function __construct(
        private CommunityPostsRepository $repository,
    ) {}

    public function __invoke(): ImmutableArray
    {
        return $this->repository->all()
            ->map(function (CommunityPost $post) {
                return new Command(
                    title: $post->title,
                    type: Type::URI,
                    hierarchy: [
                        'Community',
                        $post->title,
                    ],
                    uri: $post->uri,
                    fields: array_filter([
                        $post->type?->value,
                        $post->description,
                    ]),
                );
            });
    }
}
