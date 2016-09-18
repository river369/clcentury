[Directory]
mkdir clc
mkdir logs
mkdir uploads
chmod -R 777 clc
chmod -R 777 logs
chmod -R 777 upload

unzip weiphp3.0_beta.zip
sudo chmod -R 777 weiphp

scp -r OverSea root@www.clcentury.com:/home/www/clc/weiphp/Addons
scp -r WxpayAPI_v3 root@www.clcentury.com:/home/www/clc/weiphp

[Config]
cp /home/www/config.php /home/www/clc/weiphp/Addons/OverSea/
cp /home/www/WxPay.Config.php /home/www/clc/weiphp/WxpayAPI_v3/lib

[nginx]
vi /etc/nginx/nginx.conf
set value for client_max_body_size as 5m;

service nginx restart

[php]
vi /etc/php.ini
set value for upload_max_filesize as 5m, for post_max_size as 8m

killall php-fpm
/usr/sbin/php-fpm &

[Menus]
《进入易知》
http://www.clcentury.com/weiphp/Addons/OverSea/View/mobile/query/discover.php
//http://www.clcentury.com/weiphp/Addons/OverSea/Controller/FreelookDispatcher.php?c=index
//http://www.clcentury.com/weiphp/Addons/OverSea/View/mobile/query/discover.php
//http://www.clcentury.com/weiphp/Addons/OverSea/Controller/FreelookDispatcher.php?c=getServices
//http://www.clcentury.com/weiphp/Addons/OverSea/Controller/AuthUserDispatcher.php?c=submityz
//http://www.clcentury.com/weiphp/Addons/OverSea/Controller/AuthUserDispatcher.php?c=submityzpic
//http://www.clcentury.com/weiphp/Addons/OverSea/Controller/AuthUserDispatcher.php?c=mine

《联系方式》
易知海外商务拓展联系方式

邮箱: contact@clcentury.com

官方微信: 

《意见反馈》
http://www.clcentury.com/weiphp/Addons/OverSea/View/mobile/users/suggestion.php

[Admins]
http://www.clcentury.com/weiphp/Addons/OverSea/View/admin/index.php



