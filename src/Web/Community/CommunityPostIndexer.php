<?php

declare(strict_types=1);

namespace App\Web\Community;

use App\Web\CommandPalette\Command;
use App\Web\CommandPalette\Indexer;
use App\Web\CommandPalette\Type;
use Override;
use Tempest\Support\Arr\ImmutableArray;

final readonly class CommunityPostIndexer implements Indexer
{
    public function __construct(
        private CommunityPostsRepository $repository,
    ) {}

    #[Override]
    public function index(): ImmutableArray
    {
        return $this->repository
            ->all()
            ->map(static fn (CommunityPost $post) => new Command(
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
            ));
    }
}
