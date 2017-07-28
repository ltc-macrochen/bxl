#drop database if exists `db_baoxiaolv`;
create database `db_baoxiaolv`;
use `db_baoxiaolv`;
set names utf8;


###############################################################################
###########################      管理员用户系统      ###########################
###############################################################################

DROP TABLE IF EXISTS `t_admin_user`;
CREATE TABLE `t_admin_user` (
  `id`              INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `roleId`          TINYINT UNSIGNED NOT NULL DEFAULT 0 COMMENT '角色ID',  
  `name`            VARCHAR(255) NOT NULL COMMENT '用户名',
  `password`        VARCHAR(255) NOT NULL COMMENT '用户密码',
  `realName`        VARCHAR(255) NOT NULL COMMENT '用户真实姓名',
  `mobile`          VARCHAR(255) DEFAULT '' COMMENT '用户手机号',
  `email`           VARCHAR(255) DEFAULT '' COMMENT '用户邮箱',
  `status`          TINYINT UNSIGNED NOT NULL DEFAULT 0 COMMENT '审核状态',
  `config`          TEXT DEFAULT '' COMMENT '个性配置',
  `lastLoginIp`     VARCHAR(16) DEFAULT '' COMMENT '用户最近一次登录IP',
  `lastLoginTime`   DATETIME DEFAULT '0000-00-00 00:00:00' COMMENT '用户最近一次登录时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `INDEX_NAME` (`name`),
  INDEX `INDEX_ROLEID` (`roleId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

DROP TABLE IF EXISTS `t_admin_role`;
CREATE TABLE `t_admin_role` (
  `id`              INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '角色ID',
  `name`            VARCHAR(255) NOT NULL COMMENT '角色名称',
  `description`     TEXT DEFAULT '' COMMENT '角色描述',
  PRIMARY KEY (`id`),
  UNIQUE KEY `INDEX_NAME` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `t_admin_role` (`id`, `name`, `description`) VALUES
(1, '系统管理员', '具有全部系统操作权限'),
(2, '普通管理员', '除数据录入和系统配置外的操作权限');

INSERT INTO `t_admin_user` (`id`, `roleId`, `name`, `password`, `realName`, `mobile`, `email`, `status`, `lastLoginIp`, `lastLoginTime`) VALUES
(1, 1, 'admin', 'admin', '超级管理员', '', '', 0, '', '2015-06-18 06:18:00');

###############################################################################
###########################       数据统计相关表       ##########################
###############################################################################

##用户表
drop table if exists `t_user`;
create table `t_user` (
  `id`            int unsigned not null auto_increment comment '用户ID',
  `roleId`        tinyint unsigned not null default 0 COMMENT '用户身份',
  `openId`        varchar(32) not null comment 'OpenId',
  `nick`          varchar(32) not null comment '昵称',
  `head`          varchar(255) not null comment '头像',
  `name`          varchar(32) not null comment '姓名',
  `title`         varchar(32) not null comment '头衔',
  `sex`           varchar(8) not null comment '性别',
  `desc`          varchar(255) not null comment '介绍',
  `email`         varchar(255) not null comment '邮箱',
  `mobile`        varchar(32) not null comment '手机号',
  `status`        tinyint unsigned not null default 0 comment '审核状态',
  `registerTime`  datetime not null default '00-00-00 00:00:00' comment '注册时间',
  `loginTime`     datetime not null default '00-00-00 00:00:00' comment '最近登录时间',
  `blockEndTime`  datetime not null default '00-00-00 00:00:00' comment '禁言结束时间',
  primary key (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

##文章分类表
drop table if exists `t_cms_category`;
create table `t_cms_category` (
    `id`                int unsigned not null auto_increment comment '记录ID',
    `name`              varchar(64) not null comment '分类名称',
    `description`       varchar(255) not null comment '分类描述',
    `thumb`             varchar(255) not null comment '缩略图',
    `status`            tinyint unsigned not null default 0 comment '审核状态',
    `createTime`        datetime not null comment '创建时间',
    `updateTime`        datetime not null comment '更新时间',
    primary key (`id`),
    index `INDEX_STATUS`(`status`)
) ENGINE=InnoDB default CHARSET=utf8 COLLATE=utf8_general_ci;

##文章
drop table if exists `t_cms_post`;
create table `t_cms_post` (
  `id`              int unsigned not null auto_increment comment '内容ID',
  `catId`           int unsigned not null comment '所属分类',
  `userId`          int unsigned not null comment '用户ID',
  `title`           varchar(255) not null default '' comment '标题',
  `description`     varchar(255) not null comment '描述',
  `content`         text not null comment '文章',
  `link`            varchar(255) not null comment '外链',
  `imgUrl`          varchar(255) not null comment '图片地址',
  `audioUrl`        varchar(255) not null comment '音频地址',
  `videoUrl`        varchar(255) not null comment '视频地址',
  `status`          tinyint unsigned not null comment '审核状态',
  `viewCount`       int unsigned not null comment '浏览次数',
  `commentCount`    int unsigned not null comment '评论数',
  `vGood`           int unsigned not null comment '点赞数',
  `vBad`            int unsigned not null comment '点踩数',
  `createTime`      datetime not null comment '创建时间',
  `updateTime`      datetime not null comment '更新时间',
  primary key (`id`),
  INDEX `INDEX_CATID_STATUS` (`catId`, `status`)
) ENGINE=InnoDB default CHARSET=utf8 COLLATE=utf8_general_ci;
