<?php

namespace App\Advocacy\Reddit;

final class RedditConfig
{
    public function __construct(
        public array $subreddits,
        public array $keywords,
        // TODO: add API keys if necessary
    ) {}
}