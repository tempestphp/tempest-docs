<?php

declare(strict_types=1);

namespace App\Highlight;

use App\Highlight\Injections\CommentInjection;
use App\Highlight\Injections\EmphasizeInjection;
use App\Highlight\Injections\ErrorInjection;
use App\Highlight\Injections\H1Injection;
use App\Highlight\Injections\H2Injection;
use App\Highlight\Injections\QuestionInjection;
use App\Highlight\Injections\StrongInjection;
use App\Highlight\Injections\SuccessInjection;
use App\Highlight\Injections\UnderlineInjection;
use Tempest\Highlight\Language;

final readonly class TempestConsoleWebLanguage implements Language
{
    public function getName(): string
    {
        return 'console';
    }

    public function getAliases(): array
    {
        return [];
    }

    public function getInjections(): array
    {
        return [
            new QuestionInjection(),
            new EmphasizeInjection(),
            new StrongInjection(),
            new UnderlineInjection(),
            new ErrorInjection(),
            new CommentInjection(),
            new H1Injection(),
            new H2Injection(),
            new SuccessInjection(),
        ];
    }

    public function getPatterns(): array
    {
        return [];
    }
}
