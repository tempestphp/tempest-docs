<?php

use App\Markdown\MarkdownPost;
use League\CommonMark\Extension\FrontMatter\Output\RenderedContentWithFrontMatter;
use League\CommonMark\MarkdownConverter;
use Tempest\View\View;
use function Tempest\get;
use function Tempest\map;
use function Tempest\view;

function recursive_search(string $folder, string $pattern): Generator
{
    $directory = new RecursiveDirectoryIterator($folder);
    $iterator = new RecursiveIteratorIterator($directory);
    $files = new RegexIterator($iterator, $pattern, RegexIterator::MATCH);

    foreach ($files as $file) {
        yield $file->getPathName();
    }
}

function markdown(string $file): View
{
    $markdown = get(MarkdownConverter::class);

    $content = $markdown->convert(file_get_contents($file));

    $data = [
        'content' => $content,
    ];

    if ($content instanceof RenderedContentWithFrontMatter) {
        $data = [...$data, ...$content->getFrontMatter()];
    }

    $post = map($data)->to(MarkdownPost::class);

    return view(__DIR__ . '/Web/markdown.view.php', post: $post);
}