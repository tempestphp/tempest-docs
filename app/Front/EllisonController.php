<?php

declare(strict_types=1);

namespace App\Front;

use Tempest\Highlight\Highlighter;
use Tempest\Highlight\Themes\CssTheme;
use Tempest\Http\Get;
use Tempest\Http\Post;
use Tempest\Http\Request;
use Tempest\Http\Response;

use function Tempest\redirect;
use function Tempest\view;

use Tempest\View\View;

final readonly class EllisonController
{
    #[Get('/ellison')]
    public function paste(): View
    {
        return view('Front/ellison.view.php');
    }

    #[Post('/ellison')]
    public function submit(Request $request): Response
    {
        $ellison = trim($request->get('ellison'));

        return redirect([self::class, 'preview'])->addSession('ellison', base64_encode($ellison));
    }

    #[Get('/ellison/preview')]
    public function preview(
        Request $request,
    ): View {
        $highlighter = new Highlighter(new CssTheme());

        $ellison = $highlighter->parse(base64_decode($request->getSessionValue('ellison') ?? base64_encode('Hello World')), 'ellison');

        return view('Front/ellison_preview.view.php')->data(ellison: $ellison);
    }
}
