/*
Navicat MySQL Data Transfer

Source Server         : 192.168.199.140
Source Server Version : 50557
Source Host           : localhost:3306
Source Database       : codecheck

Target Server Type    : MYSQL
Target Server Version : 50557
File Encoding         : 65001

Date: 2017-09-01 09:35:07
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for obsec_admin_user
-- ----------------------------
DROP TABLE IF EXISTS `obsec_admin_user`;
CREATE TABLE `obsec_admin_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `user_name` varchar(20) DEFAULT NULL COMMENT '用户名',
  `password` varchar(200) DEFAULT NULL COMMENT '密码',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for obsec_scan_data
-- ----------------------------
DROP TABLE IF EXISTS `obsec_scan_data`;
CREATE TABLE `obsec_scan_data` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `summary_id` int(11) DEFAULT NULL COMMENT '概述表关联id',
  `leak_name` varchar(100) DEFAULT NULL COMMENT '漏洞名称类型',
  `file_type_name` varchar(50) DEFAULT NULL COMMENT '文件类型名称',
  `leak_grade` tinyint(2) DEFAULT NULL COMMENT '漏洞等级 1.高，2.中，3.低，4.忽略',
  `leak_file_pos` varchar(255) DEFAULT NULL COMMENT '漏洞文件路径',
  `leak_line_num` int(11) DEFAULT NULL COMMENT '漏洞代码行号',
  `code_part` text COMMENT '代码片段',
  `leak_defect_des` text COMMENT '漏洞缺陷描述',
  `leak_modify_sug` text COMMENT '漏洞修改建议',
  `leak_audit_res` text COMMENT '用户漏洞审计结果',
  `leak_sort` enum('0','1') DEFAULT '0' COMMENT '排序是否审计过 未审计0  已审计1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27597 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for obsec_scan_summary
-- ----------------------------
DROP TABLE IF EXISTS `obsec_scan_summary`;
CREATE TABLE `obsec_scan_summary` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `upinfo_id` int(11) DEFAULT NULL COMMENT '文件上传信息表关联id',
  `leak_file_type` varchar(255) DEFAULT NULL COMMENT '漏洞文件类型集合',
  `code_line_num` varchar(50) DEFAULT '0' COMMENT '代码总行数',
  `leak_num` varchar(50) DEFAULT '0' COMMENT '漏洞总个数',
  `leak_file_num` varchar(50) DEFAULT '0' COMMENT '漏洞文件类型总个数',
  `leak_defect_num` varchar(50) DEFAULT '0' COMMENT '漏洞缺陷种类个数',
  `scan_status` tinyint(2) DEFAULT '0' COMMENT '扫描状态 1.成功，2失败，3扫描中',
  `scan_start_time` datetime DEFAULT NULL COMMENT '扫描开始时间',
  `scan_end_time` datetime DEFAULT NULL COMMENT '扫描结束时间',
  `scan_sped` varchar(10) DEFAULT NULL COMMENT '扫描进度百分比',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8 COMMENT='扫描数据概述表';

-- ----------------------------
-- Table structure for obsec_upload_data
-- ----------------------------
DROP TABLE IF EXISTS `obsec_upload_data`;
CREATE TABLE `obsec_upload_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `user_id` int(11) DEFAULT NULL COMMENT '用户关联id',
  `upload_size` int(11) DEFAULT NULL COMMENT '上传文件大小，最大值单位MB',
  `upload_num` int(11) DEFAULT NULL COMMENT '上传文件最大数量，单位个',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8 COMMENT='用户上传大小限制';

-- ----------------------------
-- Table structure for obsec_upload_info
-- ----------------------------
DROP TABLE IF EXISTS `obsec_upload_info`;
CREATE TABLE `obsec_upload_info` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `user_id` int(11) DEFAULT NULL COMMENT '用户表关联id',
  `task_name` varchar(50) DEFAULT NULL COMMENT '任务名称',
  `upload_orig_name` varchar(100) DEFAULT NULL COMMENT '用户上传原文件名称',
  `upload_file_name` varchar(255) DEFAULT NULL COMMENT '用户上传处理后的文件名称',
  `upload_file_path` varchar(255) DEFAULT NULL COMMENT '文件上传路径',
  `upload_file_size` varchar(50) DEFAULT NULL COMMENT '上传文件大小',
  `upload_time` datetime DEFAULT NULL COMMENT '文件上传时间',
  `upload_date` varchar(50) DEFAULT NULL COMMENT '文件上传日期',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8 COMMENT='用户上传文件信息表';

-- ----------------------------
-- Table structure for obsec_user
-- ----------------------------
DROP TABLE IF EXISTS `obsec_user`;
CREATE TABLE `obsec_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `user_name` varchar(100) NOT NULL COMMENT '用户名',
  `corporate_name` varchar(100) DEFAULT NULL COMMENT '公司名称',
  `phone` varchar(20) DEFAULT NULL COMMENT '手机号',
  `email` varchar(50) DEFAULT NULL COMMENT '邮箱',
  `password` varchar(100) NOT NULL COMMENT '密码',
  `register_type` tinyint(2) DEFAULT NULL COMMENT '注册用户类型 1：个人用户 2：企业用户 3:系统用户',
  `register_time` datetime NOT NULL COMMENT '注册时间',
  `register_date` varchar(50) DEFAULT NULL COMMENT '注册日期',
  `switch_type` tinyint(2) NOT NULL DEFAULT '1' COMMENT '是否启用 1：启用 2：未启用',
  `content` varchar(100) DEFAULT NULL COMMENT '用户信息备注',
  `session_id` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user` (`user_name`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COMMENT='用户表';
