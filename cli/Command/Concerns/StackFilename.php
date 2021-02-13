<?php

namespace AlbCFn\Cli\Command\Concerns;

/**
 * このトレイトでは、コマンドでCFnを実行する時のCFnテンプレートファイルの場所（※ルートディレクトリにいる前提）を定義している
 */
trait StackFilename
{
    public function getCloudwatchName()
    {
        return 'file://cloudformation/cloudwatch.cf.yaml';
    }
    public function getEc2Name()
    {
        return 'file://cloudformation/ec2.cf.yaml';
    }
    public function getProvisionalEc2Name()
    {
        return 'file://cloudformation/init-ec2.cf.yaml';
    }
    public function getNetName()
    {
        return 'file://cloudformation/net.cf.yaml';
    }
    public function getCodePipelineName()
    {
        return 'file://cloudformation/codepipeline.cf.yaml';
    }
    public function getSsmStackName()
    {
        return 'file://cloudformation/ssm-params.cf.yaml';
    }
    public function getLaravelSsmName()
    {
        return 'file://cloudformation/laravel.cf.yaml';
    }
}
