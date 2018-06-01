/*
Navicat MySQL Data Transfer

Source Server         : 本地数据库
Source Server Version : 50547
Source Host           : localhost:3306
Source Database       : loveteemoblog

Target Server Type    : MYSQL
Target Server Version : 50547
File Encoding         : 65001

Date: 2017-03-27 16:11:49
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `lt_article`
-- ----------------------------
DROP TABLE IF EXISTS `lt_article`;
CREATE TABLE `lt_article` (
  `art_id` int(11) NOT NULL AUTO_INCREMENT,
  `art_title` varchar(128) NOT NULL COMMENT '文章标题',
  `art_img` varchar(128) NOT NULL COMMENT '缩略图',
  `art_remark` varchar(256) NOT NULL COMMENT '描述',
  `art_keyword` varchar(64) NOT NULL COMMENT '关键词',
  `art_pid` int(11) NOT NULL COMMENT '关联栏目ID',
  `art_down` tinyint(2) DEFAULT '0' COMMENT '1为附件 ',
  `art_file` varchar(255) DEFAULT NULL COMMENT '附件路径',
  `art_addtime` int(10) NOT NULL COMMENT '时间戳格式',
  `art_content` text NOT NULL COMMENT '内容',
  `art_view` tinyint(2) NOT NULL COMMENT '显示，0为草稿，1为显示，2为推荐，-1为删除(逻辑)',
  `art_collection` int(11) NOT NULL DEFAULT '0' COMMENT '收藏|喜欢数量',
  `art_hit` int(11) NOT NULL COMMENT '点击量',
  `art_url` varchar(128) NOT NULL COMMENT '非原创的转载地址',
  `art_original` tinyint(2) NOT NULL COMMENT '是否原创，0为不是，1为是',
  `art_from` varchar(128) NOT NULL COMMENT '来自',
  `art_author` varchar(32) NOT NULL COMMENT '作者',
  `art_city` varchar(16) NOT NULL COMMENT '城市',
  `art_downloadnums` int(11) DEFAULT '0' COMMENT '下载次数',
  PRIMARY KEY (`art_id`),
  KEY `a_title` (`art_title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='冷月博客文章发布表';

-- ----------------------------
-- Records of lt_article
-- ----------------------------

-- ----------------------------
-- Table structure for `lt_banner`
-- ----------------------------
DROP TABLE IF EXISTS `lt_banner`;
CREATE TABLE `lt_banner` (
  `ban_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `ban_url` varchar(128) DEFAULT NULL COMMENT '指向地址',
  `ban_img` varchar(128) DEFAULT NULL COMMENT '图片地址',
  `ban_view` tinyint(2) DEFAULT '1' COMMENT '是否显示 1显示 0隐藏',
  `ban_title` varchar(128) DEFAULT NULL COMMENT '描述/标题',
  `ban_sort` tinyint(3) DEFAULT NULL COMMENT '排序 从大到小',
  `ban_createtime` datetime DEFAULT NULL COMMENT '添加时间',
  PRIMARY KEY (`ban_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='banner图';

-- ----------------------------
-- Records of lt_banner
-- ----------------------------
INSERT INTO `lt_banner` VALUES ('1', '#', '/static/home/img/banner/a1.jpg', '1', '测试1', '1', null);
INSERT INTO `lt_banner` VALUES ('2', '#', '/static/home/img/banner/a2.jpg', '1', '测试1', '100', null);
INSERT INTO `lt_banner` VALUES ('3', '#', '/static/home/img/banner/a3.jpg', '1', '测试1', '100', null);

-- ----------------------------
-- Table structure for `lt_comment`
-- ----------------------------
DROP TABLE IF EXISTS `lt_comment`;
CREATE TABLE `lt_comment` (
  `com_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `com_artid` int(11) NOT NULL DEFAULT '0' COMMENT '文章ID 为0则为留言评论',
  `com_userid` int(11) NOT NULL COMMENT '昵称',
  `com_content` text NOT NULL COMMENT '文本',
  `com_addtime` int(10) NOT NULL DEFAULT '0' COMMENT '时间',
  `com_from` varchar(64) NOT NULL COMMENT '来自',
  `com_city` varchar(255) DEFAULT NULL COMMENT '评论地址',
  `com_ip` varchar(16) NOT NULL COMMENT 'IP',
  `com_view` tinyint(3) DEFAULT NULL COMMENT '0 待审核 1显示',
  `com_rtime` int(10) DEFAULT '0' COMMENT '回复时间',
  `com_rcontent` text COMMENT '内容',
  `com_status` tinyint(3) DEFAULT '0' COMMENT '是否回复 0为未回复',
  PRIMARY KEY (`com_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='留言表';

-- ----------------------------
-- Records of lt_comment
-- ----------------------------

-- ----------------------------
-- Table structure for `lt_link`
-- ----------------------------
DROP TABLE IF EXISTS `lt_link`;
CREATE TABLE `lt_link` (
  `link_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `link_name` varchar(128) NOT NULL COMMENT '申请人',
  `link_url` varchar(128) NOT NULL COMMENT '域名',
  `link_content` varchar(128) NOT NULL COMMENT '描述',
  `link_sort` tinyint(3) NOT NULL DEFAULT '100' COMMENT '排序1为第一',
  `link_view` tinyint(2) NOT NULL COMMENT '显示0不显示1显示',
  `link_favicon` varchar(128) DEFAULT '/favicon.ico' COMMENT '图标地址',
  PRIMARY KEY (`link_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='友情链接表';

-- ----------------------------
-- Records of lt_link
-- ----------------------------
INSERT INTO `lt_link` VALUES ('1', '冷月博客', 'loveteemo.com', '测试描述', '100', '1', '/favicon.ico');

-- ----------------------------
-- Table structure for `lt_member`
-- ----------------------------
DROP TABLE IF EXISTS `lt_member`;
CREATE TABLE `lt_member` (
  `mem_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `mem_sex` tinyint(2) DEFAULT NULL COMMENT '1 男 2女',
  `mem_name` varchar(128) NOT NULL COMMENT '昵称',
  `mem_img` varchar(128) NOT NULL COMMENT '头像',
  `mem_openid` varchar(64) NOT NULL COMMENT '用户唯一识别标志',
  `mem_logintime` int(10) NOT NULL COMMENT '时间',
  `mem_loginnum` int(11) DEFAULT '0' COMMENT '登陆次数',
  `mem_auth` tinyint(2) DEFAULT NULL COMMENT '1为管理员',
  PRIMARY KEY (`mem_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='QQ访客表';

-- ----------------------------
-- Records of lt_member
-- ----------------------------

-- ----------------------------
-- Table structure for `lt_menu`
-- ----------------------------
DROP TABLE IF EXISTS `lt_menu`;
CREATE TABLE `lt_menu` (
  `menu_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `menu_parent` int(11) DEFAULT NULL COMMENT '父级标签',
  `menu_url` varchar(128) DEFAULT NULL COMMENT '指向url地址',
  `menu_name` varchar(128) NOT NULL COMMENT '栏目名称',
  `menu_createtime` datetime DEFAULT NULL COMMENT '创建时间',
  `menu_sort` int(11) NOT NULL DEFAULT '100' COMMENT '排序',
  `menu_view` int(11) NOT NULL COMMENT '显示0不显示1显示',
  `menu_remark` varchar(256) NOT NULL COMMENT '描述',
  PRIMARY KEY (`menu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='栏目表';

-- ----------------------------
-- Records of lt_menu
-- ----------------------------
INSERT INTO `lt_menu` VALUES ('1', '8', 'Cate/index', 'PHP', '2016-09-09 11:03:37', '99', '1', 'PHP笔记');
INSERT INTO `lt_menu` VALUES ('2', '8', 'Cate/index', 'HTML', '2016-09-09 11:03:39', '101', '1', 'HTML');
INSERT INTO `lt_menu` VALUES ('3', '8', 'Cate/index', 'ThinkPHP', '2016-09-09 11:03:40', '100', '1', 'ThinkPHP用法总结');
INSERT INTO `lt_menu` VALUES ('4', '8', 'Cate/index', 'Other', '2016-09-09 11:03:42', '100', '1', '其他');
INSERT INTO `lt_menu` VALUES ('5', '8', 'Cate/index', 'Blog', '2016-09-09 11:03:44', '100', '1', 'Blog');
INSERT INTO `lt_menu` VALUES ('6', '0', 'Index/index', '首页', '2016-09-09 10:44:17', '100', '1', '首页');
INSERT INTO `lt_menu` VALUES ('7', '0', 'About/index', '关于', '2016-09-09 10:44:45', '100', '1', '关于我');
INSERT INTO `lt_menu` VALUES ('8', '0', 'Cate/index', '分类', '2016-09-09 11:03:19', '100', '1', '分类');
INSERT INTO `lt_menu` VALUES ('9', '0', 'Links/index', '邻居', '2016-09-09 11:04:26', '100', '1', '邻居');
INSERT INTO `lt_menu` VALUES ('10', '0', 'Comment/index', '留言', '2016-09-09 11:05:09', '100', '1', '留言');
INSERT INTO `lt_menu` VALUES ('11', '0', 'Download/index', '资源', '2016-09-09 11:05:55', '100', '1', '资源');
INSERT INTO `lt_menu` VALUES ('12', '0', 'Tool/index', '工具', '2016-09-09 11:41:52', '100', '1', '工具');

-- ----------------------------
-- Table structure for `lt_sourceslog`
-- ----------------------------
DROP TABLE IF EXISTS `lt_sourceslog`;
CREATE TABLE `lt_sourceslog` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '表主键',
  `art_id` int(11) DEFAULT NULL COMMENT '文章表主键',
  `mem_id` int(11) DEFAULT NULL COMMENT '会员表主键',
  `addtime` datetime DEFAULT NULL COMMENT '记录时间',
  `ip` varchar(16) DEFAULT NULL COMMENT 'IP地址',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of lt_sourceslog
-- ----------------------------

-- ----------------------------
-- Table structure for `lt_system`
-- ----------------------------
DROP TABLE IF EXISTS `lt_system`;
CREATE TABLE `lt_system` (
  `sys_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `sys_title` varchar(128) NOT NULL COMMENT '标题',
  `sys_title2` varchar(128) NOT NULL COMMENT '次级标题',
  `sys_keyword` varchar(128) NOT NULL COMMENT '关键词',
  `sys_remark` varchar(256) NOT NULL COMMENT '描述',
  `sys_createtime` date NOT NULL COMMENT '创建时间',
  `sys_icp` varchar(32) NOT NULL COMMENT '备案',
  `sys_copy` varchar(128) NOT NULL COMMENT '版权',
  `sys_footer` text NOT NULL COMMENT '统计',
  `sys_hits` int(11) NOT NULL COMMENT '访问',
  `sys_adurl` text COMMENT '广告代码',
  `sys_version` varchar(16) DEFAULT NULL COMMENT '版本号',
  PRIMARY KEY (`sys_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='系统基本表';

-- ----------------------------
-- Records of lt_system
-- ----------------------------
INSERT INTO `lt_system` VALUES ('1', '冷月博客', '青春因为爱情而美丽', '青春,爱情,博客,thinkphp,bootstrap3', '青春因为爱情而美丽，欢迎来访~', '2013-12-31', '鄂ICP备15000791号-1', '© 2013 - 2016 冷月博客 & 版权所有 ', '<a href=\"http://dev.loveteemo.com/admin\" target=\"_blank\">管理登陆</a>', '2', null, '3.0');

-- ----------------------------
-- Table structure for `lt_tip`
-- ----------------------------
DROP TABLE IF EXISTS `lt_tip`;
CREATE TABLE `lt_tip` (
  `tip_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `tip_title` varchar(128) DEFAULT NULL COMMENT '提示文字',
  `tip_addtime` datetime DEFAULT NULL COMMENT '添加时间',
  `tip_view` tinyint(3) DEFAULT NULL COMMENT '是否显示 0不显示 1显示',
  `tip_sort` tinyint(3) DEFAULT NULL COMMENT '排序',
  PRIMARY KEY (`tip_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of lt_tip
-- ----------------------------
INSERT INTO `lt_tip` VALUES ('1', '测试滚动文字1', '2016-11-15 09:42:56', '0', '100');
INSERT INTO `lt_tip` VALUES ('2', '测试滚动文字2', '2016-11-15 09:43:33', '1', '100');
INSERT INTO `lt_tip` VALUES ('3', '测试滚动文字3', '2016-11-15 09:43:45', '1', '98');

-- ----------------------------
-- Table structure for `lt_version`
-- ----------------------------
DROP TABLE IF EXISTS `lt_version`;
CREATE TABLE `lt_version` (
  `ver_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `ver_bate` varchar(16) NOT NULL COMMENT '版本号',
  `ver_text` varchar(256) NOT NULL COMMENT '描述',
  `ver_addtime` datetime NOT NULL COMMENT '时间',
  PRIMARY KEY (`ver_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='版本表';

-- ----------------------------
-- Records of lt_version
-- ----------------------------
