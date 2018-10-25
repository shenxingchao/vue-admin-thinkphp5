/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : vue

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2018-10-25 08:20:58
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for vue_admin
-- ----------------------------
DROP TABLE IF EXISTS `vue_admin`;
CREATE TABLE `vue_admin` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `username` varchar(50) NOT NULL DEFAULT '' COMMENT '管理员账号 ',
  `password` varchar(32) NOT NULL DEFAULT '' COMMENT '账号密码',
  `role_ids` varchar(255) NOT NULL DEFAULT '' COMMENT '角色id字符串',
  `token` varchar(32) NOT NULL DEFAULT '' COMMENT '登录令牌',
  `expire_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'token过期时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of vue_admin
-- ----------------------------
INSERT INTO `vue_admin` VALUES ('3', 'admin', '96e79218965eb72c92a549dd5a330112', '4,9', '', '0');
INSERT INTO `vue_admin` VALUES ('2', 'super', 'e10adc3949ba59abbe56e057f20f883e', '3', '06d206faaca10dd88cd63a9d25854831', '1540513138');

-- ----------------------------
-- Table structure for vue_permission_menu
-- ----------------------------
DROP TABLE IF EXISTS `vue_permission_menu`;
CREATE TABLE `vue_permission_menu` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(30) NOT NULL DEFAULT '' COMMENT '路由标题',
  `path` varchar(255) NOT NULL DEFAULT '' COMMENT '路由路径',
  `component` varchar(50) NOT NULL DEFAULT '' COMMENT '映射组件名称',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '路由名称',
  `redirect` varchar(255) NOT NULL DEFAULT '' COMMENT '重定向地址',
  `icon` varchar(30) NOT NULL DEFAULT '' COMMENT '图标名称',
  `parent_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '上级路由id 0代表顶级路由',
  `hidden` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '显示',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of vue_permission_menu
-- ----------------------------
INSERT INTO `vue_permission_menu` VALUES ('1', '主页', '/', 'Layout', 'index', '/dashboard', 'dashboard', '0', '1');
INSERT INTO `vue_permission_menu` VALUES ('2', '权限管理', '/permission', 'Layout', 'permission', '', 'permission', '0', '1');
INSERT INTO `vue_permission_menu` VALUES ('3', '控制台', 'dashboard', 'dashboard', 'dashboard', '', '', '1', '1');
INSERT INTO `vue_permission_menu` VALUES ('24', '权限资源', 'permission_src', 'permission_src', 'permission_src', '/permission/permission_src/permission_src_lst', 'permission_src', '2', '1');
INSERT INTO `vue_permission_menu` VALUES ('25', '权限资源列表', 'permission_src_lst', 'permission_src_lst', 'permission_src_lst', '', '', '24', '1');
INSERT INTO `vue_permission_menu` VALUES ('26', '添加权限资源', 'permission_src_add', 'permission_src_add', 'permission_src_add', '', '', '24', '2');
INSERT INTO `vue_permission_menu` VALUES ('27', '编辑权限资源', 'permission_src_edit', 'permission_src_edit', 'permission_src_edit', '', '', '24', '2');
INSERT INTO `vue_permission_menu` VALUES ('8', '菜单资源', 'permission_menu', 'permission_menu', 'permission_menu', '/permission/permission_menu/permission_menu_lst', 'permission_menu', '2', '1');
INSERT INTO `vue_permission_menu` VALUES ('9', '菜单资源列表', 'permission_menu_lst', 'permission_menu_lst', 'permission_menu_lst', '', '', '8', '1');
INSERT INTO `vue_permission_menu` VALUES ('10', '添加菜单资源', 'permission_menu_add', 'permission_menu_add', 'permission_menu_add', '', '', '8', '2');
INSERT INTO `vue_permission_menu` VALUES ('17', '角色管理', 'permission_role', 'permission_role', 'permission_role', '/permission/permission_role/permission_role_lst', 'permission_role', '2', '1');
INSERT INTO `vue_permission_menu` VALUES ('15', '编辑菜单资源', 'permission_menu_edit', 'permission_menu_edit', 'permission_menu_edit', '', '', '8', '2');
INSERT INTO `vue_permission_menu` VALUES ('18', '角色列表', 'permission_role_lst', 'permission_role_lst', 'permission_role_lst', '', '', '17', '1');
INSERT INTO `vue_permission_menu` VALUES ('19', '添加角色', 'permission_role_add', 'permission_role_add', 'permission_role_add', '', '', '17', '2');
INSERT INTO `vue_permission_menu` VALUES ('20', '编辑角色', 'permission_role_edit', 'permission_role_edit', 'permission_role_edit', '', '', '17', '2');
INSERT INTO `vue_permission_menu` VALUES ('28', '管理员', 'admin', 'admin', 'admin', '/permission/admin/admin_lst', 'admin', '2', '1');
INSERT INTO `vue_permission_menu` VALUES ('29', '管理员列表', 'admin_lst', 'admin_lst', 'admin_lst', '', '', '28', '1');
INSERT INTO `vue_permission_menu` VALUES ('30', '添加管理员', 'admin_add', 'admin_add', 'admin_add', '', '', '28', '2');
INSERT INTO `vue_permission_menu` VALUES ('31', '编辑管理员', 'admin_edit', 'admin_edit', 'admin_edit', '', '', '28', '2');

-- ----------------------------
-- Table structure for vue_permission_role
-- ----------------------------
DROP TABLE IF EXISTS `vue_permission_role`;
CREATE TABLE `vue_permission_role` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `role_name` varchar(50) NOT NULL DEFAULT '' COMMENT '角色名称',
  `permission_menu_ids` text NOT NULL COMMENT '菜单id字符串',
  `temp_menu_ids` text NOT NULL COMMENT '选中状态的id（不包含半选中id）',
  `permission_src_ids` text NOT NULL COMMENT '权限id字符串',
  `temp_src_ids` text NOT NULL COMMENT '选中状态的id（不包含半选中id）',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of vue_permission_role
-- ----------------------------
INSERT INTO `vue_permission_role` VALUES ('3', '超级管理员', '1,3,2,24,25,26,27,8,9,10,15,17,18,19,20,28,29,30,31', '1,3,2,24,25,26,27,8,9,10,15,17,18,19,20,28,29,30,31', '2,3,11,12,13,5,10,8,9,6,14,15,16,17,18,19,21', '2,3,11,12,13,5,10,8,9,6,14,15,16,17,18,19,21');
INSERT INTO `vue_permission_role` VALUES ('4', '访客', '1,3', '1,3', '', '');
INSERT INTO `vue_permission_role` VALUES ('9', '访客2', '2,24,25,26,27,8,9,10,15,17,18,19,20,28,29,30,31', '2,24,25,26,27,8,9,10,15,17,18,19,20,28,29,30,31', '', '');

-- ----------------------------
-- Table structure for vue_permission_src
-- ----------------------------
DROP TABLE IF EXISTS `vue_permission_src`;
CREATE TABLE `vue_permission_src` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `title` varchar(255) NOT NULL,
  `parent_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父权限组id',
  `permission_code` text NOT NULL COMMENT '权限码 序列化数组',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of vue_permission_src
-- ----------------------------
INSERT INTO `vue_permission_src` VALUES ('2', '权限管理', '0', '');
INSERT INTO `vue_permission_src` VALUES ('3', '权限资源', '2', '');
INSERT INTO `vue_permission_src` VALUES ('5', '菜单资源', '2', '');
INSERT INTO `vue_permission_src` VALUES ('6', '角色管理', '2', '');
INSERT INTO `vue_permission_src` VALUES ('10', '删除', '5', 'a:1:{i:0;a:2:{s:15:\"controller_name\";s:10:\"Permission\";s:11:\"action_name\";s:20:\"permissionMenuDelete\";}}');
INSERT INTO `vue_permission_src` VALUES ('8', '添加', '5', 'a:1:{i:0;a:2:{s:15:\"controller_name\";s:10:\"Permission\";s:11:\"action_name\";s:17:\"permissionMenuAdd\";}}');
INSERT INTO `vue_permission_src` VALUES ('11', '添加', '3', 'a:1:{i:0;a:2:{s:15:\"controller_name\";s:10:\"Permission\";s:11:\"action_name\";s:16:\"permissionSrcAdd\";}}');
INSERT INTO `vue_permission_src` VALUES ('9', '编辑', '5', 'a:1:{i:0;a:2:{s:15:\"controller_name\";s:10:\"Permission\";s:11:\"action_name\";s:18:\"permissionMenuEdit\";}}');
INSERT INTO `vue_permission_src` VALUES ('12', '编辑', '3', 'a:1:{i:0;a:2:{s:15:\"controller_name\";s:10:\"Permission\";s:11:\"action_name\";s:17:\"permissionSrcEdit\";}}');
INSERT INTO `vue_permission_src` VALUES ('13', '删除', '3', 'a:1:{i:0;a:2:{s:15:\"controller_name\";s:10:\"Permission\";s:11:\"action_name\";s:19:\"permissionSrcDelete\";}}');
INSERT INTO `vue_permission_src` VALUES ('14', '添加', '6', 'a:1:{i:0;a:2:{s:15:\"controller_name\";s:10:\"Permission\";s:11:\"action_name\";s:17:\"permissionRoleAdd\";}}');
INSERT INTO `vue_permission_src` VALUES ('15', '编辑', '6', 'a:1:{i:0;a:2:{s:15:\"controller_name\";s:10:\"Permission\";s:11:\"action_name\";s:18:\"permissionRoleEdit\";}}');
INSERT INTO `vue_permission_src` VALUES ('16', '删除', '6', 'a:1:{i:0;a:2:{s:15:\"controller_name\";s:10:\"Permission\";s:11:\"action_name\";s:20:\"permissionRoleDelete\";}}');
INSERT INTO `vue_permission_src` VALUES ('17', '管理员', '2', '');
INSERT INTO `vue_permission_src` VALUES ('18', '添加', '17', 'a:1:{i:0;a:2:{s:15:\"controller_name\";s:10:\"Permission\";s:11:\"action_name\";s:8:\"adminAdd\";}}');
INSERT INTO `vue_permission_src` VALUES ('19', '编辑', '17', 'a:1:{i:0;a:2:{s:15:\"controller_name\";s:10:\"Permission\";s:11:\"action_name\";s:9:\"adminEdit\";}}');
INSERT INTO `vue_permission_src` VALUES ('21', '删除', '17', 'a:1:{i:0;a:2:{s:15:\"controller_name\";s:10:\"Permission\";s:11:\"action_name\";s:11:\"adminDelete\";}}');
