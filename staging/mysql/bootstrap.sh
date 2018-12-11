#!/usr/bin/env bash

# Use single quotes instead of double quotes to make it work with special-character passwords
PASSWORD='password'
DBNAME='fias'
APP_DIR='/var/app'

# add php repository
sudo apt-get -y install software-properties-common
sudo add-apt-repository ppa:ondrej/php

# update / upgrade
sudo apt-get update
sudo apt-get -y upgrade

# build essential
sudo apt-get install -y build-essential tcl git unzip

# install php 7
sudo apt-get install -y php7.2 php7.2-cli php7.2-curl php7.2-xml php7.2-zip php7.2-dev php7.2-soap php7.2-sqlite3 php7.2-mbstring

# install php rar extension
sudo pecl -v install rar
echo "extension=rar.so" >> /etc/php/7.2/cli/php.ini

# install mysql and give password to installer
sudo debconf-set-selections <<< "mysql-server mysql-server/root_password password ${PASSWORD}"
sudo debconf-set-selections <<< "mysql-server mysql-server/root_password_again password ${PASSWORD}"
sudo apt-get install -y mysql-server php7.2-mysql

# create database
mysql -uroot -p"${PASSWORD}" -e "create database if not exists ${DBNAME} DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_unicode_ci;"

# install Composer
curl -s https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer


# install composer dependencies
cd "${APP_DIR}" && composer install
