
# .env の設定項目について
`.env`の設定値については下記を参考にしてください。

| Key | 設定するValueについて |
| ----------------------- | ---------------------------------- |
| EnvType             | test, stage, prod のどれかとなります。こちらはALBやCodePipelineの名前など様々な場所で利用されますので、必ずテスト用(test)・ステージング用(stage)・本番用(prod)で切り分けるようにしてください。 **値がprodの時だけALBのEC2 3台構成となります。それ以外の値だとALBのEC2は1台しか作成されません。**        |
| Region             | デフォルトは東京リージョンです      |
| SiteDomain             | ドメインを設定してください。ACM発行の際に利用されます     |
| WebsiteDocumentRoot             | サイトのドキュメントルート。Laravelの場合はこのままで大丈夫です。サイトに応じて変更してください     |
| StackPrefix             | サンプル値になっていますが、環境ごとに値を変えてください。この値はCFnのスタック名などに使用されます。環境によって名前を変えることで影響が及ばないようにします。        |
| CWStack             | CloudWatch用CFnスタックの名前。`StackPrefix`が名前のプレフィックスに付くので、基本このままで大丈夫です |
| EC2Stack             | EC2, ALB用CFnスタックの名前。`StackPrefix`が名前のプレフィックスに付くので、基本このままで大丈夫です   |
| NetStack             | VPCなどネットワーク用CFnスタックの名前。`StackPrefix`が名前のプレフィックスに付くので、基本このままで大丈夫です  |
| CodePipelineStack             | CodePipeline用CFnスタックの名前。`StackPrefix`が名前のプレフィックスに付くので、基本このままで大丈夫です   |
| SSMStack             | SSMパラメータ用CFnスタックの名前。`StackPrefix`が名前のプレフィックスに付くので、基本このままで大丈夫です   |
| KeyName             | EC2インスタンス一覧に表示されている、キー名を設定します。   |
| InstanceType             |  インスタンスタイプを指定。何も指定しないと`t2.micro`になります。Webpackを利用する場合、メモリ4GB以上推奨です。スペックが低すぎるとビルド失敗します。   |
| ProvisionalAmiId             |  何も設定しないとAmazon Linux 2 のAMIを取得します   |
| LatestAmiId             | ここで設定したAMIのIDからALBのターゲットとなるEC2が作成されます。 |
| CreateDatabase             | MySQLデータベースを作成するかどうかを true/falseで設定。テスト環境などでRDS必要ない場合はtrueで良いですが、本番環境はfalseにしてRDS使うことを推奨します   |
| DbRootPassword             | MySQLにrootユーザーで接続する時のパスワードです   |
| DbName             | MySQLで作成するデータベース名   |
| DbCharSet             | MySQLデータベースの文字コード   |
| DbCollate             | MySQLデータベースの照合順序   |
| ArtifactBucketName             | CodeDeploy用S3 bucket。名前は何でもいいですが、環境によって変えてください。小文字・ハイフンのみ許可   |
| GitHubOwner             |  例えばリポジトリのURLが github.com/xentok/sample-app.git  の場合、`xentok` と設定します   |
| GitHubRepo             | 例えばリポジトリのURLが github.com/xentok/sample-app.git  の場合、`sample-app` と設定します    |
| GitHubBranch             | デプロイを発火させるブランチ名です（ここで設定したブランチ名にプッシュされた時に発火）             |
| GitHubOAuthToken             |  GitHubアカウントの設定で、OAuthトークンを発行する必要があります。発行したトークンをこちらに設定します。<br>詳細は下記を参照してください。<br>https://docs.aws.amazon.com/ja_jp/codepipeline/latest/userguide/GitHub-authentication.html  |
| GitHubSecretToken             | 任意のトークンを設定しておきます。英数字で設定します。安全のためランダムな12文字以上推奨です    |
