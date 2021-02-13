#!/bin/bash
# スタックのリージョン
Region=ap-northeast-1
# サイトが存在するディレクトリ
Destination=/var/www/html
# CFnスタック名のプレフィックス。
StackPrefix=Xentok
# インストールしてあるNode.js のバージョン
NODEJS_VER=latest

SSMName="${StackPrefix}SSM-DotenvConfig"
if [[ $DEPLOYMENT_GROUP_NAME =~ ^.*test.*$ ]]; then
    SSMNameEnv=${SSMName}-test
elif [[ $DEPLOYMENT_GROUP_NAME =~ ^.*stage.*$ ]]; then
    SSMNameEnv=${SSMName}-stage
else
    SSMNameEnv=${SSMName}-prod
fi

echo "DEBUG: Total Swap: $(free -hm | grep Swap | awk '{print $2}')"
echo "DEBUG: Used Swap: $(free -hm | grep Swap | awk '{print $3}')"

export COMPOSER_HOME="$HOME/.config/composer"
cd $Destination && composer install

export $PATH=$PATH:$HOME/.nvm/versions/node/$NODEJS_VER/bin
export NVM_DIR="$HOME/.nvm"
[ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh"
cd $Destination && npm install

aws --region=${Region} ssm get-parameter --name ${SSMNameEnv} --with-decryption --output text --query Parameter.Value > $Destination/.env

php $Destination/artisan optimize
php $Destination/artisan migrate

cd $Destination && npm run dev