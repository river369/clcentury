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