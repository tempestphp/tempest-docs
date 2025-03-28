<?php

namespace App;

use Tempest\Console\ConsoleCommand;
use Tempest\Console\HasConsole;
use Tempest\Database\Query;
use Tempest\Database\QueryStatements\AlterTableStatement;
use Tempest\Database\QueryStatements\VarcharStatement;

final class FixMigrationsCommand
{
    use HasConsole;

    #[ConsoleCommand]
    public function __invoke(): void
    {
        $statement = new AlterTableStatement('migrations')
            ->add(new VarcharStatement(name: 'hash', size: 32, default: ''));

        new Query($statement)->execute();

        $this->console->success('Done');
    }
}