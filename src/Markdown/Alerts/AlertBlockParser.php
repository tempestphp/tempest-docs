<?php

namespace App\Markdown\Alerts;

use League\CommonMark\Node\Block\AbstractBlock;
use League\CommonMark\Parser\Block\BlockContinue;
use League\CommonMark\Parser\Block\BlockContinueParserInterface;
use League\CommonMark\Parser\Cursor;
use League\CommonMark\Util\RegexHelper;

final class AlertBlockParser implements BlockContinueParserInterface
{
    protected $block;
    protected $finished = false;

    public function __construct(
        protected string $alertType,
        protected string $title,
    ) {
        $this->block = new AlertBlock($alertType, $title);
    }

    public function addLine(string $line): void
    {
    }

    public function getBlock(): AbstractBlock
    {
        return $this->block;
    }

    public function isContainer(): bool
    {
        return true;
    }

    public function canContain(AbstractBlock $childBlock): bool
    {
        return true;
    }

    public function canHaveLazyContinuationLines(): bool
    {
        return false;
    }

    public function parseInlines(): bool
    {
        return true;
    }

    public function tryContinue(Cursor $cursor, BlockContinueParserInterface $activeBlockParser): ?BlockContinue
    {
        if ($cursor->isIndented()) {
            return BlockContinue::at($cursor);
        }

        $match = RegexHelper::matchFirst('/^:::$/', $cursor->getLine());
        if ($match !== null) {
            $this->finished = true;
            return BlockContinue::finished();
        }

        return BlockContinue::at($cursor);
    }

    public function closeBlock(): void
    {
        // Nothing to do here
    }

    public function isFinished(): bool
    {
        return $this->finished;
    }
}
