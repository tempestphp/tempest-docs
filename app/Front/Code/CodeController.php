<?php

declare(strict_types=1);

namespace App\Front\Code;

use Tempest\Container\Tag;
use Tempest\Highlight\Highlighter;
use Tempest\Router\Get;
use Tempest\Router\Post;
use Tempest\Router\Request;
use Tempest\Router\Responses\Redirect;
use Tempest\View\View;

use function Tempest\uri;
use function Tempest\view;

final readonly class CodeController
{
    #[Get('/code')]
    public function paste(Request $request): View
    {
        $language = $request->get('lang') ?? 'php';
        $code = $request->get('code') ?? '';

        if ($code) {
            $code = urldecode(base64_decode($code, strict: true));
        }

        return view(__DIR__ . '/code.view.php', code: $code, language: $language);
    }

    #[Post('/code/submit')]
    public function submit(Request $request): Redirect
    {
        $code = $request->get('code');

        $language = $request->get('lang', 'php');

        $code = urlencode(base64_encode($code));

        return new Redirect(uri([self::class, 'preview']) . '?lang=' . $language . '&code=' . $code);
    }

    #[Get('/code/preview')]
    public function preview(
        Request $request,
        #[Tag('project')]
        Highlighter $highlighter,
    ): View {
        $code = $request->get('code') ?? urlencode(base64_encode('// Hello world'));

        $language = $request->get('lang') ?? 'php';

        $editUrl = uri([self::class, 'paste'], lang: $language, code: $code);

        $highlightedCode = $highlighter->parse(urldecode(base64_decode($code, strict: true)), $language);

        return view(__DIR__ . '/code_preview.view.php')->data(
            code: $highlightedCode,
            editUrl: $editUrl,
            language: $language,
        );
    }
}
