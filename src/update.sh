#!/bin/sh
#重新编译(有人称之为更新缓存)
sudo rm /var/cache/hhvm/hhvm.hhbc
sudo hhvm-repo-mode enable "/var/www/fbctf"
sudo chown www-data:www-data /var/cache/hhvm/hhvm.hhbc
sudo service hhvm start
sudo service nginx restart
