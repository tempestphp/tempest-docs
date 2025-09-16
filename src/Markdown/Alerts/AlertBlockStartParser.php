<?php

namespace App\Markdown\Alerts;

use Override;
use League\CommonMark\Parser\Block\BlockStart;
use League\CommonMark\Parser\Block\BlockStartParserInterface;
use League\CommonMark\Parser\Cursor;
use League\CommonMark\Parser\MarkdownParserStateInterface;
use League\CommonMark\Util\RegexHelper;

final class AlertBlockStartParser implements BlockStartParserInterface
{
    #[Override]
    public function tryStart(Cursor $cursor, MarkdownParserStateInterface $parserState): ?BlockStart
    {
        if ($cursor->isIndented()) {
            return BlockStart::none();
        }

        $match = RegexHelper::matchFirst('/^:::(?!group)(?<type>[a-z]+)({(?<icon>.*?)})? ?(?<title>.*?)$/i', $cursor->getLine());

        if ($match === null) {
            return BlockStart::none();
        }

        $cursor->advanceToEnd();

        $alertType = $match['type'];
        $icon = $match['icon'] ?: null;
        $title = $match['title'] ?: null;

        return BlockStart::of(new AlertBlockParser($alertType, $icon, $title))->at($cursor);
    }
}
