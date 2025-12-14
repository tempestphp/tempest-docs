<?php

namespace App\Markdown\CodeGroups;

use League\CommonMark\Parser\Block\BlockStart;
use League\CommonMark\Parser\Block\BlockStartParserInterface;
use League\CommonMark\Parser\Cursor;
use League\CommonMark\Parser\MarkdownParserStateInterface;
use League\CommonMark\Util\RegexHelper;
use Override;

final class CodeGroupBlockStartParser implements BlockStartParserInterface
{
    #[Override]
    public function tryStart(Cursor $cursor, MarkdownParserStateInterface $parser_state): ?BlockStart
    {
        if ($cursor->isIndented()) {
            return BlockStart::none();
        }

        $match = RegexHelper::matchFirst('/^:::code-group$/', $cursor->getLine());

        if ($match === null) {
            return BlockStart::none();
        }

        $cursor->advanceToEnd();

        return BlockStart::of(new CodeGroupBlockParser())->at($cursor);
    }
}
