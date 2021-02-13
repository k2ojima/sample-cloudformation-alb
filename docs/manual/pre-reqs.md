
# 前提条件

## 必要なツール
下記が必要ですので、あらかじめインストールしてください。

- AWS CLI（バージョン問わない）
- PHP（7.3以上）

## AWS CLI
あらかじめAWS CLIの初期設定（`$ aws configure` コマンド）でアクセスキー、シークレットキー、リージョンの設定をしてください。
https://docs.aws.amazon.com/ja_jp/cli/latest/userguide/cli-chap-configure.html

## 利用するユーザーのIAM設定
AWS CLIで操作するユーザーにIAMの許可設定が必要です。

### 必要な管理ポリシー
下記の管理ポリシーは最低でも必要です。（環境によっては他にも必要なポリシーがある可能性あり）
下記のポリシーは、利用しているユーザー(AWS CLIで操作しているユーザー)のロールにアタッチしてください。

※Resourceを限定したい場合は、AWSのポリシーではなく、カスタムポリシー作成する形になります。

- AWSCloudFormationFullAccess
- S3FullAccess
- CodePipelineFullAccess
- CodeDeployFullAccess
- EC2FullAccess

### IAMを操作するポリシードキュメント
下記の内容でカスタムポリシーを作成し、利用しているユーザー(CLIで操作しているユーザー)にアタッチしてください。
※こちらは設定例ですので、`Resource`のARNは適宜修正してください（もう少し限定した方が良いです）

```json
{
    "Version": "2012-10-17",
    "Statement": [
        {
            "Sid": "EditSpecificServiceRole",
            "Effect": "Allow",
            "Action": [
                "iam:AttachRolePolicy",
                "iam:DeleteRolePolicy",
                "iam:DetachRolePolicy",
                "iam:GetRole",
                "iam:GetRolePolicy",
                "iam:ListAttachedRolePolicies",
                "iam:ListRolePolicies",
                "iam:PutRolePolicy",
                "iam:UpdateRole",
                "iam:UpdateRoleDescription"
            ],
            "Resource": "arn:aws:iam::*:role/*"
        },
        {
            "Sid": "ViewRolesAndPolicies",
            "Effect": "Allow",
            "Action": [
                "iam:GetPolicy",
                "iam:ListRoles"
            ],
            "Resource": ""
        }
    ]
}
```


### 信頼ポリシードキュメントの設定
ロールの信頼ポリシー(信頼関係タブ)設定内容は下記のようになります。

```json
{
  "Version": "2012-10-17",
  "Statement": {
    "Effect": "Allow",
    "Principal": {"Service": "cloudformation.amazonaws.com"},
    "Action": "sts:AssumeRole"
  }
}
```
