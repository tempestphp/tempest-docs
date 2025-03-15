<?php

namespace App\Markdown;

use League\CommonMark\Extension\CommonMark\Node\Inline\Code;
use League\CommonMark\Extension\CommonMark\Node\Inline\Link;
use League\CommonMark\Parser\Inline\InlineParserInterface;
use League\CommonMark\Parser\Inline\InlineParserMatch;
use League\CommonMark\Parser\InlineParserContext;

use function Tempest\Support\Namespace\to_base_class_name;
use function Tempest\Support\Namespace\to_namespace;
use function Tempest\Support\str;

final readonly class FqcnParser implements InlineParserInterface
{
    public function getMatchDefinition(): InlineParserMatch
    {
        return InlineParserMatch::regex("{`((?:\\\{1,2}\w+|\w+\\\{1,2})(?:\w+\\\{0,2})+)`}");
    }

    public function parse(InlineParserContext $inlineContext): bool
    {
        $cursor = $inlineContext->getCursor();
        $previousChar = $cursor->peek(-1);

        if ($previousChar !== null && $previousChar !== ' ') {
            return false;
        }

        $cursor->advanceBy($inlineContext->getFullMatchLength());

        [$fqcn] = $inlineContext->getSubMatches();
        $url = str($fqcn)
            ->stripStart('\\Tempest\\')
            ->replaceRegex("/^(\w+)/", 'src/Tempest/$0/src')
            ->replace('\\', '/')
            ->prepend('https://github.com/tempestphp/tempest-framework/blob/main/')
            ->append('.php');

        $link = new Link($url);
        $link->appendChild(new Code($fqcn));
        $inlineContext->getContainer()->appendChild($link);

        return true;
    }
}
