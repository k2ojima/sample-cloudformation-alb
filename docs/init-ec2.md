
# AMI構築方法

## CloudWatchとネットワーク周りの構築
**EC2を作成する前に、CloudWatchおよびVPC等の作成が必要です。**
こちらが作成されていないと、EC2の生成時にエラーとなります。

下記を実行するだけで大丈夫です。

```bash
$ php cli/run stack:create cloudwatch
$ php cli/run stack:create net
```

## 初期インスタンスの作成

* 現状、AMIだけコンソール上で作成しています。
    * TODO: PackerによるAMIのコード化
* AMI作るベースのインスタンスはCFnで管理しています。

下記を実行してAMIに使う用のインスタンスを作成します。こちらを実行するだけで、一通り揃ったLAMP環境のEC2インスタンスが出来上がります。

```bash
$ php cli/run stack:prepare_ec2
```

## AMI作成
コンソールでAMIを作成し、AMIのIDを`.env`の`LatestAmiId`に設定しておきます。

AMIの作成が終わったら、スタックは削除してください。`-D`オプションで削除です。

```bash
$ php cli/run stack:prepare_ec2 -D
```