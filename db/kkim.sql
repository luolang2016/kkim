/*
 Navicat Premium Data Transfer

 Source Server         : 192.168.1.192
 Source Server Type    : MySQL
 Source Server Version : 50733
 Source Host           : 192.168.1.192:3306
 Source Schema         : kkim

 Target Server Type    : MySQL
 Target Server Version : 50733
 File Encoding         : 65001

 Date: 06/06/2021 11:14:22
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for kkim_admin
-- ----------------------------
DROP TABLE IF EXISTS `kkim_admin`;
CREATE TABLE `kkim_admin`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `account` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '账号',
  `password` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '密码',
  `salt` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '密码盐值',
  `name` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '姓名或称呼',
  `mail` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '邮箱',
  `create_time` int(10) UNSIGNED NULL DEFAULT 0 COMMENT '创建时间',
  `last_login_time` int(10) UNSIGNED NULL DEFAULT 0 COMMENT '上一次登录时间',
  `last_login_ip` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '上一次登录IP',
  `login_count` int(10) UNSIGNED NULL DEFAULT 0 COMMENT '登录次数',
  `status` enum('normal','disable') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'normal' COMMENT '状态:normal=正常,disable=禁用',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `account`(`account`) USING BTREE,
  INDEX `create_time`(`create_time`) USING BTREE,
  INDEX `status`(`status`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '管理员表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of kkim_admin
-- ----------------------------
INSERT INTO `kkim_admin` VALUES (1, 'admin', '38752606BC1D9D777FE71CC2D9A1E01F', 'd3Wg5K', '龙王', 'longking@qq.com', 1621008846, 1622943436, '192.168.1.16', 45, 'normal');

-- ----------------------------
-- Table structure for kkim_chat_record
-- ----------------------------
DROP TABLE IF EXISTS `kkim_chat_record`;
CREATE TABLE `kkim_chat_record`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `rec_type` tinyint(1) UNSIGNED NULL DEFAULT 0 COMMENT '类型:0=私聊,1=群聊',
  `group_id` int(10) UNSIGNED NULL DEFAULT 0 COMMENT '群ID',
  `sender_id` int(10) UNSIGNED NULL DEFAULT 0 COMMENT '发送人ID',
  `receive_id` int(10) UNSIGNED NULL DEFAULT 0 COMMENT '接收人ID',
  `content` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '聊天内容',
  `create_time` int(10) UNSIGNED NULL DEFAULT 0 COMMENT '发送时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `group_id`(`group_id`) USING BTREE,
  INDEX `sender_id`(`sender_id`) USING BTREE,
  INDEX `receive_id`(`receive_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 51 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '聊天记录表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of kkim_chat_record
-- ----------------------------
INSERT INTO `kkim_chat_record` VALUES (1, 0, 0, 156373, 156375, '你好啊，你这只小粉兔', 1622860304);
INSERT INTO `kkim_chat_record` VALUES (2, 0, 0, 156375, 156373, '哈喽，你好', 1622860342);
INSERT INTO `kkim_chat_record` VALUES (3, 0, 0, 156373, 156375, '不错不错', 1622860359);
INSERT INTO `kkim_chat_record` VALUES (4, 0, 0, 156375, 156373, 'abc', 1622860448);
INSERT INTO `kkim_chat_record` VALUES (5, 0, 0, 156373, 156374, '你来了？', 1622860487);
INSERT INTO `kkim_chat_record` VALUES (6, 0, 0, 156374, 156373, '是的，你还好吗？', 1622860511);
INSERT INTO `kkim_chat_record` VALUES (7, 0, 0, 156374, 156373, '今天 天气真好', 1622860543);
INSERT INTO `kkim_chat_record` VALUES (8, 0, 0, 156373, 156374, '你说呀', 1622861732);
INSERT INTO `kkim_chat_record` VALUES (9, 0, 0, 156373, 156375, '加上文件显示', 1622868649);
INSERT INTO `kkim_chat_record` VALUES (10, 0, 0, 156373, 156375, '看觉得如何？', 1622868661);
INSERT INTO `kkim_chat_record` VALUES (11, 0, 0, 156373, 156375, '发送过来看看', 1622868677);
INSERT INTO `kkim_chat_record` VALUES (12, 0, 0, 156373, 156375, '消息过多了', 1622868705);
INSERT INTO `kkim_chat_record` VALUES (13, 0, 0, 156373, 156375, '不能发送？', 1622868715);
INSERT INTO `kkim_chat_record` VALUES (14, 0, 0, 156375, 156373, '为什么会有提示？', 1622868747);
INSERT INTO `kkim_chat_record` VALUES (15, 0, 0, 156375, 156373, '不能在特定情况下', 1622868764);
INSERT INTO `kkim_chat_record` VALUES (16, 0, 0, 156376, 156377, '我是阿运', 1622868991);
INSERT INTO `kkim_chat_record` VALUES (17, 0, 0, 156377, 156376, '你好我是阿哲', 1622869014);
INSERT INTO `kkim_chat_record` VALUES (18, 0, 0, 156376, 156377, '运发来的消息', 1622869293);
INSERT INTO `kkim_chat_record` VALUES (19, 0, 0, 156377, 156376, '哲发去的消息', 1622869313);
INSERT INTO `kkim_chat_record` VALUES (20, 0, 0, 156376, 156377, '今天天气真是不错啊', 1622889383);
INSERT INTO `kkim_chat_record` VALUES (21, 0, 0, 156376, 156377, '去哪里玩好呢？', 1622890684);
INSERT INTO `kkim_chat_record` VALUES (22, 0, 0, 156376, 156377, '有点无聊', 1622890787);
INSERT INTO `kkim_chat_record` VALUES (23, 0, 0, 156376, 156377, '怎么不回复啊？', 1622892048);
INSERT INTO `kkim_chat_record` VALUES (24, 0, 0, 156377, 156376, '不好意思，刚才忙了', 1622892076);
INSERT INTO `kkim_chat_record` VALUES (25, 0, 0, 156376, 156377, '现在预约那个疫苗预防针打，好难预约啊，人可真是多，在线预约点都点不进去，一直卡死，到深更半夜能点进去了，全都满了，好难啊，你说这可咋办好呢？', 1622892288);
INSERT INTO `kkim_chat_record` VALUES (26, 0, 0, 156377, 156376, '是的是的，我也一样，真是太骓了', 1622892328);
INSERT INTO `kkim_chat_record` VALUES (27, 0, 0, 156376, 156377, '印度那边不知情况怎样了，希望能变好起来，不然不光对印度本身，对世界也是个灾难。', 1622892708);
INSERT INTO `kkim_chat_record` VALUES (28, 0, 0, 156376, 156377, '希望一切变好。', 1622894022);
INSERT INTO `kkim_chat_record` VALUES (29, 0, 0, 156377, 156376, '希望一切变好', 1622894040);
INSERT INTO `kkim_chat_record` VALUES (30, 0, 0, 156376, 156377, '明天会更好', 1622894280);
INSERT INTO `kkim_chat_record` VALUES (31, 0, 0, 156377, 156376, '希望明天会更好', 1622894301);
INSERT INTO `kkim_chat_record` VALUES (32, 0, 0, 156377, 156376, '相信更好的明天会来的\n加油！', 1622894404);
INSERT INTO `kkim_chat_record` VALUES (33, 0, 0, 156376, 156377, '真想唱首歌', 1622909075);
INSERT INTO `kkim_chat_record` VALUES (34, 0, 0, 156375, 156376, '这只是测试消息', 1622909592);
INSERT INTO `kkim_chat_record` VALUES (35, 0, 0, 156377, 156376, '唱来', 1622909653);
INSERT INTO `kkim_chat_record` VALUES (36, 0, 0, 156375, 156376, '测试消息2', 1622909700);
INSERT INTO `kkim_chat_record` VALUES (37, 0, 0, 156376, 156377, '嘻嘻哈哈', 1622909860);
INSERT INTO `kkim_chat_record` VALUES (38, 0, 0, 156376, 156375, '给你回复消息了', 1622909950);
INSERT INTO `kkim_chat_record` VALUES (39, 0, 0, 156376, 156375, '即将更新新版本', 1622910812);
INSERT INTO `kkim_chat_record` VALUES (40, 0, 0, 156376, 156377, '太傻', 1622911225);
INSERT INTO `kkim_chat_record` VALUES (41, 0, 0, 156376, 156377, '1+1等于几？', 1622911257);
INSERT INTO `kkim_chat_record` VALUES (42, 0, 0, 156376, 156375, '更新好多的内容.', 1622911590);
INSERT INTO `kkim_chat_record` VALUES (43, 0, 0, 156377, 156376, '好难的问题啊，未赐教', 1622912282);
INSERT INTO `kkim_chat_record` VALUES (44, 0, 0, 156375, 156376, '测试消息3', 1622912384);
INSERT INTO `kkim_chat_record` VALUES (45, 0, 0, 156375, 156376, '测试消息4', 1622912393);
INSERT INTO `kkim_chat_record` VALUES (46, 0, 0, 156376, 156375, '来聊天啊', 1622913811);
INSERT INTO `kkim_chat_record` VALUES (47, 0, 0, 156375, 156376, '来就来啊', 1622913825);
INSERT INTO `kkim_chat_record` VALUES (48, 0, 0, 156376, 156375, '八匹马呀', 1622913852);
INSERT INTO `kkim_chat_record` VALUES (49, 0, 0, 156375, 156376, '六六六呀', 1622913859);
INSERT INTO `kkim_chat_record` VALUES (50, 0, 0, 156376, 156375, '五魁手呀', 1622913894);

-- ----------------------------
-- Table structure for kkim_group
-- ----------------------------
DROP TABLE IF EXISTS `kkim_group`;
CREATE TABLE `kkim_group`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `group_name` varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '群名称',
  `avatar` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '群头像',
  `introduce` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '群介绍',
  `status` enum('normal','lock') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'normal' COMMENT '状态:normal=正常,lock=锁定',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `group_name`(`group_name`) USING BTREE,
  INDEX `status`(`status`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '群聊表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for kkim_group_member
-- ----------------------------
DROP TABLE IF EXISTS `kkim_group_member`;
CREATE TABLE `kkim_group_member`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `group_id` int(10) UNSIGNED NULL DEFAULT 0 COMMENT '群ID',
  `user_id` int(10) UNSIGNED NULL DEFAULT 0 COMMENT '成员ID',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `group_id`(`group_id`) USING BTREE,
  INDEX `user_id`(`user_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '群成员表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for kkim_users
-- ----------------------------
DROP TABLE IF EXISTS `kkim_users`;
CREATE TABLE `kkim_users`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `leader_id` int(10) UNSIGNED NULL DEFAULT 0 COMMENT '推广元ID',
  `account` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '账号',
  `password` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '密码',
  `salt` varchar(8) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '盐值',
  `avatar` varchar(300) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '头像',
  `nickname` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '昵称',
  `signature` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '个性签名',
  `realname` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '真实姓名',
  `gender` tinyint(1) UNSIGNED NULL DEFAULT 0 COMMENT '性别:0=女,1=男,2=未知',
  `money` decimal(10, 2) UNSIGNED NULL DEFAULT 0.00 COMMENT '账户余额',
  `reg_time` int(10) UNSIGNED NULL DEFAULT 0 COMMENT '注册时间',
  `last_login_time` int(10) UNSIGNED NULL DEFAULT 0 COMMENT '最后登录时间',
  `last_login_ip` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '最后登录ip',
  `login_count` int(10) UNSIGNED NULL DEFAULT 0 COMMENT '登陆次数',
  `status` enum('normal','lock') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'normal' COMMENT '状态:normal=正常,lock=锁定',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `account`(`account`) USING BTREE,
  INDEX `leader_id`(`leader_id`) USING BTREE,
  INDEX `reg_time`(`reg_time`) USING BTREE,
  INDEX `status`(`status`) USING BTREE,
  INDEX `nickname`(`nickname`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 156378 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '用户表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of kkim_users
-- ----------------------------
INSERT INTO `kkim_users` VALUES (156353, 0, 'dadfe', '', NULL, '', NULL, '', '', 0, 0.00, 0, 0, '', 0, 'lock');
INSERT INTO `kkim_users` VALUES (156354, 0, 'abcde', 'A8CC8B8D09EDBCB709E07D9CA3F69077', '2ALbnI', '', '未设置', '', '', 2, 0.00, 1622363029, 1622363029, '192.168.1.16', 1, 'lock');
INSERT INTO `kkim_users` VALUES (156356, 0, 'asdfe', '7528ADD1D834332200975CB26C8D5090', 'UEVhoK', '', NULL, '', '', 2, 0.00, 1622369606, 1622369606, '192.168.1.16', 1, 'lock');
INSERT INTO `kkim_users` VALUES (156357, 0, 'alang3', '7C0EE4F3FA6849D40552556BA8F88354', '9Q9nPo', '', NULL, '', '', 2, 0.00, 1622384460, 1622384460, '192.168.1.16', 1, 'lock');
INSERT INTO `kkim_users` VALUES (156358, 0, 'longge', 'D15E3716E6C69CF653344191AE86314D', '1FMtV0', '', NULL, '', '', 2, 0.00, 1622391425, 1622397932, '192.168.1.16', 5, 'lock');
INSERT INTO `kkim_users` VALUES (156359, 0, 'longge2', '4B8AEE07F2F075397C5659CDB42A36FC', 'zw8SMi', '', NULL, '', '', 2, 0.00, 1622391487, 1622767257, '192.168.1.16', 14, 'lock');
INSERT INTO `kkim_users` VALUES (156360, 0, 'dawang', '90D8BB94666CCCD629F8DF50F1A97B68', 'iqCIHb', '', NULL, '', '', 2, 0.00, 1622398649, 1622398649, '192.168.1.16', 1, 'lock');
INSERT INTO `kkim_users` VALUES (156361, 0, 'longge3', 'FE80310AC13CEDE1569EDAFE32596D4C', 'wurE2e', '', NULL, '', '', 2, 0.00, 1622513983, 1622767367, '192.168.1.16', 7, 'lock');
INSERT INTO `kkim_users` VALUES (156362, 0, 'longge5', '3A07B9CB6D590DFF1642EB7839B3B246', 'lFk3bG', '/images/head/22.jpg', '悉泰和', '', '', 2, 0.00, 1622777110, 1622777110, '192.168.1.16', 1, 'normal');
INSERT INTO `kkim_users` VALUES (156363, 0, 'longge6', '338B6F7D01D66B2C1B68E0ADFEBFC42A', 'yrFSJI', '/images/head/18.jpg', '仪修雅', '', '', 2, 0.00, 1622777199, 1622777199, '192.168.1.16', 1, 'normal');
INSERT INTO `kkim_users` VALUES (156364, 0, 'tianshen', 'A13BBF80FFEEC172E552DEE169CDFB95', 'jj3rjJ', '/images/head/08.jpg', '端乐蕊', '', '', 2, 0.00, 1622789851, 1622789851, '192.168.1.16', 1, 'normal');
INSERT INTO `kkim_users` VALUES (156365, 0, 'sokizi', '19B8649C87B72AB5148172C6AA13D45D', '1HVa1g', '/images/head/11.jpg', '鱼夜', '', '', 2, 0.00, 1622790203, 1622790203, '192.168.1.16', 1, 'normal');
INSERT INTO `kkim_users` VALUES (156366, 0, 'heximei', 'A01B4578CE603B7734E3DC2031923F97', '2etLc4', '/images/head/25.jpg', '斛沛儿', '', '', 2, 0.00, 1622793730, 1622793730, '192.168.1.16', 1, 'normal');
INSERT INTO `kkim_users` VALUES (156367, 0, 'sulin', '38B60E4D7C5752380E8D67B3FBBB4DD2', 'Wh7vUG', '/images/head/04.jpg', '文韶美', '', '', 2, 0.00, 1622816852, 1622816852, '192.168.1.16', 1, 'normal');
INSERT INTO `kkim_users` VALUES (156368, 0, 'alisan', '8A94884A2316A3CAD3E3A273CEB5FCF9', '6gBQFv', '/images/head/14.jpg', '斐芳荃', '', '', 2, 0.00, 1622816898, 1622816898, '192.168.1.16', 1, 'normal');
INSERT INTO `kkim_users` VALUES (156369, 0, 'youmi', '2BE3DEB744572AF1A51F51CC312ED4A4', 'ak23ac', '/images/head/23.jpg', '祝雁卉', '', '', 2, 0.00, 1622828592, 1622828592, '192.168.1.16', 1, 'normal');
INSERT INTO `kkim_users` VALUES (156370, 0, 'telangp', '194D9385F1D41457FE1E5C48659CB5B9', 'bEBUWQ', '/images/head/08.jpg', '咎向', '', '', 2, 0.00, 1622829090, 1622829090, '192.168.1.16', 1, 'normal');
INSERT INTO `kkim_users` VALUES (156371, 0, 'jaeke', '3261D5233C40076A31EC3675FED9410D', 'YnyhHZ', '/images/head/03.jpg', '瓮承业', '', '', 2, 0.00, 1622852563, 1622852563, '192.168.1.16', 1, 'normal');
INSERT INTO `kkim_users` VALUES (156372, 0, 'bachm', 'BE18B3D3157E78C7D6AF7FB33FE5EA20', 'IMZdRe', '/images/head/09.jpg', '隗安福', '', '', 2, 0.00, 1622852629, 1622852629, '192.168.1.16', 1, 'normal');
INSERT INTO `kkim_users` VALUES (156373, 0, 'menglong', '2BF5973D7D640A99E03C91F708EA5CD7', 'qe0F81', '/images/head/05.jpg', '么怜容', '', '', 2, 0.00, 1622859962, 1622859962, '192.168.1.16', 1, 'normal');
INSERT INTO `kkim_users` VALUES (156374, 0, 'diqiu', '9CAE962F656368FA231457B8AB726E77', 'ZTgFr6', '/images/head/28.jpg', '厍天华', '', '', 2, 0.00, 1622860001, 1622860001, '192.168.1.16', 1, 'normal');
INSERT INTO `kkim_users` VALUES (156375, 0, 'jockc', '7F419D69ADC50AA326F7F4563331D0F6', 'PYzeS0', '/images/head/26.jpg', '伯衍', '', '', 2, 0.00, 1622860079, 1622913144, '192.168.1.16', 3, 'normal');
INSERT INTO `kkim_users` VALUES (156376, 0, 'lunts', 'DF0F3B75AA95EC225E93FB08AE3E5FD6', 'drM6Sy', '/images/head/29.jpg', '玄维运', '', '', 2, 0.00, 1622868915, 1622908696, '192.168.1.16', 4, 'normal');
INSERT INTO `kkim_users` VALUES (156377, 0, 'tcang', '4E08892914EE80C234FF5632AFEBE702', '0WSBTS', '/images/head/16.jpg', '谏俊哲', '', '', 2, 0.00, 1622868950, 1622908746, '192.168.1.16', 5, 'normal');

SET FOREIGN_KEY_CHECKS = 1;
