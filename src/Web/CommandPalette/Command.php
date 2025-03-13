<?php

namespace Tempest\Web\CommandPalette;

use JsonSerializable;

final class Command implements JsonSerializable
{
    public function __construct(
        public readonly string $title,
        public readonly Type $type,
        public readonly array $hierarchy,
        public readonly ?string $uri = null,
        public readonly ?string $javascript = null,
        public readonly array $fields = [],
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'type' => $this->type->value,
            'title' => $this->title,
            'uri' => $this->uri,
            'javascript' => $this->javascript,
            'hierarchy' => $this->hierarchy,
            'fields' => $this->fields,
        ];
    }
}
