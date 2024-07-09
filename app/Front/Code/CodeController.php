<?php

declare(strict_types=1);

namespace App\Front\Code;

use League\CommonMark\MarkdownConverter;
use Tempest\Highlight\Highlighter;
use Tempest\Highlight\Themes\CssTheme;
use Tempest\Http\Get;
use Tempest\Http\Post;
use Tempest\Http\Request;
use Tempest\Http\Response;
use Tempest\View\View;
use function Tempest\redirect;
use function Tempest\view;

final readonly class CodeController
{
    #[Get('/code')]
    public function paste(): View
    {
        return view(__DIR__ . '/code.view.php');
    }

    #[Post('/code')]
    public function submit(Request $request): Response
    {
        $code = $request->get('code');

        return redirect([self::class, 'preview'])->addSession('code', base64_encode($code));
    }

    #[Get('/code/preview')]
    public function preview(
        Request $request,
        MarkdownConverter $markdown,
    ): View {
        $highlighter = new Highlighter(new CssTheme());

        if ($slide = $request->get('slide')) {
            $code = $markdown->convert(file_get_contents(__DIR__ . "/../Content/slides/{$slide}.md"))->getContent();
        } else {
            $code = $this->trim(base64_decode($request->getSessionValue('code') ?? base64_encode('// Hello World')));

            $code = $highlighter->parse($code, $request->get('lang') ?? 'php');
        }

        return view(__DIR__ . '/code_preview.view.php')->data(code: $code);
    }

    private function trim(string $code): string
    {
        preg_match_all('/^ */m', $code, $matches);

        if ($matches[0] === []) {
            return $code;
        }

        $indentation = min(array_map(
            fn (string $spaces) => strlen($spaces),
            $matches[0],
        ));

        return preg_replace('/^\s{' . $indentation . '}/m', '', $code);
    }
}
