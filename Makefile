default:
	@echo Please specify target name!
.PHONY: default

install:
	@make php-version-up
	@make install-composer
	@make install-phpmyadmin
	@make update-apache-conf
	@make update-db-conf
	@make php-myadmin-setting
	@make check
.PHONY: install

php-version-up:
	sudo yum -y update
	sudo yum -y remove php56*
	sudo yum -y install php73*
	sudo unlink /usr/bin/php
	sudo ln -s /etc/alternatives/php7 /usr/bin/php
.PHONY: php-version-up

install-composer:
	curl -sS https://getcomposer.org/installer | php
	sudo mv composer.phar /usr/local/bin/composer
.PHONY: install-composer

install-phpmyadmin:
	sudo wget https://files.phpmyadmin.net/phpMyAdmin/4.9.0.1/phpMyAdmin-4.9.0.1-all-languages.zip
	sudo unzip phpMyAdmin-4.9.0.1-all-languages.zip
	sudo mv phpMyAdmin-4.9.0.1-all-languages phpMyAdmin
	sudo rm -rf phpMyAdmin-4.9.0.1-all-languages.zip
.PHONY: install-phpmyadmin

DB_ROOT_PASS=
php-myadmin-setting:
	sudo mv phpMyAdmin/config.sample.inc.php phpMyAdmin/config.inc.php
	sudo chmod 755 phpMyAdmin/config.inc.php
	@make up-db
	mysql -u root -p -e "update mysql.user set password = password('${DB_ROOT_PASS}') where user = 'root';flush privileges;"
	@make down-db
.PHONY: php-myadmin-setting

CONF=/etc/httpd/conf/httpd.conf
update-apache-conf:
	sudo cp -ip ${CONF} ${CONF}.bk
	sudo sed -i -e "s#User apache#User ec2-user#g" ${CONF}
	sudo sed -i -e "s#Group apache#Group ec2-user#g" ${CONF}
	sudo sed -i -e 's#DocumentRoot "/var/www/html"#DocumentRoot "/home/ec2-user/environment/laravel/public"#g' ${CONF}
	sudo sed -i -e 's#<Directory "/var/www/html">#<Directory "/home/ec2-user/environment/laravel/public">#g' ${CONF}
	sudo chown root:ec2-user /var/lib/php/7.3/session
.PHONY: update-apache-conf

DB_CONF=/etc/my.cnf
update-db-conf:
	sudo cp -ip ${DB_CONF} ${DB_CONF}.bk
	sudo sed -i -e '$$ a \\n[client]' ${DB_CONF}
	sudo sed -i -e '$$ a default-character-set=utf8' ${DB_CONF}
	sudo sed -i -e 's#\[mysqld\]#\[mysqld\]\ncharacter-set-server=utf8#' ${DB_CONF}
.PHONY: update-db-conf
	
check:
	php -v
	composer -V
	curl 169.254.169.254/latest/meta-data/instance-id/
	curl http://169.254.169.254/latest/meta-data/public-ipv4
.PHONY: check

up: up-web up-db
.PHONY: up

down: down-web down-db
.PHONY: down

up-web:
	sudo service httpd start
.PHONY: up-web

down-web:
	sudo service httpd stop
.PHONY: down-web

up-db:
	sudo service mysqld start
.PHONY: up-db

down-db:
	sudo service mysqld stop
.PHONY: down-db

up-builtin:
	php -S $$IP:$$PORT
.PHONY: up-builtin