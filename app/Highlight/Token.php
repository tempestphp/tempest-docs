<?php

namespace App\Highlight;

final class Token
{
    public int $length;

    public function __construct(
        public int $offset,
        public string $value,
        public TokenType $type,
        public ?string $pattern = null,
        public ?Language $language = null,
    ) {
        $this->length = strlen($this->value);
    }
}