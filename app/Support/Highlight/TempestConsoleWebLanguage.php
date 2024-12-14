<?php

declare(strict_types=1);

namespace App\Support\Highlight;

use App\Support\Highlight\Injections\CommentInjection;
use App\Support\Highlight\Injections\EmphasizeInjection;
use App\Support\Highlight\Injections\ErrorInjection;
use App\Support\Highlight\Injections\H1Injection;
use App\Support\Highlight\Injections\H2Injection;
use App\Support\Highlight\Injections\QuestionInjection;
use App\Support\Highlight\Injections\StrongInjection;
use App\Support\Highlight\Injections\SuccessInjection;
use App\Support\Highlight\Injections\UnderlineInjection;
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