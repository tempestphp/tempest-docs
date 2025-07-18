<?php

declare(strict_types=1);

namespace App\Web\Code;

use Tempest\Highlight\Highlighter;
use Tempest\Highlight\Themes\CssTheme;
use Tempest\Http\Request;
use Tempest\Http\Response;
use Tempest\Http\Responses\Redirect;
use Tempest\Router\Get;
use Tempest\Router\Post;
use Tempest\View\View;

use function Tempest\uri;
use function Tempest\view;

final readonly class EllisonController
{
    #[Get('/ellison')]
    public function paste(): View
    {
        return view(__DIR__ . '/ellison.view.php');
    }

    #[Post('/ellison')]
    public function submit(Request $request): Response
    {
        $ellison = trim($request->get('ellison'));

        return new Redirect(uri([self::class, 'preview']))->addSession('ellison', base64_encode($ellison));
    }

    #[Get('/ellison/preview')]
    public function preview(Request $request): View
    {
        $highlighter = new Highlighter(new CssTheme());

        $ellison = $highlighter->parse(base64_decode($request->getSessionValue('ellison') ?? base64_encode('Hello World'), strict: true), 'ellison');

        return view(__DIR__ . '/ellison_preview.view.php')->data(ellison: '<pre data-lang="ellison">' . $ellison .'</pre>');
    }
}
