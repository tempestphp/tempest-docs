<?php

use Tempest\Router\MatchedRoute;

use function Tempest\get;
use function Tempest\uri;

function is_uri(array $action, mixed ...$params): bool
{
    $matchedRoute = get(MatchedRoute::class);
    $candidateUri = uri($action, ...[...$matchedRoute->params, ...$params]);
    $currentUri = uri($matchedRoute->route->handler);

    foreach ($matchedRoute->params as $key => $value) {
        $currentUri = str_replace("{{$key}}", $value, $currentUri);
    }

    return $currentUri === $candidateUri;
}

function recursive_search(string $folder, string $pattern): Generator
{
    $directory = new RecursiveDirectoryIterator($folder);
    $iterator = new RecursiveIteratorIterator($directory);
    $files = new RegexIterator($iterator, $pattern, RegexIterator::MATCH);

    foreach ($files as $file) {
        yield $file->getPathName();
    }
}
