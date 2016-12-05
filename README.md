php-mvc-framework
=================

Install PHP
-----------
* sudo apt update
* sudo apt upgrade
* sudo apt install php7.0-fpm php7.0-mysql php7.0-curl
* sudo apt install libmagic-dev

* sudo nano /etc/php/7.0/fpm/php.in
```
cgi.fix_pathinfo=0
```

Install NginX
--------------
* sudo apt update
* sudo apt upgrade
* sudo apt install nginx

* sudo nano /etc/nginx/sites-enabled/default
```
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
                        include snippets/fastcgi-php.conf;
                        fastcgi_pass unix:/run/php/php7.0-fpm.sock;
                }
        }

        location ~ \.php$ {
                return 444;
        }

        location ~ /\.ht {
                deny all;
        }
}
```

Install MariaDB
---------------
* sudo apt-key adv --recv-keys --keyserver hkp://keyserver.ubuntu.com:80 0xF1656F24C74CD1D8
* sudo nano /etc/apt/sources.list
```
# http://downloads.mariadb.org/mariadb/repositories/
deb [arch=amd64,i386] http://ftp.kaist.ac.kr/mariadb/repo/10.1/ubuntu xenial main
deb-src http://ftp.kaist.ac.kr/mariadb/repo/10.1/ubuntu xenial main
```

* sudo apt update
* sudo apt upgrade
* sudo apt install mariadb-server
* sudo /etc/init.d/mysql restart

<!--
* sudo /etc/init.d/mysql stop
* sudo /usr/bin/mysqld_safe --skip-grant-tables &
* mysql -u root
```
update mysql.user set plugin='mysql_native_password';
quit;
```

* sudo kill -9 $(pgrep mysql)
* sudo /etc/init.d/mysql start
-->

<!--
Install HandlerSocket
---------------------
* sudo nano /etc/mysql/my.cnf
```
[mysqld]
..
handlersocket_address = 127.0.0.1
handlersocket_port = 9998
handlersocket_port_wr = 9999
```

* sudo mysql -u root -p
* INSTALL PLUGIN handlersocket SONAME 'handlersocket.so';
* exit
* sudo /etc/init.d/mysql restart
* sudo mysql -u root -p
* SHOW PROCESSLIST;
* exit
-->

Install php-mvc-framework
-------------------------
* cd /var/www/html
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
