<?php

namespace AlbCFn\Cli\Command\Concerns;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;

class TargetArguments
{
    protected static $target_values = [
        'all',
        'cloudwatch',
        'ec2',
        'net',
        'pipeline',
    ];

    /**
     * Add argument for operating stacks
     *
     * @param Command $visitor
     * @return void
     */
    public function decorate(Command &$visitor)
    {
        $visitor->addArgument('target', InputArgument::REQUIRED, 'スタック更新対象を指定します');
    }

    /**
     * Check the input target name
     *
     * @param string $input_target
     * @return bool
     */
    public function inTargetValues($input_target)
    {
        return in_array($input_target, static::$target_values, true);
    }

    public function isCloudwatchTarget($input_target)
    {
        return $input_target === static::$target_values[1];
    }
    public function isEc2Target($input_target)
    {
        return $input_target === static::$target_values[2];
    }
    public function isNetTarget($input_target)
    {
        return $input_target === static::$target_values[3];
    }
    public function isPipelineTarget($input_target)
    {
        return $input_target === static::$target_values[4];
    }
    public function isAllTarget($input_target)
    {
        return $input_target === static::$target_values[0];
    }
}
