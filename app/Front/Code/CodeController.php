<?php

declare(strict_types=1);

namespace App\Front\Code;

use League\CommonMark\MarkdownConverter;
use Tempest\Highlight\Highlighter;
use Tempest\Highlight\Themes\CssTheme;
use Tempest\Http\Get;
use Tempest\Http\Post;
use Tempest\Http\Request;
use Tempest\Http\Responses\Redirect;
use Tempest\View\View;
use function Tempest\uri;
use function Tempest\view;

final readonly class CodeController
{
    #[Get('/code')]
    public function paste(): View
    {
        return view(__DIR__ . '/code.view.php');
    }

    #[Post('/code/submit')]
    public function submit(Request $request): Redirect
    {
        $code = $request->get('code');

        $code = urlencode(base64_encode($code));

        return (new Redirect(uri([self::class, 'preview']) . '?code=' . $code));
    }

    #[Get('/code/preview')]
    public function preview(Request $request): View {
        $highlighter = new Highlighter(new CssTheme());

        $code = $request->get('code') ?? urlencode(base64_encode('// Hello world'));

        $language  = $request->get('lang') ?? 'php';

        $code = $highlighter->parse(urldecode(base64_decode($code)), $language);

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
