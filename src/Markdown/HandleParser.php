<?php

namespace App\Markdown;

use League\CommonMark\Extension\CommonMark\Node\Inline\Link;
use League\CommonMark\Parser\Inline\InlineParserInterface;
use League\CommonMark\Parser\Inline\InlineParserMatch;
use League\CommonMark\Parser\InlineParserContext;

use function Tempest\Support\str;

final readonly class HandleParser implements InlineParserInterface
{
    public function getMatchDefinition(): InlineParserMatch
    {
        return InlineParserMatch::regex('{(twitter|x|bluesky|bsky|gh|github):(.+?)(?:,(.+))?}');
    }

    public function parse(InlineParserContext $inlineContext): bool
    {
        $cursor = $inlineContext->getCursor();
        $previousChar = $cursor->peek(-1);

        if ($previousChar !== null && $previousChar !== ' ') {
            return false;
        }

        $cursor->advanceBy($inlineContext->getFullMatchLength());

        [$platform, $handle, $text] = $inlineContext->getSubMatches() + [null, null, null];

        $url = match ($platform) {
            'bluesky', 'bsky' => "https://bsky.app/profile/$handle",
            'gh', 'github' => "https://github.com/$handle",
            'x', 'twitter' => "https://x.com/$handle",
            default => throw new \RuntimeException("Unknown platform: $platform"),
        };

        $inlineContext->getContainer()->appendChild(
            new Link($url, label: $text ?? ('@' . $handle)),
        );

        return true;
    }
}
