<?php

namespace App\Markdown\Symbols;

use League\CommonMark\Extension\CommonMark\Node\Inline\Code;
use League\CommonMark\Extension\CommonMark\Node\Inline\Link;
use League\CommonMark\Parser\Inline\InlineParserInterface;
use League\CommonMark\Parser\Inline\InlineParserMatch;
use League\CommonMark\Parser\InlineParserContext;
use Tempest\Support\Str;

use function Tempest\Support\str;

final readonly class FqcnParser implements InlineParserInterface
{
    #[\Override]
    public function getMatchDefinition(): InlineParserMatch
    {
        return InlineParserMatch::regex("{(b)?`((?:\\\{1,2}\w+|\w+\\\{1,2})(?:\w+\\\{0,2})+)`}");
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

        [$flag, $fqcn] = $inlineContext->getSubMatches();
        $url = str($fqcn)
            ->stripStart(['\\Tempest\\', 'Tempest\\'])
            ->replaceRegex("/^(\w+)/", fn (array $matches) => sprintf('packages/%s/src', Str\to_kebab_case($matches[0])))
            ->replace('\\', '/')
            ->prepend('https://github.com/tempestphp/tempest-framework/blob/main/')
            ->append('.php');

        $link = new Link($url);
        $link->appendChild(new Code($flag === 'b' ? Str\class_basename($fqcn) : Str\strip_start($fqcn, '\\')));
        $inlineContext->getContainer()->appendChild($link);

        return true;
    }
}
