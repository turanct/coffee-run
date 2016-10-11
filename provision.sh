#!/bin/bash

#
# Basics
#

echo "--> Installing basics"
apt-get update
apt-get install -y vim git tree curl


#
# MySQL
#

echo "--> Installing MySQL"
# Set username and password to 'root'
debconf-set-selections <<< 'mysql-server mysql-server/root_password password root'
debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password root'
apt-get install -y mysql-server


#
# PHP
#

echo "--> Installing php"
apt-get install -y python-software-properties
LC_ALL=C.UTF-8 add-apt-repository ppa:ondrej/php
apt-get update
apt-get install -y php7.0 php7.0-mysql php7.0-sqlite php7.0-gd php7.0-curl php7.0-dom php7.0-xdebug php7.0-memcached php7.0-imagick php7.0-intl


#
# Apache
#

echo "--> Installing apache"
apt-get install -y apache2

# Enable the mod_rewrite
a2enmod rewrite

echo '<VirtualHost *:80>
    ServerAdmin webmaster@localhost

    DocumentRoot /var/www
    <Directory />
        Options FollowSymLinks
        AllowOverride None
    </Directory>
    <Directory /var/www/>
        Options Indexes FollowSymLinks MultiViews
        AllowOverride All
        Order allow,deny
        allow from all

        <IfModule mod_env.c>
            SetEnv BAKSKE_DB "bakske"
            SetEnv BAKSKE_DB_HOST "127.0.0.1"
            SetEnv BAKSKE_DB_USER "root"
            SetEnv BAKSKE_DB_PASS "root"
            SetEnv BAKSKE_SMTP_HOST "smtp.gmail.com"
            SetEnv BAKSKE_SMTP_USER "example@gmail.com"
            SetEnv BAKSKE_SMTP_PASS "password"
        </IfModule>
    </Directory>

    ScriptAlias /cgi-bin/ /usr/lib/cgi-bin/
    <Directory "/usr/lib/cgi-bin">
        AllowOverride None
        Options +ExecCGI -MultiViews +SymLinksIfOwnerMatch
        Order allow,deny
        Allow from all
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/error.log

    # Possible values include: debug, info, notice, warn, error, crit,
    # alert, emerg.
    LogLevel warn

    CustomLog ${APACHE_LOG_DIR}/access.log combined

    Alias /doc/ "/usr/share/doc/"
    <Directory "/usr/share/doc/">
        Options Indexes MultiViews FollowSymLinks
        AllowOverride None
        Order deny,allow
        Deny from all
        Allow from 127.0.0.0/255.0.0.0 ::1/128
    </Directory>

</VirtualHost>' > /etc/apache2/sites-available/default


# Output
echo "--> Setting up /var/www"

# Remove /var/www
rm -rf /var/www

# Link our html file to the apache web directory
ln -s /vagrant/web /var/www

# Change running user for apache
echo 'User vagrant
Group vagrant' > /etc/apache2/httpd.conf

# Restart apache
echo "--> Restarting apache"
service apache2 restart
