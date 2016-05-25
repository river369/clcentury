--Users
CREATE TABLE IF NOT EXISTS `clctravel`.`clc_users` (
`id` bigint(12) NOT NULL AUTO_INCREMENT COMMENT '主键',
`phonereigon` varchar(6) NOT NULL  COMMENT '电话区号',
`phonenumber` varchar(20) NOT NULL  COMMENT '电话号码',
`password` varchar(30) NOT NULL  COMMENT '',
`openid` varchar(100) DEFAULT NULL COMMENT '微信openid',
`name` varchar(255)  DEFAULT NULL  COMMENT '标题',
`gender` int(10)  DEFAULT 1 COMMENT '性别',
`weixin` varchar(50)  DEFAULT NULL  COMMENT '微信号',
`email` varchar(50) DEFAULT NULL  COMMENT '邮件',
`description` text DEFAULT NULL  COMMENT '个人详细介绍',
`servicearea` varchar(50) DEFAULT NULL  COMMENT '服务区域',
`servicetype` int(10) DEFAULT -1  COMMENT '服务类型 1 旅游, 2 留学, 99999 all, -1 nothing',
`serviceprice` int(10) DEFAULT 50  COMMENT '服务价格',
`servicepriceunit` varchar(10)  DEFAULT "人民币"  COMMENT '服务价格单位',
`stars` int(3) DEFAULT 3  COMMENT '星',
`tag` varchar(100) DEFAULT "" COMMENT 'user tags',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci CHECKSUM=0 ROW_FORMAT=DYNAMIC DELAY_KEY_WRITE=0;

insert into `clctravel`.`clc_users`(id,phonereigon,phonenumber,password,openid,name,gender,weixin,email,description,servicearea,servicetype,
serviceprice,servicepriceunit,stars) select id,phonereigon,phonenumber,password,openid,name,gender,weixin,email,description,servicearea,servicetype,
serviceprice,servicepriceunit,stars from temp;

--Orders
CREATE TABLE IF NOT EXISTS `clctravel`.`clc_orders` (
`id` bigint(12) NOT NULL AUTO_INCREMENT COMMENT '主键',
`customerid` bigint(12)  NOT NULL COMMENT 'The people to buy',
`sellerid` bigint(12)  NOT NULL COMMENT 'The people to sell',
`servicearea` varchar(50) DEFAULT NULL  COMMENT '服务区域',
`servicetype` int(10) DEFAULT -1  COMMENT '服务类型 1 旅游, 2 留学, 99999 all, -1 nothing',
`serviceprice` int(10) DEFAULT 50  COMMENT '服务价格',
`servicepriceunit` varchar(10)  DEFAULT "人民币"  COMMENT '服务价格单位',
`servicehours` int(10) DEFAULT 50  COMMENT '服务小时数',
`servicetotalfee` int(10) DEFAULT 50  COMMENT '服务小时数',
`requestmessage` text DEFAULT NULL  COMMENT '留言',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci CHECKSUM=0 ROW_FORMAT=DYNAMIC DELAY_KEY_WRITE=0;

------------------------Deprecated V2-------------------------

--Users
CREATE TABLE IF NOT EXISTS `clctravel`.`clc_users` (
`id` int(12) NOT NULL AUTO_INCREMENT COMMENT '主键',
`phonereigon` varchar(6) NOT NULL  COMMENT '电话区号',
`phonenumber` varchar(20) NOT NULL  COMMENT '电话号码',
`password` varchar(30) NOT NULL  COMMENT '',
`openid` varchar(100) DEFAULT NULL COMMENT '微信openid',
`name` varchar(255)  DEFAULT NULL  COMMENT '标题',
`gender` int(10)  DEFAULT 1 COMMENT '性别',
`weixin` varchar(50)  DEFAULT NULL  COMMENT '微信号',
`email` varchar(50) DEFAULT NULL  COMMENT '邮件',
`description` text DEFAULT NULL  COMMENT '个人详细介绍',
`servicearea` varchar(50) DEFAULT NULL  COMMENT '服务区域',
`servicetype` int(10) DEFAULT -1  COMMENT '服务类型 1 旅游, 2 留学, 99999 all, -1 nothing',
`serviceprice` int(10) DEFAULT 50  COMMENT '服务价格',
`servicepriceunit` varchar(10)  DEFAULT "人民币"  COMMENT '服务价格单位',
`stars` int(3) DEFAULT 3  COMMENT '星',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci CHECKSUM=0 ROW_FORMAT=DYNAMIC DELAY_KEY_WRITE=0;

--Users tags
CREATE TABLE IF NOT EXISTS `clctravel`.`clc_users_tags` (
`id` bigint(12) NOT NULL AUTO_INCREMENT COMMENT '主键',
`userid` bigint(12)  NOT NULL COMMENT 'User Id',
`tag` varchar(100) NOT NULL COMMENT 'user tags',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci CHECKSUM=0 ROW_FORMAT=DYNAMIC DELAY_KEY_WRITE=0;

------------------------Deprecated V1-------------------------
CREATE TABLE IF NOT EXISTS `clctravel`.`clc_users` (
`id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
`openid` varchar(100) DEFAULT NULL COMMENT '微信openid',
`name` varchar(255)  DEFAULT NULL  COMMENT '标题',
`gender` int(10)  DEFAULT 1 COMMENT '性别',
`weixin` varchar(50)  DEFAULT NULL  COMMENT '微信号',
`email` varchar(50) DEFAULT NULL  COMMENT '邮件',
`phone` varchar(50) DEFAULT NULL  COMMENT '电话',
`description` text DEFAULT NULL  COMMENT '个人详细介绍',
`servicearea` varchar(50) DEFAULT NULL  COMMENT '服务区域',
`servicetype` int(10) DEFAULT 0  COMMENT '服务类型 1 旅游, 2 留学, 0 all',
`serviceprice` int(10) DEFAULT 50  COMMENT '服务价格',
`servicepriceunit` varchar(10)  DEFAULT "人民币"  COMMENT '服务价格单位',
`stars` int(3) DEFAULT 3  COMMENT '星',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci CHECKSUM=0 ROW_FORMAT=DYNAMIC DELAY_KEY_WRITE=0;


INSERT INTO clc_users (name,weixin,servicearea,description,servicetype,serviceprice) VALUES ('River','112','西雅图','爱玩,不知疲倦','1','100');