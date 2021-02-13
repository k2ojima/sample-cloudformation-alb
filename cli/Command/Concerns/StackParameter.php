<?php

namespace AlbCFn\Cli\Command\Concerns;

/**
 * このトレイトでは、コマンドでCFnのスタックへ渡すパラメータを定義している
 */
trait StackParameter
{
    public function getEnvTypeParam()
    {
        return 'ParameterKey=EnvType,ParameterValue=' . $_ENV['EnvType'];
    }
    public function getSsmStackParam()
    {
        return 'ParameterKey=StackPrefix,ParameterValue=' . $_ENV['StackPrefix'];
    }

    public function getCloudwatchParameter()
    {
        return [
            $this->getEnvTypeParam(),
        ];
    }
    public function getEc2Parameter()
    {
        return array_filter(
            [
                $this->getEnvTypeParam(),
                empty($_ENV['InstanceType']) ? null : 'ParameterKey=InstanceType,ParameterValue=' . $_ENV['InstanceType'],
                empty($_ENV['KeyName']) ? null : 'ParameterKey=KeyName,ParameterValue=' . $_ENV['KeyName'],
                'ParameterKey=LatestAmiId,ParameterValue=' . $_ENV['LatestAmiId'],
                'ParameterKey=NetStack,ParameterValue=' . $_ENV['StackPrefix'].$_ENV['NetStack'],
            ]
        );
    }
    public function getProvisionalEc2Parameter()
    {
        return array_filter(
            [
                $this->getEnvTypeParam(),
                'ParameterKey=WebsiteDocumentRoot,ParameterValue=' . $_ENV['WebsiteDocumentRoot'],
                'ParameterKey=KeyName,ParameterValue=' . $_ENV['KeyName'],
                empty($_ENV['InstanceType']) ? null : 'ParameterKey=InstanceType,ParameterValue=' . $_ENV['InstanceType'],
                empty($_ENV['ProvisionalAmiId']) ? null : 'ParameterKey=ProvisionalAmiId,ParameterValue=' . $_ENV['ProvisionalAmiId'],
                'ParameterKey=CreateDatabase,ParameterValue=' . $_ENV['CreateDatabase'],
                'ParameterKey=DbRootPassword,ParameterValue=' . $_ENV['DbRootPassword'],
                'ParameterKey=DbName,ParameterValue=' . $_ENV['DbName'],
                'ParameterKey=DbCharSet,ParameterValue=' . $_ENV['DbCharSet'],
                'ParameterKey=DbCollate,ParameterValue=' . $_ENV['DbCollate'],
                'ParameterKey=CWStack,ParameterValue=' . $_ENV['StackPrefix'].$_ENV['CWStack'],
                'ParameterKey=NetStack,ParameterValue=' . $_ENV['StackPrefix'].$_ENV['NetStack'],
            ]
        );
    }
    public function getNetParameter()
    {
        return [
            'ParameterKey=SiteDomain,ParameterValue=' . $_ENV['SiteDomain'],
        ];
    }
    public function getCodePipelineParameter()
    {
        return [
            $this->getEnvTypeParam(),
            'ParameterKey=EC2Stack,ParameterValue=' . $_ENV['StackPrefix'].$_ENV['EC2Stack'].'-'.$_ENV['EnvType'],
            'ParameterKey=ArtifactBucketName,ParameterValue=' . $_ENV['ArtifactBucketName'],
            'ParameterKey=GitHubOwner,ParameterValue=' . $_ENV['GitHubOwner'],
            'ParameterKey=GitHubRepo,ParameterValue=' . $_ENV['GitHubRepo'],
            'ParameterKey=GitHubBranch,ParameterValue=' . $_ENV['GitHubBranch'],
            'ParameterKey=GitHubOAuthToken,ParameterValue=' . $_ENV['GitHubOAuthToken'],
            'ParameterKey=GitHubSecretToken,ParameterValue=' . $_ENV['GitHubSecretToken'],
        ];
    }
}
