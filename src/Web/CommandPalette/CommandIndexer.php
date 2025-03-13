<?php

namespace Tempest\Web\CommandPalette;

use Tempest\Support\Arr\ImmutableArray;
use Tempest\Web\Blog\BlogController;
use Tempest\Web\CommandPalette\Command;
use Tempest\Web\Documentation\ChapterController;
use Tempest\Web\RedirectsController;

use function Tempest\uri;

final readonly class CommandIndexer
{
    public function __invoke(): ImmutableArray
    {
        return new ImmutableArray([
            new Command(
                type: Type::URI,
                title: 'Read the documentation',
                hierarchy: ['Commands', 'Documentation'],
                uri: uri([ChapterController::class, 'index']),
            ),
            new Command(
                type: Type::URI,
                title: 'Visit the blog',
                hierarchy: ['Commands', 'Blog'],
                uri: uri([BlogController::class, 'index']),
            ),
            new Command(
                type: Type::URI,
                title: 'See the code on GitHub',
                hierarchy: ['Commands', 'Link'],
                uri: uri([RedirectsController::class, 'github']),
            ),
            new Command(
                type: Type::URI,
                title: 'Join our Discord server',
                hierarchy: ['Commands', 'Link'],
                uri: uri([RedirectsController::class, 'discord']),
            ),
            new Command(
                type: Type::URI,
                title: 'Follow me on Bluesky',
                hierarchy: ['Commands', 'Link'],
                uri: uri([RedirectsController::class, 'bluesky']),
            ),
            new Command(
                type: Type::URI,
                title: 'Follow me on X',
                hierarchy: ['Commands', 'Link'],
                uri: uri([RedirectsController::class, 'twitter']),
            ),
            new Command(
                type: Type::JAVASCRIPT,
                title: 'Toggle dark mode',
                javascript: 'toggleDarkMode',
                hierarchy: ['Commands', 'Command'],
            ),
        ]);
    }
}
