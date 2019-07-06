# cloud9-laravel-study

# 環境構築
1. GitHubの「Clone or download」から「Download ZIP」を押してZIPをダウンロード（masterブランチ）
1. AWS Cloud9のプロジェクトを作成（Amazon Linux版）
1. C9上で「File > Upload Local Files...」からアップロード
1. C9のターミナルで以下コマンドを叩いていく
    1. `unzip cloud9-laravel-study.zip`
    1. `mv cloud9-laravel-study-master hanly`
    1. `rm -rf cloud9-laravel-study.zip`
    1. `cd hanly`
    1. `make install DB_ROOT_PASS=**DBのパスワード**`
        1. 途中で「Enter password:」で止まるのでそのままEnter
        1. `curl http://169.254.169.254/latest/meta-data/public-ipv4` で表示されるIPをメモ
        1. `curl 169.254.169.254/latest/meta-data/instance-id/` で表示されるインスタンスIDをメモ
    1. `make up`
    1. `cd laravel`
    1. `composer install --prefer-dist --no-interaction`
    1. `cp -ip .env.example .env`
    1. `php artisan key:generate`
    1. [セキュリティグループを変更して外部公開](https://qiita.com/gomiryo/items/75ff20f820bea6ec81e6#%E3%82%BB%E3%82%AD%E3%83%A5%E3%83%AA%E3%83%86%E3%82%A3%E3%83%BC%E3%83%9D%E3%83%AA%E3%82%B7%E3%83%BC%E3%81%AE%E8%A8%AD%E5%AE%9A)
    1. メモしたIPでブラウザアクセスし、Laravelの画面が出ればとりあえずOK
1. SSL対応（C9のターミナルで以下コマンドを叩いていく）
    1. `sudo yum install -y mod24_ssl`
	1. `cd ~`
	1. `openssl genrsa -out server.key 2048`
	1. `openssl req -out server.csr -key server.key -new`
	1. 色々聞かれるので下記参考に
	    1. Country Name: `JP`
		1. State or Province Name: `Fukuoka`
		1. Locality Name: `Chuo-ku`
		1. Organization Name: `GS`
		1. Organizational Unit Name: `LAB`
		1. Common Name: `メモしたIP`
		1. Email AddressとAn optional company nameは「空」でOK 
	1. `openssl x509 -req -days 3650 -signkey server.key -in server.csr -out server.crt`
	1. `vim /etc/httpd/conf.d/ssl.conf`
		1. `SSLCertificateFile /etc/pki/tls/certs/localhost.crt`の部分を`SSLCertificateFile /home/ec2-user/server.crt`へ変更
		1. `SSLCertificateKeyFile /etc/pki/tls/private/localhost.key`の部分を`SSLCertificateKeyFile /home/ec2-user/server.key`へ変更
	1. `make down-web`して`make up-web`
	1. HTTPSでアクセスできることを確認

# 用意しているmakeコマンド
※全てHanlyフォルダ直下で実行
- ApacheもMySQLも起動/終了
`make up` / `make down`
- Apacheの起動/終了
`make up-web` / `make down-web`
- mysqlの起動/終了
`make up-db` / `make down-db`
- phpMyAdmin用のビルトインサーバの起動/終了
`make up-builtin` / 終了は起動したターミナルで「Ctrl + c」