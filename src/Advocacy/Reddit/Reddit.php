<?php

namespace App\Advocacy\Reddit;

final readonly class Reddit
{
    public function __construct(private RedditConfig $config) {}

    /** @return \App\Advocacy\Message[] */
    public function fetch(): array
    {
        $messages = [];

        foreach ($this->config->subreddits as $subreddit) {
            // TODO: fetch posts and comments

            // TODO: loop over posts and comments

            // TODO: if their content matches the keywords, add them to $messages
        }

        return $messages;
    }
}