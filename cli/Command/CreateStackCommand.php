<?php

namespace AlbCFn\Cli\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use AlbCFn\Cli\Command\Concerns\TargetArguments;
use AlbCFn\Cli\Command\Concerns\{StackFilename, StackParameter};

class CreateStackCommand extends Command
{
    use StackFilename, StackParameter;

    protected static $defaultName = 'stack:create';

    /** @var TargetArguments */
    protected $targetArguments;

    public function __construct(TargetArguments $targetArguments)
    {
        $this->targetArguments = $targetArguments;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('CloudFormationスタックを作成します');
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

        $output->writeln("<info>Starting to create $target stack..</info>");
        $execAll = $con->isAllTarget($target);
        try {
            if ($con->isNetTarget($target) || $execAll) {
                $netProcess = $this->netStackProcess();
                $netProcess->mustRun();
                echo $netProcess->getOutput();
            }
            if ($con->isCloudwatchTarget($target) || $execAll) {
                $cwProcess = $this->cloudwatchStackProcess();
                $cwProcess->mustRun();
                echo $cwProcess->getOutput();
            }
            if ($con->isEc2Target($target) || $execAll) {
                $ec2Process = $this->ec2StackProcess();
                $ec2Process->mustRun();
                echo $ec2Process->getOutput();
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
     * ネットワークのスタック作成Process
     * @return Process
     */
    protected function netStackProcess() {
        return new Process(
            array_merge([
                'aws', 'cloudformation', 'create-stack',
                '--stack-name', $_ENV['StackPrefix'].$_ENV['NetStack'],
                '--region', $_ENV['Region'],
                '--template-body', $this->getNetName(),
                '--parameters',
            ], $this->getNetParameter())
        );
    }
    /**
     * EC2のスタック作成Process
     * @return Process
     */
    protected function ec2StackProcess() {
        return new Process(
            array_merge(
                [
                    'aws', 'cloudformation', 'create-stack',
                    '--stack-name', $_ENV['StackPrefix'].$_ENV['EC2Stack'].'-'.$_ENV['EnvType'],
                    '--region', $_ENV['Region'],
                    '--capabilities', 'CAPABILITY_NAMED_IAM',
                    '--template-body', $this->getEc2Name(),
                    '--parameters',
                ], $this->getEc2Parameter()
            )
        );
    }
    /**
     * CloudWatchのスタック作成Process
     * @return Process
     */
    protected function cloudwatchStackProcess() {
        return new Process(
            array_merge(
                [
                    'aws', 'cloudformation', 'create-stack',
                    '--stack-name', $_ENV['StackPrefix'].$_ENV['CWStack'].'-'.$_ENV['EnvType'],
                    '--region', $_ENV['Region'],
                    '--template-body', $this->getCloudwatchName(),
                    '--parameters',
                ], $this->getCloudwatchParameter()
            )
        );
    }
    /**
     * CodePipelineのスタック作成Process
     * @return Process
     */
    protected function pipelineStackProcess() {
        return new Process(
            array_merge(
                [
                    'aws', 'cloudformation', 'create-stack',
                    '--stack-name', $_ENV['StackPrefix'].$_ENV['CodePipelineStack'].'-'.$_ENV['EnvType'],
                    '--region', $_ENV['Region'],
                    '--capabilities', 'CAPABILITY_NAMED_IAM',
                    '--template-body', $this->getCodePipelineName(),
                    '--parameters',
                ], $this->getCodePipelineParameter()
            )
        );
    }
}
