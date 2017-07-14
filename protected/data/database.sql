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

