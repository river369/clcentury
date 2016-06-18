--Users
CREATE TABLE IF NOT EXISTS `clctravel`.`yz_users` (
`id` bigint(12) NOT NULL AUTO_INCREMENT COMMENT '主键',
`user_type` int(5) DEFAULT 1  COMMENT 'User类型 1 phone, 2 weixin, 3 qq ...',
`phone_reigon` varchar(6) DEFAULT NULL  COMMENT '电话区号',
`phone_number` varchar(20) DEFAULT NULL  COMMENT '电话号码',
`password` varchar(50) DEFAULT NULL  COMMENT '',
`external_id` varchar(100) DEFAULT NULL COMMENT '1微信,openid 2 ...',
`name` varchar(255)  DEFAULT NULL  COMMENT '姓名',
`gender` int(2)  DEFAULT 1 COMMENT '性别 1 male, 2 female',
`weixin` varchar(50)  DEFAULT NULL  COMMENT '微信号',
`email` varchar(50) DEFAULT NULL  COMMENT '邮件',
`description` text DEFAULT NULL  COMMENT '个人详细介绍',
`stars` int(3) DEFAULT 3  COMMENT '星',
`tag` varchar(255) DEFAULT "" COMMENT 'user tags',
`status` int(5) DEFAULT 0  COMMENT 'user状态 0 created...' ,
`real_name` varchar(255)  DEFAULT NULL  COMMENT '真实姓名',
`certificate_type` int(5) DEFAULT 1  COMMENT 'User类型 1 身份证, 2 Passport',
`certificate_no` varchar(25)  DEFAULT NULL  COMMENT '身份号码',
`check_reason` varchar(255)  DEFAULT NULL  COMMENT 'approve or reject reason',
`creation_date` datetime  DEFAULT NULL COMMENT 'creation datetime',
`update_date` datetime  DEFAULT NULL COMMENT 'update datetime',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci CHECKSUM=0 ROW_FORMAT=DYNAMIC DELAY_KEY_WRITE=0;

user状态 0 created, 20 个人信息不完整, 40 已经实名, 60 已发布过服务, 80 完成一次服务, 100 多次服务, 120 封号 ,
user状态 0 created已经注册, 20 已经提交实名, 40, rejected 60 approved, 120 封号 ,

--Services
CREATE TABLE IF NOT EXISTS `clctravel`.`yz_services` (
`id` bigint(12) NOT NULL AUTO_INCREMENT COMMENT '主键',
`seller_id` bigint(12)  NOT NULL COMMENT 'The people to sell',
`seller_name` varchar(255)  DEFAULT NULL  COMMENT 'seller姓名',
`status` int(5) DEFAULT 0  COMMENT 'service状态 0 created, ...' ,
`description` text DEFAULT NULL  COMMENT 'service详细介绍',
`service_area` varchar(50) DEFAULT NULL  COMMENT '服务区域',
`service_type` int(10) DEFAULT 1  COMMENT '服务类型 1 旅游, 2 留学, 99999 all, -1 nothing',
`service_price` int(10) DEFAULT 50  COMMENT '服务价格',
`service_price_unit` varchar(10)  DEFAULT "人民币"  COMMENT '服务价格单位',
`stars` int(3) DEFAULT 3  COMMENT '星',
`check_reason` varchar(255) DEFAULT NULL  COMMENT '管理员拒绝理由',
`delete_reason` varchar(255) DEFAULT NULL  COMMENT 'seller删除原因,当有用户购买时必须输入',
`tag` varchar(255) DEFAULT "" COMMENT 'user tags',
`creation_date` datetime  DEFAULT NULL COMMENT 'creation datetime',
`update_date` datetime  DEFAULT NULL COMMENT 'update datetime',
PRIMARY KEY (`id`),
KEY `seller_id` (`seller_id`)
) ENGINE=MyISAM DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci CHECKSUM=0 ROW_FORMAT=DYNAMIC DELAY_KEY_WRITE=0;

service status:
0 created,
20 wait for approve
40 rejected
60 approved,
80 deleted

--Orders
CREATE TABLE IF NOT EXISTS `clctravel`.`yz_orders` (
`id` bigint(12) NOT NULL AUTO_INCREMENT COMMENT '主键',
`service_id` bigint(12)  NOT NULL COMMENT 'service to buy',
`customer_id` bigint(12)  NOT NULL COMMENT 'The people to buy',
`customer_name` varchar(255)  DEFAULT NULL  COMMENT 'seller姓名',
`seller_id` bigint(12)  NOT NULL COMMENT 'The people to sell',
`seller_name` varchar(255)  DEFAULT NULL  COMMENT 'seller姓名',
`status` int(5) DEFAULT 0  COMMENT '订单状态 0 created...' ,
`service_area` varchar(50) DEFAULT NULL  COMMENT '服务区域',
`service_type` int(10) DEFAULT -1  COMMENT '服务类型 1 旅游, 2 留学, 99999 all, -1 nothing',
`service_price` int(10) DEFAULT 50  COMMENT '服务价格',
`service_price_unit` varchar(10)  DEFAULT "人民币"  COMMENT '服务价格单位',
`service_hours` int(10) DEFAULT 1  COMMENT '服务小时数',
`service_total_fee` int(10) DEFAULT 50  COMMENT '支付金额',
`request_message` text DEFAULT NULL  COMMENT '留言',
`creation_date` datetime  DEFAULT NULL COMMENT 'creation datetime',
`update_date` datetime  DEFAULT NULL COMMENT 'update datetime',
PRIMARY KEY (`id`),
KEY `service_id` (`service_id`),
KEY `seller_id` (`seller_id`),
KEY `customer_id` (`customer_id`)
) ENGINE=MyISAM DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci CHECKSUM=0 ROW_FORMAT=DYNAMIC DELAY_KEY_WRITE=0;

订单状态 :
 0 customer created, seller 待接收
 10 customer 已经支付
 1020 seller 已拒绝,
 20 seller 已接收,
 40 seller 已完成, customer 待确认
 1040 seller 已取消
 1060 customer 已取消
 60 customer 已确认完成
 80 评论完成
 100 eknowhow已经支付

--order actions
CREATE TABLE IF NOT EXISTS `clctravel`.`yz_order_actions` (
`id` bigint(12) NOT NULL AUTO_INCREMENT COMMENT '主键',
`order_id` bigint(12)  NOT NULL COMMENT 'order id',
`action` int(10) DEFAULT 0 NOT NULL COMMENT '服务类型 0 创建订单',
`creation_date` datetime  NOT NULL COMMENT 'entry datetime',
`update_date` datetime  DEFAULT NULL COMMENT 'update datetime',
`actioner` int(2) DEFAULT -1  COMMENT 'Action person 0 System, 1 Customer, 2 Seller',
`comments` text DEFAULT NULL  COMMENT 'action description',
PRIMARY KEY (`id`),
KEY `order_id` (`order_id`)
) ENGINE=MyISAM DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci CHECKSUM=0 ROW_FORMAT=DYNAMIC DELAY_KEY_WRITE=0;

----pay
CREATE TABLE IF NOT EXISTS `clctravel`.`yz_payments` (
`id` bigint(12) NOT NULL AUTO_INCREMENT COMMENT '主键',
`order_id` bigint(12) NOT NULL COMMENT 'foreign key',
`transaction_id` varchar(64)  DEFAULT NULL  COMMENT 'transaction id in payside',
`out_trade_no` varchar(64)  DEFAULT NULL  COMMENT 'the payment number in yz',
`cash_fee` decimal(10,2)  DEFAULT NULL COMMENT 'cache fee',
`total_fee` decimal(10,2)  DEFAULT NULL COMMENT 'total fee',
`fee_type` varchar(5)  DEFAULT NULL  COMMENT 'fee type like CYN',
`openid` varchar(100) DEFAULT NULL COMMENT '微信openid',
`is_subscribe` varchar(5)  DEFAULT NULL  COMMENT 'is_subscribe',
`result_code` varchar(25)  DEFAULT NULL  COMMENT 'result_code',
`return_code` varchar(25)  DEFAULT NULL  COMMENT 'return_code',
`trade_type` varchar(10)  DEFAULT NULL  COMMENT 'trade type',
`start_date` datetime  DEFAULT NULL COMMENT 'time_start',
`end_date` datetime  DEFAULT NULL COMMENT 'time_end',
`creation_date` datetime  DEFAULT NULL COMMENT 'creation datetime',
`update_date` datetime  DEFAULT NULL COMMENT 'update datetime',
PRIMARY KEY (`id`),
KEY `order_id` (`order_id`)
) ENGINE=MyISAM DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci CHECKSUM=0 ROW_FORMAT=DYNAMIC DELAY_KEY_WRITE=0;

--query history
CREATE TABLE IF NOT EXISTS `clctravel`.`yz_query_history` (
`id` bigint(12) NOT NULL AUTO_INCREMENT COMMENT '主键',
`user_id` bigint(12)  NOT NULL COMMENT 'The people to query',
`key_word` varchar(255)  DEFAULT NULL  COMMENT 'search key',
`status` int(2) DEFAULT 0  COMMENT 'active 0, deleted 1' ,
`creation_date` datetime  DEFAULT NULL COMMENT 'creation datetime',
`update_date` datetime  DEFAULT NULL COMMENT 'update datetime',
PRIMARY KEY (`id`),
KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci CHECKSUM=0 ROW_FORMAT=DYNAMIC DELAY_KEY_WRITE=0;

--Comments
CREATE TABLE IF NOT EXISTS `clctravel`.`yz_comments` (
`id` bigint(12) NOT NULL AUTO_INCREMENT COMMENT '主键',
`service_id` bigint(12)  NOT NULL COMMENT 'service to buy,foreign key',
`order_id` bigint(12) NOT NULL COMMENT 'order Id, foreign key',
`customer_id` bigint(12)  NOT NULL COMMENT 'The people to buy',
`seller_id` bigint(12)  NOT NULL COMMENT 'The people to sell',
`comments` text DEFAULT NULL  COMMENT '评论',
`stars` int(3) DEFAULT 3  COMMENT '星',
`creation_date` datetime  DEFAULT NULL COMMENT 'creation datetime',
`update_date` datetime  DEFAULT NULL COMMENT 'update datetime',
PRIMARY KEY (`id`),
KEY `service_id` (`service_id`),
KEY `order_id` (`order_id`),
KEY `customer_id` (`customer_id`),
KEY `seller_id` (`seller_id`)
) ENGINE=MyISAM DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci CHECKSUM=0 ROW_FORMAT=DYNAMIC DELAY_KEY_WRITE=0;



------------------------Deprecated V3(1 user 1 service)-------------------------
--Users
CREATE TABLE IF NOT EXISTS `clctravel`.`clc_users` (
`id` bigint(12) NOT NULL AUTO_INCREMENT COMMENT '主键',
`phonereigon` varchar(6) NOT NULL  COMMENT '电话区号',
`phonenumber` varchar(20) NOT NULL  COMMENT '电话号码',
`password` varchar(30) NOT NULL  COMMENT '',
`openid` varchar(100) DEFAULT NULL COMMENT '微信openid',
`name` varchar(255)  DEFAULT NULL  COMMENT '姓名',
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
`customername` varchar(255)  DEFAULT NULL  COMMENT 'seller姓名',
`sellerid` bigint(12)  NOT NULL COMMENT 'The people to sell',
`sellername` varchar(255)  DEFAULT NULL  COMMENT 'seller姓名',
`conditions` int(10) DEFAULT 0  COMMENT '订单状态 0 created...' ,
`servicearea` varchar(50) DEFAULT NULL  COMMENT '服务区域',
`servicetype` int(10) DEFAULT -1  COMMENT '服务类型 1 旅游, 2 留学, 99999 all, -1 nothing',
`serviceprice` int(10) DEFAULT 50  COMMENT '服务价格',
`servicepriceunit` varchar(10)  DEFAULT "人民币"  COMMENT '服务价格单位',
`servicehours` int(10) DEFAULT 1  COMMENT '服务小时数',
`servicetotalfee` int(10) DEFAULT 50  COMMENT '支付金额',
`requestmessage` text DEFAULT NULL  COMMENT '留言',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci CHECKSUM=0 ROW_FORMAT=DYNAMIC DELAY_KEY_WRITE=0;

订单状态 :
 0 customer created, seller 待接收
 1020 seller 已拒绝,
 20 seller 已接收,
 40 seller 已完成, customer 待确认
 1040 seller 已取消
 1060 customer 已取消
 60 customer 已确认完成
 80 yz已经支付

INSERT INTO clc_orders (sellerid,conditions,customerid) VALUES (1,0,2) ;

CREATE TABLE IF NOT EXISTS `clctravel`.`clc_order_actions` (
`id` bigint(12) NOT NULL AUTO_INCREMENT COMMENT '主键',
`orderid` bigint(12)  NOT NULL COMMENT 'order id',
`action` int(10) DEFAULT 0 NOT NULL COMMENT '服务类型 0 创建订单',
`creation_date` datetime  NOT NULL COMMENT 'entry datetime',
`actioner` int(2) DEFAULT -1  COMMENT 'Action person 0 System, 1 Customer, 2 Seller',
`comments` text DEFAULT NULL  COMMENT 'action description',
PRIMARY KEY (`id`),
KEY `order_id` (`order_id`)
) ENGINE=MyISAM DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci CHECKSUM=0 ROW_FORMAT=DYNAMIC DELAY_KEY_WRITE=0;

----pay
CREATE TABLE IF NOT EXISTS `clctravel`.`clc_payments` (
`id` bigint(12) NOT NULL AUTO_INCREMENT COMMENT '主键',
`order_id` bigint(12) NOT NULL COMMENT 'foreign key',
`transaction_id` varchar(64)  DEFAULT NULL  COMMENT 'transaction id in payside',
`out_trade_no` varchar(64)  DEFAULT NULL  COMMENT 'the payment number in yz',
`cash_fee` decimal(10,2)  DEFAULT NULL COMMENT 'cache fee',
`total_fee` decimal(10,2)  DEFAULT NULL COMMENT 'total fee',
`fee_type` varchar(5)  DEFAULT NULL  COMMENT 'fee type like CYN',
`openid` varchar(100) DEFAULT NULL COMMENT '微信openid',
`is_subscribe` varchar(5)  DEFAULT NULL  COMMENT 'is_subscribe',
`result_code` varchar(25)  DEFAULT NULL  COMMENT 'result_code',
`return_code` varchar(25)  DEFAULT NULL  COMMENT 'return_code',
`trade_type` varchar(10)  DEFAULT NULL  COMMENT 'trade type',
`start_date` datetime  DEFAULT NULL COMMENT 'time_start',
`end_date` datetime  DEFAULT NULL COMMENT 'time_end',
PRIMARY KEY (`id`),
KEY `order_id` (`order_id`)
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