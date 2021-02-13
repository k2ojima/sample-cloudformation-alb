<?php

namespace AlbCFn\Cli\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use AlbCFn\Cli\Command\Concerns\TargetArguments;
use AlbCFn\Cli\Command\Concerns\{StackFilename, StackParameter};

class DeleteStackCommand extends Command
{
    use StackFilename, StackParameter;

    protected static $defaultName = 'stack:delete';

    /** @var TargetArguments */
    protected $targetArguments;

    public function __construct(TargetArguments $targetArguments)
    {
        $this->targetArguments = $targetArguments;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('CloudFormationスタックを削除します');
        $this->targetArguments->decorate($this);
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
        $con = $this->targetArguments;
        $target = $input->getArgument('target');
        if (!$con->inTargetValues($target)) {
            $output->writeln('<error>targetが不正な値です</error>');
            return 1;
        }

        $output->writeln("<info>Starting to delete $target stack..</info>");
        $execAll = $con->isAllTarget($target);
        try {
            if ($con->isNetTarget($target) || $execAll) {
                $netProcess = $this->netStackProcess();
                $netProcess->mustRun();
                echo $netProcess->getOutput();
            }
            if ($con->isEc2Target($target) || $execAll) {
                $ec2Process = $this->ec2StackProcess();
                $ec2Process->mustRun();
                echo $ec2Process->getOutput();
            }
            if ($con->isCloudwatchTarget($target) || $execAll) {
                $cwProcess = $this->cloudwatchStackProcess();
                $cwProcess->mustRun();
                echo $cwProcess->getOutput();
            }
            if ($con->isPipelineTarget($target) || $execAll) {
                $cpProcess = $this->pipelineStackProcess();
                $cpProcess->mustRun();
                echo $cpProcess->getOutput();
            }
        } catch (ProcessFailedException $exception) {
            echo $exception->getMessage();
            return 1;
        }
        return 0;
    }

    /**
     * ネットワークのスタック削除Process
     * @return Process
     */
    protected function netStackProcess() {
        return new Process([
            'aws', 'cloudformation', 'delete-stack',
            '--stack-name', $_ENV['StackPrefix'].$_ENV['NetStack'],
        ]);
    }
    /**
     * EC2のスタック削除Process
     * @return Process
     */
    protected function ec2StackProcess() {
        return new Process([
            'aws', 'cloudformation', 'delete-stack',
            '--stack-name', $_ENV['StackPrefix'].$_ENV['EC2Stack'].'-'.$_ENV['EnvType'],
        ]);
    }
    /**
     * CloudWatchのスタック削除Process
     * @return Process
     */
    protected function cloudwatchStackProcess() {
        return new Process([
            'aws', 'cloudformation', 'delete-stack',
            '--stack-name', $_ENV['StackPrefix'].$_ENV['CWStack'].'-'.$_ENV['EnvType'],
        ]);
    }
    /**
     * CodePipelineのスタック削除Process
     * @return Process
     */
    protected function pipelineStackProcess() {
        return new Process([
            'aws', 'cloudformation', 'delete-stack',
            '--stack-name', $_ENV['StackPrefix'].$_ENV['CodePipelineStack'].'-'.$_ENV['EnvType'],
        ]);
    }
}
