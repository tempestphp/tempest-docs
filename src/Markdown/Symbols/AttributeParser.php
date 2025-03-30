<?php

namespace App\Markdown\Symbols;

use League\CommonMark\Extension\CommonMark\Node\Inline\Code;
use League\CommonMark\Extension\CommonMark\Node\Inline\Link;
use League\CommonMark\Parser\Inline\InlineParserInterface;
use League\CommonMark\Parser\Inline\InlineParserMatch;
use League\CommonMark\Parser\InlineParserContext;

use function Tempest\Support\str;
use function Tempest\Support\Str\class_basename;
use function Tempest\Support\Str\strip_start;

final readonly class AttributeParser implements InlineParserInterface
{
    #[\Override]
    public function getMatchDefinition(): InlineParserMatch
    {
        return InlineParserMatch::regex("{(b)?`#\[((?:\\\{1,2}\w+|\w+\\\{1,2})(?:\w+\\\{0,2})+)\]`}");
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
            ->replaceRegex("/^(\w+)/", 'src/Tempest/$0/src')
            ->replace('\\', '/')
            ->prepend('https://github.com/tempestphp/tempest-framework/blob/main/')
            ->append('.php');

        $attribute = str($fqcn)
            ->stripStart('\\')
            ->when($flag === 'b', fn ($s) => $s->classBasename())
            ->wrap(before: '#[', after: ']')
            ->toString();

        $link = new Link($url);
        $link->appendChild(new Code($attribute));
        $inlineContext->getContainer()->appendChild($link);

        return true;
    }
}
