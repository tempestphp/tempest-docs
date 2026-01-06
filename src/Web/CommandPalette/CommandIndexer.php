<?php

declare(strict_types=1);

namespace App\Web\CommandPalette;

use App\Web\Blog\BlogController;
use App\Web\Community\CommunityController;
use App\Web\Documentation\DocumentationController;
use App\Web\RedirectsController;
use Override;
use Tempest\Support\Arr\ImmutableArray;

use function Tempest\Router\uri;

final readonly class CommandIndexer implements Indexer
{
    #[Override]
    public function index(): ImmutableArray
    {
        return new ImmutableArray([
            new Command(
                title: 'Read the documentation',
                type: Type::URI,
                hierarchy: ['Commands', 'Documentation'],
                uri: uri([DocumentationController::class, 'index']),
            ),
            new Command(
                title: 'Visit the blog',
                type: Type::URI,
                hierarchy: ['Commands', 'Blog'],
                uri: uri([BlogController::class, 'index']),
            ),
            new Command(
                title: 'Check out community resources',
                type: Type::URI,
                hierarchy: ['Commands', 'Community'],
                uri: uri([CommunityController::class, 'index']),
            ),
            new Command(
                title: 'See the code on GitHub',
                type: Type::URI,
                hierarchy: ['Commands', 'Link'],
                uri: uri([RedirectsController::class, 'github']),
            ),
            new Command(
                title: 'Join our Discord server',
                type: Type::URI,
                hierarchy: ['Commands', 'Link'],
                uri: uri([RedirectsController::class, 'discord']),
            ),
            new Command(
                title: 'Follow Brent on Bluesky',
                type: Type::URI,
                hierarchy: ['Commands', 'Link', 'Core team'],
                uri: uri([RedirectsController::class, 'blueskyBrent']),
            ),
            new Command(
                title: 'Follow Brent on X',
                type: Type::URI,
                hierarchy: ['Commands', 'Link', 'Core team'],
                uri: uri([RedirectsController::class, 'twitterBrent']),
            ),
            new Command(
                title: 'Follow Enzo on Bluesky',
                type: Type::URI,
                hierarchy: ['Commands', 'Link', 'Core team'],
                uri: uri([RedirectsController::class, 'blueskyEnzo']),
            ),
            new Command(
                title: 'Follow Enzo on X',
                type: Type::URI,
                hierarchy: ['Commands', 'Link', 'Core team'],
                uri: uri([RedirectsController::class, 'twitterEnzo']),
            ),
            new Command(
                title: 'Follow Aidan from the core team on X',
                type: Type::URI,
                hierarchy: ['Commands', 'Link', 'Core team'],
                uri: uri([RedirectsController::class, 'twitterAidan']),
            ),
            new Command(
                title: 'Toggle dark mode',
                type: Type::JAVASCRIPT,
                hierarchy: ['Commands', 'Command'],
                javascript: 'toggleDarkMode',
            ),
        ]);
    }
}
