<?php

namespace AlbCFn\Cli\Command\Concerns;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;

class SubOptions
{
    /**
     * Add options for operating stacks
     *
     * @param Command $visitor
     * @return void
     */
    public function decorate(Command &$visitor)
    {
        $visitor->addOption(
            'update', 'U', InputOption::VALUE_NONE, "このオプションを指定した場合、作成ではなく更新を行います"
        );
        $visitor->addOption(
            'delete', 'D', InputOption::VALUE_NONE, "このオプションを指定した場合、スタックを削除します"
        );
    }
}
