php-mvc-framework
=================

<!--
sudo apt-get install nginx
sudo apt-get install php-fpm

vi /etc/php5/fpm/php.ini
cgi.fix_pathinfo=0

sudo vi /etc/nginx/sites-available/default
server {
        listen 80 default_server;
        listen [::]:80 default_server ipv6only=on;

        root /var/www/html;
	
        autoindex off;
        index index.php;

        server_name localhost;

        location ~* \.(ico|css|js|gif|jpe?g|png)(\?[0-9]+)?$ {
                expires max;
                log_not_found off;
        }

        location / {
                try_files $uri $uri/ /index.php;

                location = /index.php {
                        fastcgi_pass unix:/var/run/php5-fpm.sock;
                        include fastcgi_params;
                        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
                }
        }

        location ~ \.php$ {
                return 444;
        }
}
-->

Install Apache2
---------------
* sudo apt-get update
* sudo apt-get upgrade
* sudo apt-get install apache2
* sudo a2enmod rewrite
* sudo a2enmod mime_magic

* cd /etc/apache2/sites-enabled
* sudo vi 000-default.conf
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
* sudo vi /etc/apt/sources.list
```
deb http://ftp.osuosl.org/pub/mariadb/repo/10.0/ubuntu precise main
deb-src http://ftp.osuosl.org/pub/mariadb/repo/10.0/ubuntu precise main
```

* sudo apt-get update
* sudo apt-get upgrade
* sudo apt-get install mariadb-server

<!--
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
* SHOW PROCESSLIST;
* exit
-->

Install PHP
-----------
* sudo apt-get update
* sudo apt-get upgrade
* sudo apt-get install libapache2-mod-php5 php5 php5-mysqlnd php5-curl
* sudo /etc/init.d/apache2 restart

Install php-mvc-framework
-------------------------
* cd /var/www
* git clone https://github.com/geeksen/php-mvc-framework.git
* cd php-mvc-framework
* mv * ..
* mv .htaccess ..
* cd ..
* rm -rf php-mvc-framework
* mkdir upload
* sudo chown www-data.www-data upload

Run
---
* Go to http://your.web.server/
