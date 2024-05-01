<?php

namespace App\Front;

use League\CommonMark\MarkdownConverter;
use Spatie\Browsershot\Browsershot;
use Tempest\AppConfig;
use Tempest\Highlight\Highlighter;
use Tempest\Highlight\Themes\CssTheme;
use Tempest\Http\Get;
use Tempest\Http\Post;
use Tempest\Http\Request;
use Tempest\Http\Response;
use Tempest\View\View;
use function Tempest\redirect;
use function Tempest\response;
use function Tempest\view;

final readonly class ScreenshotController
{
    #[Get('/code')]
    public function paste(): View
    {
        return view('Front/screenshot.view.php');
    }

    #[Post('/screenshot')]
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
            $code = $highlighter->parse(base64_decode($request->getSessionValue('code') ?? base64_encode('// Hello World')), 'php');
        }

        return view('Front/code_download_code.view.php')->data(code: $code);
    }

    #[Get('/code/download')]
    public function download(Request $request, Highlighter $highlighter, AppConfig $appConfig): Response
    {
        $code = base64_decode($request->getSessionValue('code') ?? base64_encode('// Hello World'));

        $format = $request->getQuery()['format'] ?? null;

        $view = match($format) {
            'image' => view('Front/code_download_image.view.php'),
            default => view('Front/code_download_code.view.php'),
        };

        $html = $view->data(code: $highlighter->parse($code, 'php'))->render($appConfig);

        $base64 = Browsershot::html($html)
            ->windowSize(1920, 1080)
            ->deviceScaleFactor(2)
            ->base64Screenshot();

        return response(base64_decode($base64))
            ->addHeader('Content-Type', 'image/png')
            ->addHeader('Content-Disposition', 'attachment')
            ->addHeader('filename', 'screenshot.png');
    }
}