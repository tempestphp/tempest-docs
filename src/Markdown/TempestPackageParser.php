<?php

namespace App\Markdown;

use League\CommonMark\Extension\CommonMark\Node\Inline\Code;
use League\CommonMark\Extension\CommonMark\Node\Inline\Link;
use League\CommonMark\Parser\Inline\InlineParserInterface;
use League\CommonMark\Parser\Inline\InlineParserMatch;
use League\CommonMark\Parser\InlineParserContext;

use function Tempest\Support\str;

final readonly class TempestPackageParser implements InlineParserInterface
{
    #[\Override]
    public function getMatchDefinition(): InlineParserMatch
    {
        return InlineParserMatch::regex("{`tempest\\/([\w-]+)`}");
    }

    #[\Override]
    public function parse(InlineParserContext $inlineContext): bool
    {
        $cursor = $inlineContext->getCursor();
        $previousChar = $cursor->peek(-1);

        if ($previousChar !== null && $previousChar !== ' ') {
            return false;
        }

        $cursor->advanceBy($inlineContext->getFullMatchLength());

        [$package] = $inlineContext->getSubMatches();

        $url = match ($package) {
            'app' => 'https://github.com/tempestphp/tempest-app',
            default => $url = str($package)
                ->pascal()
                ->prepend('https://github.com/tempestphp/tempest-framework/tree/main/src/Tempest/')
                ->toString(),
        };

        $link = new Link($url);
        $link->appendChild(new Code("tempest/$package"));
        $inlineContext->getContainer()->appendChild($link);

        return true;
    }
}
