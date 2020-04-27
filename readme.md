# CloudFormation ALB Sample
ALB template through CloudFormation.

## Requirements
AWS CLI

## Setup
### Create stack

```bash
$ aws cloudformation create-stack --stack-name CoreApStack --region ap-northeast-1 --capabilities CAPABILITY_IAM \
--template-body file://cloudformation/lamp-amazonlinux2/core.cf.yaml \
--parameters ParameterKey=SiteDomain,ParameterValue=example.com \
&& \
aws cloudformation create-stack --stack-name CWApStack --region ap-northeast-1 \
--template-body file://cloudformation/lamp-amazonlinux2/cloudwatch.cf.yaml \
--parameters ParameterKey=EnvType,ParameterValue=test
&& \
aws cloudformation create-stack --stack-name Ec2ApStack --region ap-northeast-1 \
--capabilities CAPABILITY_NAMED_IAM \
--template-body file://cloudformation/lamp-amazonlinux2/ec2.cf.yaml \
--parameters ParameterKey=EnvType,ParameterValue=test \
	ParameterKey=DbRootPassword,ParameterValue=passw0rd
&& \
aws cloudformation create-stack --stack-name DeployStack --region ap-northeast-1 --capabilities CAPABILITY_IAM CAPABILITY_NAMED_IAM \
--template-body file://cloudformation/lamp-amazonlinux2/deployments.cf.yaml \
--parameters ParameterKey=EnvType,ParameterValue=test \
	ParameterKey=ArtifactBucketName,ParameterValue=sample-artifact-bucket \
	ParameterKey=GitHubOwner,ParameterValue=owner \
	ParameterKey=GitHubRepo,ParameterValue=sample-code \
	ParameterKey=GitHubBranch,ParameterValue=staging \
	ParameterKey=GitHubOAuthToken,ParameterValue=your_token \
	ParameterKey=GitHubSecretToken,ParameterValue=your_token
```
