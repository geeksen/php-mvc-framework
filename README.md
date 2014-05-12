php-mvc-framework
=================

Install Apache2
---------------
* sudo apt-get update
* sudo apt-get upgrade
* sudo apt-get install apache2
* sudo a2enmod rewrite
* sudo a2enmod mime_magic

* cd /etc/apache2/sites-enabled
* sudo vi 000-default
```
<Directory /var/www>
..
AllowOverride All
</Directory>
```

* sudo /etc/init.d/apache2 restart

Install MariaDB
---------------
* sudo apt-key adv --recv-keys --keyserver keyserver.ubuntu.com 0xcbcb082a1bb943db
* vi /etc/apt/sources.list
```
deb http://ftp.osuosl.org/pub/mariadb/repo/10.0/ubuntu precise main
deb-src http://ftp.osuosl.org/pub/mariadb/repo/10.0/ubuntu precise main
```

* sudo apt-get update
* sudo apt-get upgrade
* sudo apt-get install mariadb-server

Install HandlerSocket
---------------------
* sudo vi /etc/mysql/my.cnf
```
[mysqld]
..
handlersocket_address = 127.0.0.1
handlersocket_port = 9998
handlersocket_port_wr = 9999
```

* mysql -u root -p
* INSTALL PLUGIN handlersocket SONAME 'handlersocket.so';
* exit
* sudo /etc/init.d/mysql restart
* mysql -u root -p
* SHOW PROCESSLIST
* exit

Install PHP
-----------
* sudo apt-get update
* sudo apt-get upgrade
* sudo apt-get install libapache2-mod-php5 php5 php5-mysqlnd
* sudo /etc/init.d/apache2 restart

Install php-mvc-framework
-------------------------
* cd /var/www
* git clone https://github.com/geeksen/php-mvc-framework.git
* cd php-mvc-framework
* mv * ..
* mv .htaccess ..
* cd ..
* rmdir php-mvc-framework
* mkdir upload
* sudo chown www-data.www-data upload

Run
---
* Go to http://your.web.server/
