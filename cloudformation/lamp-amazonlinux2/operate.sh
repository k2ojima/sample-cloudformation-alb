#!/bin/bash
set -be

#
# Helpers
#
arrayExists() {
	local searchKey="$1"
	shift
	local arr=("$@")
	for item in "${arr[@]}"; do
		if [[ "$item" == "$searchKey" ]]; then
			return 0
		fi
	done
	return 1
}

# operation map
operationsMap=(create update delete)
targetMap=(all core cloudwatch ec2 deploy)

argOperation="$1"
argTarget="$2"

if arrayExists "$argOperation" "${operationsMap[@]}"; then
	if ! arrayExists "$argTarget" "${targetMap[@]}"; then
		echo "Second argument must be either of ${targetMap[@]}"
		exit
	fi

	if [[ "$argOperation" == "${operationsMap[0]}" ]]; then
		echo "creating stack is not supported yet."
		exit

	elif [[ "$argOperation" == "${operationsMap[1]}" ]]; then
		echo updating..

		if [[ "$argTarget" == "${targetMap[0]}" ]] || [[ "$argTarget" == "${targetMap[4]}" ]]; then
		aws cloudformation update-stack --stack-name DeployStack --capabilities CAPABILITY_IAM CAPABILITY_NAMED_IAM \
		--template-body file://deployments.cf.yaml \
		--parameters ParameterKey=EnvType,UsePreviousValue=true \
			ParameterKey=ArtifactBucketName,UsePreviousValue=true \
			ParameterKey=GitHubOwner,UsePreviousValue=true \
			ParameterKey=GitHubRepo,UsePreviousValue=true \
			ParameterKey=GitHubBranch,UsePreviousValue=true \
			ParameterKey=GitHubOAuthToken,UsePreviousValue=true \
			ParameterKey=GitHubSecretToken,UsePreviousValue=true
		fi
		if [[ "$argTarget" == "${targetMap[0]}" ]] || [[ "$argTarget" == "${targetMap[3]}" ]]; then
		aws cloudformation update-stack --stack-name Ec2ApStack \
		--capabilities CAPABILITY_NAMED_IAM \
		--template-body file://ec2.cf.yaml \
		--parameters ParameterKey=EnvType,UsePreviousValue=true \
			ParameterKey=DbRootPassword,UsePreviousValue=true
		fi
		if [[ "$argTarget" == "${targetMap[0]}" ]] || [[ "$argTarget" == "${targetMap[2]}" ]]; then
		aws cloudformation update-stack --stack-name CWApStack \
		--template-body file://cloudwatch.cf.yaml \
		--parameters ParameterKey=EnvType,UsePreviousValue=true
		fi
		if [[ "$argTarget" == "${targetMap[0]}" ]] || [[ "$argTarget" == "${targetMap[1]}" ]]; then
		aws cloudformation update-stack --stack-name CoreApStack --capabilities CAPABILITY_IAM \
		--template-body file://core.cf.yaml \
		--parameters ParameterKey=SiteDomain,UsePreviousValue=true
		fi
	elif [[ "$argOperation" == "${operationsMap[2]}" ]]; then
		echo deleting..
		if [[ "$argTarget" == "${targetMap[0]}" ]] || [[ "$argTarget" == "${targetMap[4]}" ]]; then
			aws cloudformation delete-stack --stack-name DeployStack
		fi
		if [[ "$argTarget" == "${targetMap[0]}" ]] || [[ "$argTarget" == "${targetMap[3]}" ]]; then
			aws cloudformation delete-stack --stack-name Ec2ApStack
		fi
		if [[ "$argTarget" == "${targetMap[0]}" ]] || [[ "$argTarget" == "${targetMap[2]}" ]]; then
			aws cloudformation delete-stack --stack-name CWApStack
		fi
		if [[ "$argTarget" == "${targetMap[0]}" ]] || [[ "$argTarget" == "${targetMap[1]}" ]]; then
			aws cloudformation delete-stack --stack-name CoreApStack
		fi
	fi
else
	echo "First argument must be either of ${operationsMap[@]}"
	exit
fi
