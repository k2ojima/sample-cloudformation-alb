<?php

namespace AlbCFn\Cli\Command\Equipment;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use AlbCFn\Cli\Command\Concerns\SubOptions;
use AlbCFn\Cli\Command\Concerns\{StackFilename, StackParameter};

class InitSSMStackCommand extends Command
{
    use StackFilename, StackParameter;

    protected static $defaultName = 'stack:init_ssm';

    /** @var SubOptions */
    protected $option;

    /** @var string */
    protected $cfnStackName = '';

    public function __construct(SubOptions $option)
    {
        $this->option = $option;
        parent::__construct();

        $this->cfnStackName = $_ENV['StackPrefix'].$_ENV['SSMStack'].'-'.$_ENV['EnvType'];
    }

    protected function configure()
    {
        $this->setDescription('SSMパラメータのスタックを作成します');
        $this->option->decorate($this);
    }

    /**
     * コマンドを実行
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            if ($input->getOption('update')) {
                $output->writeln("<info>Starting to update stack..</info>");
                $process = $this->stackUpdateProcess();
            }
            elseif ($input->getOption('delete')) {
                $output->writeln("<info>Starting to delete stack..</info>");
                $process = $this->stackDeleteProcess();
            }
            else {
                $output->writeln("<info>Starting to create stack..</info>");
                $process = $this->stackCreateProcess();
            }
            $process->mustRun();
            echo $process->getOutput();
        } catch (ProcessFailedException $exception) {
            echo $exception->getMessage();
            return 1;
        }
        return 0;
    }

    /**
     * スタック作成Process
     * @return Process
     */
    protected function stackCreateProcess() {
        return new Process([
            'aws', 'cloudformation', 'create-stack',
            '--stack-name', $this->cfnStackName,
            '--region', $_ENV['Region'],
            '--template-body', $this->getSsmStackName(),
            '--parameters', $this->getEnvTypeParam(), $this->getSsmStackParam(),
        ]);
    }
    /**
     * スタック更新Process
     * @return Process
     */
    protected function stackUpdateProcess() {
        return new Process([
            'aws', 'cloudformation', 'update-stack',
            '--stack-name', $this->cfnStackName,
            '--region', $_ENV['Region'],
            '--template-body', $this->getSsmStackName(),
            '--parameters', $this->getEnvTypeParam(),  $this->getSsmStackParam(),
        ]);
    }
    /**
     * スタック削除Process
     * @return Process
     */
    protected function stackDeleteProcess() {
        return new Process([
            'aws', 'cloudformation', 'delete-stack',
            '--stack-name', $this->cfnStackName,
        ]);
    }
}
