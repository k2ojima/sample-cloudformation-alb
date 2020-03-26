# CloudFormation ALB Sample
ALB template through CloudFormation.

## Requirements
AWS CLI

## Setup
### Create stack

```bash
$ aws cloudformation create-stack --stack-name test-stack --region ap-northeast-1 --template-body file://services.cf.yaml \
--parameters ParameterKey=SiteDomain,ParameterValue=example.com
```
