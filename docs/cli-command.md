
# スタック操作コマンドについて
各リソース（EC2やCloudWatchなど）の操作は、CFnを通して行います。

各コマンドの一覧は`list`で見れます。

```bash
$ php cli/run list
```


## 📝 Webサイト関連の設定スタック
SSMの設定です。現状はサイト側にある`.env`の内容を保管しています。

```bash
$ php cli/run stack:init_ssm
```

設定を変更して更新する場合は、`-U`オプションを付けてください。
削除するときは、`-D`オプションです。

```bash
$ php cli/run stack:init_ssm -U
```

## 📝 スタック作成
下記を実行することでスタックを作成します。
`<target>` には、`all` `cloudwatch` `ec2` `net` `pipeline`のいずれかを指定します。
最初の作成時は、`all`で大丈夫です

```bash
$ php cli/run stack:create <target>
```

## 📝 スタック更新
下記を実行することで、既存のスタックを更新します。
cloudformation/ フォルダ内にあるyamlを編集した後は、必ずこちらのコマンドで更新を行ってください。

`<target>` には、`all` `cloudwatch` `ec2` `net` `pipeline`のいずれかを指定します。
例えば`ec2` をtargetにするとEC2関連だけが更新されるようになります。

```bash
$ php cli/run stack:update <target>
```

## 📝 スタック削除
下記を実行することで、スタックを削除します。関連するリソースは削除されますので、実行前に削除して問題ないか確認してください。
`<target>` には、`all` `cloudwatch` `ec2` `net` `pipeline`のいずれかを指定します。

```bash
$ php cli/run stack:delete <target>
```
