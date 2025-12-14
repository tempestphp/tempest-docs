<?php

namespace App\Markdown\Symbols;

use League\CommonMark\Extension\CommonMark\Node\Inline\Code;
use League\CommonMark\Extension\CommonMark\Node\Inline\Link;
use League\CommonMark\Parser\Inline\InlineParserInterface;
use League\CommonMark\Parser\Inline\InlineParserMatch;
use League\CommonMark\Parser\InlineParserContext;
use Override;
use ReflectionFunction;
use Tempest\Support\Str\ImmutableString;
use Throwable;

use function Tempest\Support\str;

final readonly class FunctionParser implements InlineParserInterface
{
    #[Override]
    public function getMatchDefinition(): InlineParserMatch
    {
        return InlineParserMatch::regex("{(b)?`((?:\\\{1,2}\w+|\w+\\\{1,2})(?:\w+\\\{0,2})+)\(\)`}");
    }

    #[Override]
    public function parse(InlineParserContext $inlineContext): bool
    {
        $cursor = $inlineContext->getCursor();
        $previousChar = $cursor->peek(-1);

        if ($previousChar !== null && $previousChar !== ' ') {
            return false;
        }

        $cursor->advanceBy($inlineContext->getFullMatchLength());

        [$flag, $fqf] = $inlineContext->getSubMatches();

        $reflection = $this->createReflectionFromFqf($fqf);
        $function = str($fqf)
            ->stripStart('\\')
            ->when($flag === 'b', fn (ImmutableString $s) => $s->afterLast('\\'))
            ->append('()')
            ->toString();

        if (! $reflection) {
            $inlineContext->getContainer()->appendChild(new Code($function));

            return true;
        }

        $url = str($reflection->getFileName())
            ->afterLast('tempest/framework/')
            ->prepend('https://github.com/tempestphp/tempest-framework/blob/main/')
            ->append("#L{$reflection->getStartLine()}-L{$reflection->getEndLine()}")
            ->toString();

        $link = new Link($url);
        $link->appendChild(new Code($function));
        $inlineContext->getContainer()->appendChild($link);

        return true;
    }

    private function createReflectionFromFqf(string $fqf): ?ReflectionFunction
    {
        try {
            return new ReflectionFunction($fqf);
        } catch (Throwable) {
            return null;
        }
    }
}
