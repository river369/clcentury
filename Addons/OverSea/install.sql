--Account
CREATE TABLE IF NOT EXISTS `clctravel`.`yz_user_accounts` (
`id` bigint(12) NOT NULL AUTO_INCREMENT COMMENT '主键',
`user_id` varchar(36) DEFAULT NULL COMMENT 'User Id to show in the internal system',
`user_type` int(5) DEFAULT 1  COMMENT 'User类型 1 phone, 2 weixin, 3 qq ...',
`phone_reigon` varchar(6) DEFAULT NULL  COMMENT '电话区号',
`phone_number` varchar(20) DEFAULT NULL  COMMENT '电话号码',
`password` varchar(50) DEFAULT NULL  COMMENT '',
`external_id` varchar(100) DEFAULT NULL COMMENT '1微信,openid 2 ...',
`external_id_type` int(5) DEFAULT 1 COMMENT '1微信,openid 2 ...',
`creation_date` datetime  DEFAULT NULL COMMENT 'creation datetime',
`update_date` datetime  DEFAULT NULL COMMENT 'update datetime',
PRIMARY KEY (`id`),
UNIQUE KEY (`user_id`),
KEY `phone` (`phone_reigon`, `phone_number`)
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci CHECKSUM=0 ROW_FORMAT=DYNAMIC DELAY_KEY_WRITE=0;

update yz_user_accounts set external_id = 'om0h_wdY-532dGj__zVFKJVj9wJ0';
alter table yz_user_accounts add column `external_id_type` int(5) DEFAULT 1 COMMENT '1微信,openid 2 ...';

CREATE TABLE IF NOT EXISTS `clctravel`.`yz_seller_pay_accounts` (
`id` bigint(12) NOT NULL AUTO_INCREMENT COMMENT '主键',
`user_id` varchar(36) NOT NULL COMMENT 'User Id to show in the internal system',
`nick_name` varchar(36) DEFAULT NULL COMMENT 'User nick name to show on select page',
`account_type` int(5) DEFAULT 2  COMMENT '支付类型 1 weixin,',
`account_id` varchar(100) DEFAULT NULL COMMENT '1微信,openid 2 ...',
`status` decimal(5,1) DEFAULT 0  COMMENT 'account状态 0 not use, 1 using...' ,
`creation_date` datetime  DEFAULT NULL COMMENT 'creation datetime',
`update_date` datetime  DEFAULT NULL COMMENT 'update datetime',
PRIMARY KEY (`id`),
KEY (`user_id`),
KEY (`account_type`),
KEY (`account_id`),
KEY (`status`)
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci CHECKSUM=0 ROW_FORMAT=DYNAMIC DELAY_KEY_WRITE=0;


--Users Info
CREATE TABLE IF NOT EXISTS `clctravel`.`yz_user_infos` (
`id` bigint(12) NOT NULL AUTO_INCREMENT COMMENT '主键',
`user_id` varchar(36) DEFAULT NULL COMMENT 'User Id to show in the internal system',
`name` varchar(255)  DEFAULT NULL  COMMENT '姓名',
`signature` varchar(255)  DEFAULT NULL  COMMENT '个性签名',
`gender` int(2)  DEFAULT 1 COMMENT '性别 1 male, 2 female',
`weixin` varchar(50)  DEFAULT NULL  COMMENT '微信号',
`email` varchar(50) DEFAULT NULL  COMMENT '邮件',
`description` text DEFAULT NULL  COMMENT '个人详细介绍',
`stars` int(3) DEFAULT 3  COMMENT '用户等级',
`serve_count` int(5) DEFAULT 0  COMMENT '用户提供服务次数',
`tag` varchar(255) DEFAULT "" COMMENT 'user tags',
`status` decimal(5,1) DEFAULT 0  COMMENT 'user状态 0 created...' ,
`real_name` varchar(255)  DEFAULT NULL  COMMENT '真实姓名',
`certificate_type` int(5) DEFAULT 1  COMMENT 'User类型 1 身份证, 2 Passport',
`certificate_no` varchar(25)  DEFAULT NULL  COMMENT '身份号码',
`check_reason` varchar(255)  DEFAULT NULL  COMMENT 'approve or reject reason',
`user_type` int(1)  DEFAULT 0 COMMENT 'user type, 0 common user, 1 admin',
`creation_date` datetime  DEFAULT NULL COMMENT 'creation datetime',
`update_date` datetime  DEFAULT NULL COMMENT 'update datetime',
PRIMARY KEY (`id`),
UNIQUE KEY (`name`),
UNIQUE KEY (`user_id`),
KEY (`status`)
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci CHECKSUM=0 ROW_FORMAT=DYNAMIC DELAY_KEY_WRITE=0;

user状态 0 created, 20 个人信息不完整, 40 已经实名, 60 已发布过服务, 80 完成一次服务, 100 多次服务, 120 封号 ,
user状态 0 created已经注册, 20 已经提交实名, 40, rejected 60 approved, 120 封号 ,
alter table yz_users add column serve_count int(5) DEFAULT 0  COMMENT '用户提供服务次数';

alter table yz_user_infos drop column stars;
alter table yz_user_infos add column `stars` decimal(5,1) DEFAULT 3  COMMENT '服务价格';
alter table yz_user_infos add column `signature` varchar(255)  DEFAULT NULL  COMMENT '个性签名';
alter table yz_user_infos add column `user_type` int(1)  DEFAULT 0 COMMENT 'user type, 0 common user, 1 admin';
--Users Settings
CREATE TABLE IF NOT EXISTS `clctravel`.`yz_user_settings` (
`id` bigint(12) NOT NULL AUTO_INCREMENT COMMENT '主键',
`user_id` varchar(36) DEFAULT NULL COMMENT 'user id foreign key',
`selected_service_area` varchar(50) DEFAULT NULL  COMMENT '服务区域',
`creation_date` datetime  DEFAULT NULL COMMENT 'creation datetime',
`update_date` datetime  DEFAULT NULL COMMENT 'update datetime',
PRIMARY KEY (`id`),
KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci CHECKSUM=0 ROW_FORMAT=DYNAMIC DELAY_KEY_WRITE=0;

--Services
CREATE TABLE IF NOT EXISTS `clctravel`.`yz_services` (
`id` bigint(12) NOT NULL AUTO_INCREMENT COMMENT '主键',
`service_id` varchar(36) DEFAULT NULL COMMENT 'Service Id to show in the internal system',
`service_name` varchar(255)  DEFAULT NULL  COMMENT 'service title',
`service_brief` varchar(255)  DEFAULT NULL  COMMENT 'service 简介',
`seller_id` varchar(36) DEFAULT NULL COMMENT 'The people to sell',
`seller_name` varchar(255)  DEFAULT NULL  COMMENT 'seller姓名',
`status` int(5) DEFAULT 0  COMMENT 'service状态 0 created, ...' ,
`description` text DEFAULT NULL  COMMENT 'service详细介绍',
`service_area` varchar(50) DEFAULT NULL  COMMENT '服务区域',
`service_type` int(10) DEFAULT 1  COMMENT '服务类型 1 旅游, 2 留学, 99999 all, -1 nothing',
`service_price_type` int(10) DEFAULT 1  COMMENT 'service fee type 1 hourly, 2 each time',
`service_price` decimal(10,2) DEFAULT 50  COMMENT '服务价格',
`service_price_unit` varchar(10)  DEFAULT "人民币"  COMMENT '服务价格单位',
`stars` decimal(5,1) DEFAULT 3  COMMENT '服务评级',
`serve_count` int(5) DEFAULT 0  COMMENT '服务旅行次数',
`check_reason` varchar(255) DEFAULT NULL  COMMENT '管理员拒绝理由',
`delete_reason` varchar(255) DEFAULT NULL  COMMENT 'seller删除原因,当有用户购买时必须输入',
`tag` varchar(255) DEFAULT "" COMMENT 'user tags',
`creation_date` datetime  DEFAULT NULL COMMENT 'creation datetime',
`update_date` datetime  DEFAULT NULL COMMENT 'update datetime',
PRIMARY KEY (`id`),
UNIQUE KEY (`service_id`),
KEY `service_name` (`service_name`),
KEY `service_brief` (`service_brief`),
KEY `seller_id` (`seller_id`),
KEY `service_area` (`service_area`),
KEY `service_type` (`service_type`)
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci CHECKSUM=0 ROW_FORMAT=DYNAMIC DELAY_KEY_WRITE=0;

service status:
0 created,
20 wait for approve
40 rejected
60 approved,
80 deleted
alter table yz_services add column `service_price_type` int(10) DEFAULT 1  COMMENT 'service fee type 1 hourly, 2 each time';
alter table yz_services add column serve_count int(5) DEFAULT 0  COMMENT '用户提供服务次数';
alter table yz_services drop column service_price;
alter table yz_services add column `service_price` decimal(10,2) DEFAULT 50  COMMENT '服务价格';
alter table yz_services drop column stars;
alter table yz_services add column `stars` decimal(5,1) DEFAULT 3  COMMENT '服务评级';
alter table yz_services add column `service_brief` varchar(255)  DEFAULT NULL  COMMENT 'service 简介';
alter table yz_services add index `service_name` (`service_name`);
alter table yz_services add index `service_brief` (`service_brief`);

--Service Y Plus page
CREATE TABLE IF NOT EXISTS `clctravel`.`yz_service_yplus` (
`id` bigint(12) NOT NULL AUTO_INCREMENT COMMENT '主键',
`service_id` varchar(36) DEFAULT NULL COMMENT 'Service Id to show in the internal system',
`yplus_subject` varchar(255)  DEFAULT NULL  COMMENT 'y plus item subject',
`yplus_brief` varchar(255)  DEFAULT NULL  COMMENT 'y plus item 简介',
`status` int(2) DEFAULT 0  COMMENT 'active 0, deleted 1' ,
`creation_date` datetime  DEFAULT NULL COMMENT 'creation datetime',
`update_date` datetime  DEFAULT NULL COMMENT 'update datetime',
PRIMARY KEY (`id`),
KEY (`service_id`)
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci CHECKSUM=0 ROW_FORMAT=DYNAMIC DELAY_KEY_WRITE=0;

--Orders
CREATE TABLE IF NOT EXISTS `clctravel`.`yz_orders` (
`id` bigint(12) NOT NULL AUTO_INCREMENT COMMENT '主键',
`order_id` varchar(36) DEFAULT NULL COMMENT 'order Id to show in the internal system',
`service_id` varchar(36) DEFAULT NULL COMMENT 'Service Id to show in the internal system',
`service_name` varchar(255)  DEFAULT NULL  COMMENT 'service title',
`customer_id` varchar(36)  NOT NULL COMMENT 'The people to buy',
`customer_name` varchar(255)  DEFAULT NULL  COMMENT 'customer姓名',
`seller_id` varchar(36)  NOT NULL COMMENT 'The people to sell',
`seller_name` varchar(255)  DEFAULT NULL  COMMENT 'seller姓名',
`status` int(5) DEFAULT 0  COMMENT '订单状态 0 created...' ,
`service_area` varchar(50) DEFAULT NULL  COMMENT '服务区域',
`service_type` int(10) DEFAULT -1  COMMENT '服务类型 1 旅游, 2 留学, 99999 all, -1 nothing',
`service_price_type` int(10) DEFAULT 1  COMMENT 'service fee type 1 hourly, 2 each time',
`service_price` decimal(10,2) DEFAULT 50  COMMENT '服务价格',
`service_price_unit` varchar(10)  DEFAULT "人民币"  COMMENT '服务价格单位',
`service_hours` int(10) DEFAULT 1  COMMENT '服务小时数', -- infact it means service length with a price type
`service_total_fee` decimal(10,2) DEFAULT 50  COMMENT '支付金额',
`request_message` text DEFAULT NULL  COMMENT '留言',
`service_start_date` datetime  DEFAULT NULL COMMENT 'the date to start service',
`service_people_count` int(10) DEFAULT 1  COMMENT '服务人数',
`creation_date` datetime  DEFAULT NULL COMMENT 'creation datetime',
`update_date` datetime  DEFAULT NULL COMMENT 'update datetime',
PRIMARY KEY (`id`),
KEY `order_id` (`order_id`),
KEY `service_id` (`service_id`),
KEY `seller_id` (`seller_id`),
KEY `customer_id` (`customer_id`)
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci CHECKSUM=0 ROW_FORMAT=DYNAMIC DELAY_KEY_WRITE=0;

订单状态 :
 0 customer created, seller 待接收
 10 customer 已经支付
 1020 seller 已拒绝,
 20 seller 已接收,
 40 seller 已完成, customer 待确认
 1040 seller 已取消
 1060 customer 已取消
 1080 customer 已退款
 60 customer 已确认完成
 70 customer有争议
 80 评论完成
 100 eknowhow已经支付

alter table yz_orders drop column service_price;
alter table yz_orders add column `service_price` decimal(10,2) DEFAULT 50  COMMENT '服务价格';
alter table yz_orders drop column service_total_fee;
alter table yz_orders add column `service_total_fee` decimal(10,2) DEFAULT 50  COMMENT '服务价格';
alter table yz_orders add column `service_price_type` int(10) DEFAULT 1  COMMENT 'service fee type 1 hourly, 2 each time';
alter table yz_orders add column `service_start_date` datetime  DEFAULT NULL COMMENT 'the date to start service';
alter table yz_orders add column `service_people_count` int(10) DEFAULT 1  COMMENT '服务人数';

--order actions
CREATE TABLE IF NOT EXISTS `clctravel`.`yz_order_actions` (
`id` bigint(12) NOT NULL AUTO_INCREMENT COMMENT '主键',
`order_id` varchar(36) DEFAULT NULL COMMENT 'order Id to show in the internal system',
`action` int(10) DEFAULT 0 NOT NULL COMMENT '服务类型 0 创建订单',
`creation_date` datetime  NOT NULL COMMENT 'entry datetime',
`update_date` datetime  DEFAULT NULL COMMENT 'update datetime',
`actioner` int(2) DEFAULT -1  COMMENT 'Action person 0 System, 1 Customer, 2 Seller',
`comments` text DEFAULT NULL  COMMENT 'action description',
PRIMARY KEY (`id`),
KEY `order_id` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci CHECKSUM=0 ROW_FORMAT=DYNAMIC DELAY_KEY_WRITE=0;

----customer pay
CREATE TABLE IF NOT EXISTS `clctravel`.`yz_payments` (
`id` bigint(12) NOT NULL AUTO_INCREMENT COMMENT '主键',
`order_id` varchar(36) DEFAULT NULL COMMENT 'order Id to show in the internal system',
`transaction_id` varchar(64)  DEFAULT NULL  COMMENT 'transaction id in payside',
`out_trade_no` varchar(64)  DEFAULT NULL  COMMENT 'the payment number in yz',
`cash_fee` decimal(10,2)  DEFAULT NULL COMMENT 'cache fee',
`total_fee` decimal(10,2)  DEFAULT NULL COMMENT 'total fee',
`fee_type` varchar(5)  DEFAULT NULL  COMMENT 'fee type like CYN',
`openid` varchar(100) DEFAULT NULL COMMENT '微信openid',
`is_subscribe` varchar(5)  DEFAULT NULL  COMMENT 'is_subscribe',
`result_code` varchar(25)  DEFAULT NULL  COMMENT 'result_code',
`err_code_des` varchar(128)  DEFAULT NULL  COMMENT 'err_code_des'
`return_code` varchar(25)  DEFAULT NULL  COMMENT 'return_code',
`return_msg` varchar(128)  DEFAULT NULL  COMMENT 'return_msg',
`trade_type` varchar(10)  DEFAULT NULL  COMMENT 'trade type',
`start_date` datetime  DEFAULT NULL COMMENT 'time_start',
`end_date` datetime  DEFAULT NULL COMMENT 'time_end',
`creation_date` datetime  DEFAULT NULL COMMENT 'creation datetime',
`update_date` datetime  DEFAULT NULL COMMENT 'update datetime',
PRIMARY KEY (`id`),
KEY `order_id` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci CHECKSUM=0 ROW_FORMAT=DYNAMIC DELAY_KEY_WRITE=0;

-----customer pay refund
CREATE TABLE IF NOT EXISTS `clctravel`.`yz_payments_refund` (
`id` bigint(12) NOT NULL AUTO_INCREMENT COMMENT '主键',
`order_id` varchar(36) DEFAULT NULL COMMENT 'order Id to show in the internal system',
`transaction_id` varchar(64)  DEFAULT NULL  COMMENT 'transaction id in payment',
`out_refund_no` varchar(64)  DEFAULT NULL  COMMENT 'the payment number in yz',
`total_fee` decimal(10,2)  DEFAULT NULL COMMENT 'total fee',
`refund_fee` decimal(10,2)  DEFAULT NULL COMMENT 'refound fee',
`return_code` varchar(25)  DEFAULT NULL  COMMENT 'return_code',
`return_msg` varchar(128)  DEFAULT NULL  COMMENT 'return_msg',
`result_code` varchar(25)  DEFAULT NULL  COMMENT 'result_code',
`err_code_des` varchar(128)  DEFAULT NULL  COMMENT 'err_code_des',
`action_user_id` varchar(36) DEFAULT NULL COMMENT 'User Id to take this action',
`update_date` datetime  DEFAULT NULL COMMENT 'update_date',
`creation_date` datetime  DEFAULT NULL COMMENT 'creation_date',
PRIMARY KEY (`id`),
KEY `order_id` (`order_id`),
KEY `transaction_id` (`transaction_id`)
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci CHECKSUM=0 ROW_FORMAT=DYNAMIC DELAY_KEY_WRITE=0;

-----seller pay
CREATE TABLE IF NOT EXISTS `clctravel`.`yz_payments_seller` (
`id` bigint(12) NOT NULL AUTO_INCREMENT COMMENT '主键',
`order_id` varchar(36) DEFAULT NULL COMMENT 'order Id to show in the internal system',
`pattern_trade_no` varchar(64)  DEFAULT NULL  COMMENT 'the yz payment to seller number',
`openid` varchar(100) DEFAULT NULL COMMENT '微信openid',
`amount` decimal(10,2)  DEFAULT NULL COMMENT 'total fee',
`return_code` varchar(25)  DEFAULT NULL  COMMENT 'return_code',
`return_msg` varchar(128)  DEFAULT NULL  COMMENT 'return_msg',
`result_code` varchar(25)  DEFAULT NULL  COMMENT 'result_code',
`err_code_des` varchar(128)  DEFAULT NULL  COMMENT 'err_code_des',
`action_user_id` varchar(36) DEFAULT NULL COMMENT 'User Id to take this action',
`update_date` datetime  DEFAULT NULL COMMENT 'update_date',
`creation_date` datetime  DEFAULT NULL COMMENT 'creation_date',
PRIMARY KEY (`id`),
KEY `order_id` (`order_id`),
KEY `pattern_trade_no` (`pattern_trade_no`)
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci CHECKSUM=0 ROW_FORMAT=DYNAMIC DELAY_KEY_WRITE=0;

--query history
CREATE TABLE IF NOT EXISTS `clctravel`.`yz_query_history` (
`id` bigint(12) NOT NULL AUTO_INCREMENT COMMENT '主键',
`user_id` varchar(36) DEFAULT NULL COMMENT 'user id foreign key',
`key_word` varchar(255)  DEFAULT NULL  COMMENT 'search key',
`status` int(2) DEFAULT 0  COMMENT 'active 0, deleted 1' ,
`creation_date` datetime  DEFAULT NULL COMMENT 'creation datetime',
`update_date` datetime  DEFAULT NULL COMMENT 'update datetime',
PRIMARY KEY (`id`),
KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci CHECKSUM=0 ROW_FORMAT=DYNAMIC DELAY_KEY_WRITE=0;

--Comments
CREATE TABLE IF NOT EXISTS `clctravel`.`yz_comments` (
`id` bigint(12) NOT NULL AUTO_INCREMENT COMMENT '主键',
`service_id` varchar(36) DEFAULT NULL COMMENT 'Service Id to show in the internal system',
`order_id` varchar(36) DEFAULT NULL COMMENT 'order Id to show in the internal system',
`customer_id` varchar(36)  NOT NULL COMMENT 'The people to buy',
`seller_id` varchar(36)  NOT NULL COMMENT 'The people to sell',
`customer_name` varchar(255)  DEFAULT NULL  COMMENT 'customer姓名',
`seller_name` varchar(255)  DEFAULT NULL  COMMENT 'seller姓名',
`comments` text DEFAULT NULL  COMMENT '评论',
`stars` int(3) DEFAULT 3  COMMENT '星',
`creation_date` datetime  DEFAULT NULL COMMENT 'creation datetime',
`update_date` datetime  DEFAULT NULL COMMENT 'update datetime',
PRIMARY KEY (`id`),
KEY `service_id` (`service_id`),
KEY `order_id` (`order_id`),
KEY `customer_id` (`customer_id`),
KEY `seller_id` (`seller_id`)
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci CHECKSUM=0 ROW_FORMAT=DYNAMIC DELAY_KEY_WRITE=0;

--Countries
CREATE TABLE IF NOT EXISTS `clctravel`.`yz_countries` (
`id` bigint(12) NOT NULL AUTO_INCREMENT COMMENT '主键',
`country_name` varchar(50) DEFAULT NULL  COMMENT '服务区域',
`display_sequence` int(5) DEFAULT 0  COMMENT '显示的排序',
`creation_date` datetime  DEFAULT NULL COMMENT 'creation datetime',
`update_date` datetime  DEFAULT NULL COMMENT 'update datetime',
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci CHECKSUM=0 ROW_FORMAT=DYNAMIC DELAY_KEY_WRITE=0;

insert into  `clctravel`.`yz_countries` (country_name, display_sequence) values ('中国',1);
insert into  `clctravel`.`yz_countries` (country_name, display_sequence) values ('美国',2);
insert into  `clctravel`.`yz_countries` (country_name, display_sequence) values ('欧洲',3);
insert into  `clctravel`.`yz_countries` (country_name, display_sequence) values ('亚洲',4);
insert into  `clctravel`.`yz_countries` (country_name, display_sequence) values ('大洋洲',5);
insert into  `clctravel`.`yz_countries` (country_name, display_sequence) values ('非洲',6);
insert into  `clctravel`.`yz_countries` (country_name, display_sequence) values ('两极',8);
insert into  `clctravel`.`yz_countries` (country_name, display_sequence) values ('美洲',7);

--Citys
CREATE TABLE IF NOT EXISTS `clctravel`.`yz_cities` (
`id` bigint(12) NOT NULL AUTO_INCREMENT COMMENT '主键',
`city_name` varchar(50) DEFAULT NULL  COMMENT '城市名称',
`first_char_pinyin` varchar(2) DEFAULT NULL  COMMENT '城市首字母拼音',
`country_id` bigint(12)  NOT NULL COMMENT 'country id foreign key',
`creation_date` datetime  DEFAULT NULL COMMENT 'creation datetime',
`update_date` datetime  DEFAULT NULL COMMENT 'update datetime',
PRIMARY KEY (`id`),
KEY `country_id` (`country_id`)
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci CHECKSUM=0 ROW_FORMAT=DYNAMIC DELAY_KEY_WRITE=0;

insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (1, '北京', 'B');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (1, '上海', 'S');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (1, '广州', 'G');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (1, '杭州', 'H');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (1, '深圳', 'S');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (1, '厦门', 'X');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (1, '南京', 'N');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (1, '成都', 'C');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (1, '青岛', 'Q');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (1, '武汉', 'W');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (1, '西安', 'X');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (1, '天津', 'T');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (1, '重庆', 'C');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (1, '苏州', 'S');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (1, '济南', 'J');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (1, '长沙', 'C');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (1, '香港', 'X');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (1, '大连', 'D');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (1, '三亚', 'S');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (1, '哈尔滨', 'H');

insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (2, '西雅图', 'X');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (2, '拉斯维加斯', 'L');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (2, '纽约', 'N');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (2, '洛杉矶', 'L');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (2, '夏威夷', 'X');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (2, '旧金山', 'J');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (2, '圣地亚哥', 'S');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (2, '芝加哥', 'Z');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (2, '华盛顿', 'H');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (2, '奥兰多', 'A');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (2, '波士顿', 'B');
-- insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (2, '圣保罗', 'S');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (2, '圣路易斯', 'S');

insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (3, '俄罗斯', 'E');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (3, '德国', 'D');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (3, '土耳其', 'T');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (3, '法国', 'F');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (3, '英国', 'Y');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (3, '意大利', 'Y');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (3, '西班牙', 'X');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (3, '乌克兰', 'W');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (3, '波兰', 'B');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (3, '罗马尼亚', 'L');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (3, '荷兰', 'H');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (3, '希腊', 'X');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (3, '葡萄牙', 'P');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (3, '捷克', 'J');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (3, '比利时', 'B');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (3, '瑞典', 'R');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (3, '奥地利', 'A');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (3, '瑞士', 'R');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (3, '斯洛伐克', 'S');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (3, '丹麦', 'D');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (3, '芬兰', 'F');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (3, '挪威', 'N');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (3, '爱尔兰', 'A');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (3, '立陶宛', 'L');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (3, '卢森堡', 'L');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (3, '冰岛', 'B');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (3, '列支敦士登', 'L');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (3, '摩纳哥', 'M');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (3, '梵蒂冈', 'F');

insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (4, '孟加拉国', 'M');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (4, '不丹', 'B');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (4, '文莱', 'W');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (4, '柬埔寨', 'J');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (4, '台湾', 'T');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (4, '香港特别行政区', 'X');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (4, '印度', 'Y');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (4, '印尼', 'Y');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (4, '以色列', 'Y');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (4, '日本', 'R');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (4, '韩国', 'H');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (4, '澳门特别行政区', 'S');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (4, '马来西亚', 'M');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (4, '马尔代夫', 'M');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (4, '菲律宾', 'F');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (4, '沙特阿拉伯', 'S');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (4, '新加坡', 'X');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (4, '泰国', 'T');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (4, '阿联酋', 'A');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (4, '越南', 'Y');

insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (5, '澳大利亚', 'A');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (5, '新西兰', 'X');

insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (6, '埃及', 'A');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (6, '塞舌尔', 'A');

insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (7, '南极', 'N');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (7, '北极', 'B');

insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (8, '巴西', 'B');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (8, '墨西哥', 'M');
insert into  `clctravel`.`yz_cities` (country_id, city_name, first_char_pinyin) values (8, '加拿大', 'J');

--city tags
CREATE TABLE IF NOT EXISTS `clctravel`.`yz_city_tags` (
`id` bigint(12) NOT NULL AUTO_INCREMENT COMMENT '主键',
`city_name` varchar(50) DEFAULT NULL  COMMENT '城市名称',
`service_type` int(10) DEFAULT 1  COMMENT '服务类型 1 旅游, 2 留学, 99999 all, -1 nothing',
`tag` varchar(50) DEFAULT NULL  COMMENT 'tag',
`creation_date` datetime  DEFAULT NULL COMMENT 'creation datetime',
`update_date` datetime  DEFAULT NULL COMMENT 'update datetime',
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci CHECKSUM=0 ROW_FORMAT=DYNAMIC DELAY_KEY_WRITE=0;

insert into  `clctravel`.`yz_city_tags` (city_name, service_type,tag) values('北京', 1, '故宫');
insert into  `clctravel`.`yz_city_tags` (city_name, service_type,tag) values('北京', 1, '长城');
insert into  `clctravel`.`yz_city_tags` (city_name, service_type,tag) values('北京', 1, '颐和园');
insert into  `clctravel`.`yz_city_tags` (city_name, service_type,tag) values('上海', 1, '东方明珠');
insert into  `clctravel`.`yz_city_tags` (city_name, service_type,tag) values('西雅图', 1, 'Space Needle');

--advertise
CREATE TABLE IF NOT EXISTS `clctravel`.`yz_advertises` (
`id` bigint(12) NOT NULL AUTO_INCREMENT COMMENT '主键',
`city_name` varchar(50) DEFAULT NULL  COMMENT '城市名称',
`service_type` int(10) DEFAULT 1  COMMENT '服务类型 1 旅游, 2 留学, 99999 all, -1 nothing',
`service_id` varchar(50) DEFAULT NULL  COMMENT 'service id',
`status` int(2) DEFAULT 0  COMMENT 'active 0, deleted 1' ,
`creation_date` datetime  DEFAULT NULL COMMENT 'creation datetime',
`update_date` datetime  DEFAULT NULL COMMENT 'update datetime',
PRIMARY KEY (`id`),
KEY `city_name` (`city_name`),
KEY `service_type` (`service_type`),
KEY `status` (`status`),
KEY `service_id` (`service_id`)
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci CHECKSUM=0 ROW_FORMAT=DYNAMIC DELAY_KEY_WRITE=0;

--suggestion
CREATE TABLE IF NOT EXISTS `clctravel`.`yz_suggestions` (
`id` bigint(12) NOT NULL AUTO_INCREMENT COMMENT '主键',
`user_id` varchar(36) DEFAULT NULL COMMENT 'The people to sell',
`suggestion` text DEFAULT NULL  COMMENT '意见',
`response` text DEFAULT NULL  COMMENT '意见反馈',
`creation_date` datetime  DEFAULT NULL COMMENT 'creation datetime',
`update_date` datetime  DEFAULT NULL COMMENT 'update datetime',
PRIMARY KEY (`id`),
KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci CHECKSUM=0 ROW_FORMAT=DYNAMIC DELAY_KEY_WRITE=0;


// DB table engines
show table status from clctravel where name ='yz_orders';
alter table yz_orders ENGINE=InnoDB;
alter table yz_advertises ENGINE=InnoDB;
alter table yz_cities ENGINE=InnoDB;
alter table yz_city_tags ENGINE=InnoDB;
alter table yz_comments ENGINE=InnoDB;
alter table yz_countries ENGINE=InnoDB;
alter table yz_order_actions ENGINE=InnoDB;
alter table yz_payments ENGINE=InnoDB;
alter table yz_query_history ENGINE=InnoDB;
alter table yz_services ENGINE=InnoDB;
alter table yz_user_accounts ENGINE=InnoDB;
alter table yz_user_infos ENGINE=InnoDB;
alter table yz_user_settings ENGINE=InnoDB;

// dump tables
mysqldump -uroot -pFreelook2 clctravel yz_orders yz_services yz_user_accounts yz_user_infos yz_user_settings yz_advertises yz_cities yz_city_tags yz_comments yz_countries yz_order_actions yz_payments yz_query_history > /home/www/dbbak/20160827.bak;

