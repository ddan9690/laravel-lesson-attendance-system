#!/bin/bash

# Make sure to chmod a+x on this file so it'll run during Bitnami's setup.

# Install the dependencies
#apt-get -y update
apt-get -y install autoconf unzip
apt-get -y install build-essential

# Set PECL defaults
pecl channel-update
pear config-set php_ini /opt/bitnami/php/etc/php.ini
pecl config-set php_ini /opt/bitnami/php/etc/php.ini

# Install swoole
#pecl install swoole

# Install phpredis
pecl install redis