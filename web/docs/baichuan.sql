/*
Navicat MySQL Data Transfer

Source Server         : 本地
Source Server Version : 50726
Source Host           : localhost:3306
Source Database       : baichuan

Target Server Type    : MYSQL
Target Server Version : 50726
File Encoding         : 65001

Date: 2020-04-30 00:45:29
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `address`
-- ----------------------------
DROP TABLE IF EXISTS `address`;
CREATE TABLE `address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` varchar(45) NOT NULL,
  `address` varchar(200) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of address
-- ----------------------------

-- ----------------------------
-- Table structure for `first_attribute`
-- ----------------------------
DROP TABLE IF EXISTS `first_attribute`;
CREATE TABLE `first_attribute` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` varchar(45) NOT NULL,
  `name` varchar(180) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of first_attribute
-- ----------------------------
INSERT INTO `first_attribute` VALUES ('1', 'c2cd1d32-7b45-11ea-bb37-507b9df87d73', '内存');
INSERT INTO `first_attribute` VALUES ('3', '6bf3bafe-7b47-11ea-bb37-507b9df87d73', '颜色');
INSERT INTO `first_attribute` VALUES ('4', '7d4cc56e-7b47-11ea-bb37-507b9df87d73', '保修状态');
INSERT INTO `first_attribute` VALUES ('5', '93be16ad-7b47-11ea-bb37-507b9df87d73', '购买渠道');
INSERT INTO `first_attribute` VALUES ('7', 'be25b540-7bd6-11ea-bb37-507b9df87d73', '网络制式');
INSERT INTO `first_attribute` VALUES ('8', '70560d86-7bdb-11ea-bb37-507b9df87d73', '处理器');

-- ----------------------------
-- Table structure for `first_product`
-- ----------------------------
DROP TABLE IF EXISTS `first_product`;
CREATE TABLE `first_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` varchar(45) NOT NULL,
  `name` varchar(200) NOT NULL,
  `product_type_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of first_product
-- ----------------------------
INSERT INTO `first_product` VALUES ('1', '13b0685c-7a78-11ea-88c1-507b9df87d73', '苹果在保', '11');
INSERT INTO `first_product` VALUES ('2', '392a4306-7a78-11ea-88c1-507b9df87d73', '苹果过保', '11');
INSERT INTO `first_product` VALUES ('4', 'dce73f70-7b47-11ea-bb37-507b9df87d73', 'OPPO', '11');
INSERT INTO `first_product` VALUES ('5', 'f14807bb-7b47-11ea-bb37-507b9df87d73', 'Ipad系列', '12');
INSERT INTO `first_product` VALUES ('6', '0d688450-7b48-11ea-bb37-507b9df87d73', 'Mac air系列', '13');
INSERT INTO `first_product` VALUES ('7', '5411f7ea-7b48-11ea-bb37-507b9df87d73', 'VIVO', '11');
INSERT INTO `first_product` VALUES ('8', '62233ec5-7b48-11ea-bb37-507b9df87d73', '一加', '11');
INSERT INTO `first_product` VALUES ('9', '6d80bc4a-7b48-11ea-bb37-507b9df87d73', '华为', '11');
INSERT INTO `first_product` VALUES ('10', '762d0894-7b48-11ea-bb37-507b9df87d73', '三星', '11');
INSERT INTO `first_product` VALUES ('11', '7fa03bbc-7b48-11ea-bb37-507b9df87d73', '小米', '11');
INSERT INTO `first_product` VALUES ('12', '8771e3cd-7b48-11ea-bb37-507b9df87d73', '魅族', '11');
INSERT INTO `first_product` VALUES ('13', '9eee646a-7b48-11ea-bb37-507b9df87d73', '金立', '11');

-- ----------------------------
-- Table structure for `notice`
-- ----------------------------
DROP TABLE IF EXISTS `notice`;
CREATE TABLE `notice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `content` text NOT NULL COMMENT '通知内容',
  `target_id` int(11) NOT NULL COMMENT '通知产生的记录id',
  `target_class` varchar(45) NOT NULL COMMENT '通知产生的表',
  `is_readed` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否阅读',
  `user_id` int(11) NOT NULL,
  `link` varchar(255) DEFAULT NULL COMMENT '点击通知进入的地址',
  `subject` varchar(45) DEFAULT NULL COMMENT '消息标题',
  `type` varchar(15) DEFAULT NULL COMMENT '区分用户/河长消息',
  PRIMARY KEY (`id`),
  KEY `fk_target_table_id` (`target_class`,`target_id`)
) ENGINE=InnoDB AUTO_INCREMENT=919 DEFAULT CHARSET=utf8 COMMENT='系统通知表';

-- ----------------------------
-- Records of notice
-- ----------------------------
INSERT INTO `notice` VALUES ('887', '2019-12-20 11:02:00', '2019-12-20 11:02:00', '用户I m Fine.评论了你的分享', '39', 'app\\common\\Rv_Share_Model', '0', '10', '/api/share/001e943c-220c-11ea-97b4-509a4c1f87a0', null, null);
INSERT INTO `notice` VALUES ('888', '2019-12-20 11:11:33', '2019-12-20 11:11:33', '用户I m Fine.评论了你的分享', '39', 'app\\common\\Rv_Share_Model', '0', '10', '/api/share/001e943c-220c-11ea-97b4-509a4c1f87a0', null, null);
INSERT INTO `notice` VALUES ('889', '2019-12-20 11:15:01', '2019-12-20 11:15:01', '用户I m Fine.评论了你的分享', '39', 'app\\common\\Rv_Share_Model', '0', '10', '/api/share/001e943c-220c-11ea-97b4-509a4c1f87a0', null, null);
INSERT INTO `notice` VALUES ('890', '2019-12-20 11:16:30', '2019-12-20 11:16:30', '用户I m Fine.评论了你的分享', '39', 'app\\common\\Rv_Share_Model', '0', '10', '/api/share/001e943c-220c-11ea-97b4-509a4c1f87a0', null, null);
INSERT INTO `notice` VALUES ('891', '2019-12-20 11:17:03', '2019-12-20 11:17:03', '用户I m Fine.评论了你的分享', '39', 'app\\common\\Rv_Share_Model', '0', '10', '/api/share/001e943c-220c-11ea-97b4-509a4c1f87a0', null, null);
INSERT INTO `notice` VALUES ('892', '2019-12-20 11:17:40', '2019-12-20 11:17:40', '用户I m Fine.评论了你的分享', '39', 'app\\common\\Rv_Share_Model', '0', '10', '/api/share/001e943c-220c-11ea-97b4-509a4c1f87a0', null, null);
INSERT INTO `notice` VALUES ('893', '2019-12-20 11:18:37', '2019-12-20 11:18:37', '用户I m Fine.评论了你的分享', '39', 'app\\common\\Rv_Share_Model', '0', '10', '/api/share/001e943c-220c-11ea-97b4-509a4c1f87a0', null, null);
INSERT INTO `notice` VALUES ('894', '2019-12-20 11:24:09', '2019-12-20 11:24:09', '用户I m Fine.评论了你的分享', '39', 'app\\common\\Rv_Share_Model', '0', '10', '/api/share/001e943c-220c-11ea-97b4-509a4c1f87a0', null, null);
INSERT INTO `notice` VALUES ('895', '2019-12-20 11:26:59', '2019-12-20 11:26:59', '用户I m Fine.评论了你的分享', '39', 'app\\common\\Rv_Share_Model', '0', '10', '/api/share/001e943c-220c-11ea-97b4-509a4c1f87a0', null, null);
INSERT INTO `notice` VALUES ('896', '2019-12-20 11:28:00', '2019-12-20 11:28:00', '用户I m Fine.评论了你的分享', '39', 'app\\common\\Rv_Share_Model', '0', '10', '/api/share/001e943c-220c-11ea-97b4-509a4c1f87a0', null, null);
INSERT INTO `notice` VALUES ('897', '2019-12-20 11:37:48', '2019-12-20 11:37:48', '用户I m Fine.评论了你的分享', '39', 'app\\common\\Rv_Share_Model', '0', '10', '/api/share/001e943c-220c-11ea-97b4-509a4c1f87a0', null, null);
INSERT INTO `notice` VALUES ('898', '2019-12-23 15:21:11', '2019-12-25 17:29:45', '观山湖河段问题举报：dfssdfs', '24', 'app\\common\\Rv_Complain_Model', '1', '2', '/api/problem/c9cd9eec-2554-11ea-9f75-509a4c1f87a0', null, null);
INSERT INTO `notice` VALUES ('899', '2019-12-23 16:27:07', '2019-12-25 17:29:45', '观山湖河段问题举报：sdad', '25', 'app\\common\\Rv_Complain_Model', '1', '2', '/api/problem/ff8030d5-255d-11ea-9f75-509a4c1f87a0', null, null);
INSERT INTO `notice` VALUES ('900', '2019-12-23 16:34:54', '2019-12-23 16:34:54', '用户你瞅啥评论了你的分享', '40', 'app\\common\\Rv_Share_Model', '0', '10', '/api/share/0e139bf3-255f-11ea-9f75-509a4c1f87a0', null, null);
INSERT INTO `notice` VALUES ('901', '2019-12-24 11:30:48', '2019-12-24 11:30:48', '该举报已经处理完成', '23', 'app\\common\\Rv_Complain_Model', '0', '10', '/api/problem/74ceafae-1fe7-11ea-9375-509a4c1f87a0', null, null);
INSERT INTO `notice` VALUES ('902', '2019-12-25 10:43:46', '2019-12-25 10:43:46', '微信用户:你瞅啥 加入了您的队伍', '27', 'app\\common\\Rv_Group_Model', '0', '10', '/api/group/7921b77e-1fa4-11ea-9375-509a4c1f87a0', null, null);
INSERT INTO `notice` VALUES ('903', '2019-12-25 11:32:14', '2019-12-25 18:13:49', '微信用户:你瞅啥 点赞了你的分享', '92', 'app\\common\\Rv_Like_Model', '1', '16', '/api/share/14299cb5-26c7-11ea-aa24-509a4c1f87a0', null, null);
INSERT INTO `notice` VALUES ('904', '2019-12-25 14:19:42', '2019-12-25 18:13:58', '用户你瞅啥评论了你的分享', '23', 'app\\common\\Rv_Comment_Model', '1', '16', '/api/share/14299cb5-26c7-11ea-aa24-509a4c1f87a0', null, null);
INSERT INTO `notice` VALUES ('905', '2019-12-25 14:20:12', '2019-12-25 17:29:45', '您有新的举报需要处理,编号:2019122500001', '26', 'app\\common\\Rv_Complain_Model', '1', '2', '/api/problem/9a308e0f-26de-11ea-aa24-509a4c1f87a0', null, null);
INSERT INTO `notice` VALUES ('906', '2019-12-25 14:22:43', '2019-12-25 16:54:18', '举报:2019122500001 处理进度更新', '26', 'app\\common\\Rv_Complain_Model', '1', '16', '/api/problem/9a308e0f-26de-11ea-aa24-509a4c1f87a0', null, null);
INSERT INTO `notice` VALUES ('907', '2019-12-25 16:49:10', '2019-12-25 18:13:49', '微信用户:你瞅啥 点赞了你的分享', '93', 'app\\common\\Rv_Like_Model', '1', '16', '/api/share/887f76d6-26e4-11ea-aa24-509a4c1f87a0', null, null);
INSERT INTO `notice` VALUES ('908', '2019-12-26 15:02:11', '2019-12-26 15:17:11', '您有新的举报需要处理,编号:2019122600001', '27', 'app\\common\\Rv_Complain_Model', '1', '2', '/api/problem/a36a2157-27ad-11ea-80e3-509a4c1f87a0', null, null);
INSERT INTO `notice` VALUES ('909', '2019-12-26 15:03:08', '2019-12-26 15:17:11', '您有新的举报需要处理,编号:2019122600002', '28', 'app\\common\\Rv_Complain_Model', '1', '2', '/api/problem/c5c2fba5-27ad-11ea-80e3-509a4c1f87a0', null, null);
INSERT INTO `notice` VALUES ('910', '2019-12-26 15:21:15', '2019-12-26 15:21:30', '评论了你的分享:aaa', '24', 'app\\common\\Rv_Comment_Model', '1', '17', '/api/share/449334b3-27b0-11ea-80e3-509a4c1f87a0', null, null);
INSERT INTO `notice` VALUES ('911', '2019-12-26 15:22:49', '2019-12-26 15:23:01', '评论了你的分享:sadasd', '25', 'app\\common\\Rv_Comment_Model', '1', '17', '/api/share/449334b3-27b0-11ea-80e3-509a4c1f87a0', null, null);
INSERT INTO `notice` VALUES ('912', '2019-12-26 15:26:29', '2019-12-26 15:26:39', '评论了你的分享:454654', '26', 'app\\common\\Rv_Comment_Model', '1', '17', '/api/share/449334b3-27b0-11ea-80e3-509a4c1f87a0', null, null);
INSERT INTO `notice` VALUES ('913', '2019-12-26 17:09:59', '2019-12-26 17:10:08', '评论了你的分享:13213213', '27', 'app\\common\\Rv_Comment_Model', '1', '17', '/api/share/449334b3-27b0-11ea-80e3-509a4c1f87a0', null, null);
INSERT INTO `notice` VALUES ('914', '2019-12-26 17:10:21', '2019-12-26 17:11:17', '评论了你的分享:5641654', '28', 'app\\common\\Rv_Comment_Model', '1', '17', '/api/share/449334b3-27b0-11ea-80e3-509a4c1f87a0', null, null);
INSERT INTO `notice` VALUES ('915', '2019-12-26 17:15:59', '2019-12-26 17:16:08', '您有新的举报需要处理,编号:2019122600003', '29', 'app\\common\\Rv_Complain_Model', '1', '2', '/api/problem/54829620-27c0-11ea-80e3-509a4c1f87a0', null, null);
INSERT INTO `notice` VALUES ('916', '2019-12-27 15:14:38', '2019-12-27 15:18:26', '举报:2019122600003 处理进度更新', '29', 'app\\common\\Rv_Complain_Model', '1', '17', '/api/problem/54829620-27c0-11ea-80e3-509a4c1f87a0', null, null);
INSERT INTO `notice` VALUES ('917', '2019-12-27 18:18:18', '2019-12-27 18:18:18', '评论了你的分享:a', '29', 'app\\common\\Rv_Comment_Model', '0', '10', '/api/share/0e139bf3-255f-11ea-9f75-509a4c1f87a0', null, 'usual');
INSERT INTO `notice` VALUES ('918', '2019-12-30 09:51:27', '2019-12-30 09:51:46', '举报:2019122600003 处理进度更新', '29', 'app\\common\\Rv_Complain_Model', '1', '17', '/api/problem/54829620-27c0-11ea-80e3-509a4c1f87a0', null, 'usual');

-- ----------------------------
-- Table structure for `order`
-- ----------------------------
DROP TABLE IF EXISTS `order`;
CREATE TABLE `order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` varchar(45) NOT NULL,
  `status` varchar(255) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `desc` varchar(200) DEFAULT NULL,
  `price` varchar(255) DEFAULT NULL,
  `count` int(11) DEFAULT NULL,
  `order_time` datetime DEFAULT NULL,
  `wx_appid` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of order
-- ----------------------------
INSERT INTO `order` VALUES ('1', '750e04bf-8a37-11ea-8bf0-507b9df87d73', 'unshipped', '4', '苹果 11 Pro Max:256G→中国红→60天以上 充新', '90', '1', '2020-04-30 12:35:37', 'wx7717d96c45ce7e7d');
INSERT INTO `order` VALUES ('2', 'b2e6b010-8a37-11ea-8bf0-507b9df87d73', 'unshipped', '4', '苹果 11 Pro Max：128G→中国红→60天以上 充新', '910', '1', '2020-04-30 12:37:21', 'wx7717d96c45ce7e7d');

-- ----------------------------
-- Table structure for `product`
-- ----------------------------
DROP TABLE IF EXISTS `product`;
CREATE TABLE `product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` varchar(45) NOT NULL,
  `first_product_id` int(11) NOT NULL,
  `name` varchar(80) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of product
-- ----------------------------
INSERT INTO `product` VALUES ('1', '8c161cf9-7c0f-11ea-bb37-507b9df87d73', '1', '苹果 11 Pro Max');
INSERT INTO `product` VALUES ('2', 'ae6e6580-7c0f-11ea-bb37-507b9df87d73', '1', '苹果 11 Pro');
INSERT INTO `product` VALUES ('3', 'c5c15726-7c0f-11ea-bb37-507b9df87d73', '1', '苹果 11');
INSERT INTO `product` VALUES ('4', 'dd533734-7c0f-11ea-bb37-507b9df87d73', '4', 'OPPO Reno 855');
INSERT INTO `product` VALUES ('5', 'fb197e9e-7c0f-11ea-bb37-507b9df87d73', '4', 'OPPO Reno 710');
INSERT INTO `product` VALUES ('6', '121fb8f5-7c10-11ea-bb37-507b9df87d73', '4', 'OPPO FIND X');
INSERT INTO `product` VALUES ('7', '5113db8f-7c10-11ea-bb37-507b9df87d73', '9', '华为 P30Pro');
INSERT INTO `product` VALUES ('8', '72ce1b9c-7c10-11ea-bb37-507b9df87d73', '11', '小米10');
INSERT INTO `product` VALUES ('9', '7e0be2e9-7c10-11ea-bb37-507b9df87d73', '11', '小米9');
INSERT INTO `product` VALUES ('10', '8a5f3409-7c10-11ea-bb37-507b9df87d73', '12', '魅族16th');
INSERT INTO `product` VALUES ('11', '9b7feea2-7c10-11ea-bb37-507b9df87d73', '12', '魅族16th plus');

-- ----------------------------
-- Table structure for `product_price`
-- ----------------------------
DROP TABLE IF EXISTS `product_price`;
CREATE TABLE `product_price` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` varchar(45) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `second_attribute_ids` varchar(45) DEFAULT NULL,
  `quote_standard_ids` varchar(45) DEFAULT NULL,
  `price` varchar(120) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of product_price
-- ----------------------------
INSERT INTO `product_price` VALUES ('1', '15de69c5-8178-11ea-bb4a-507b9df87d73', '8', '3_11_6_5', '1,2', '4900,4700');
INSERT INTO `product_price` VALUES ('16', '51a802e6-8711-11ea-a0b3-507b9df87d73', '1', '3_9_6', '1,2', '90,80');
INSERT INTO `product_price` VALUES ('15', 'd73b0d84-8708-11ea-a0b3-507b9df87d73', '1', '2_9_6', '1,2', '910,810');
INSERT INTO `product_price` VALUES ('14', 'b21f66aa-824c-11ea-ada0-507b9df87d73', '6', '1', '1', '90000');
INSERT INTO `product_price` VALUES ('17', '20e5576b-8969-11ea-9c64-507b9df87d73', '1', '1', '1', '9000');
INSERT INTO `product_price` VALUES ('18', 'cddfbdab-896a-11ea-9c64-507b9df87d73', '8', '1', '1,2,3,4,6', '900,8000,8000,7000,8000');

-- ----------------------------
-- Table structure for `product_type`
-- ----------------------------
DROP TABLE IF EXISTS `product_type`;
CREATE TABLE `product_type` (
  `id` int(45) NOT NULL AUTO_INCREMENT,
  `name` varchar(180) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of product_type
-- ----------------------------
INSERT INTO `product_type` VALUES ('11', '手机');
INSERT INTO `product_type` VALUES ('12', '平板');
INSERT INTO `product_type` VALUES ('13', '笔记本');

-- ----------------------------
-- Table structure for `quote_standard`
-- ----------------------------
DROP TABLE IF EXISTS `quote_standard`;
CREATE TABLE `quote_standard` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` varchar(45) DEFAULT NULL,
  `name` varchar(80) DEFAULT NULL,
  `desc` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of quote_standard
-- ----------------------------
INSERT INTO `quote_standard` VALUES ('1', '55a6b273-7c06-11ea-bb37-507b9df87d73', '充新', '和全新差不多的哦');
INSERT INTO `quote_standard` VALUES ('2', '709f8a73-7c07-11ea-bb37-507b9df87d73', '靓机', '屏幕总成完美，壳轻微使用痕迹，显示无老化');
INSERT INTO `quote_standard` VALUES ('3', '5898c453-896a-11ea-9c64-507b9df87d73', '屏靓支架靓壳小靓', '碎膘');
INSERT INTO `quote_standard` VALUES ('4', '71b787c3-896a-11ea-9c64-507b9df87d73', '屏、支架小花框小磕', '你这鲁大师');
INSERT INTO `quote_standard` VALUES ('5', '829c166b-896a-11ea-9c64-507b9df87d73', '屏花壳花', '阿瑟东撒的');
INSERT INTO `quote_standard` VALUES ('6', '91bd5c59-896a-11ea-9c64-507b9df87d73', '外屏破', '撒大苏打我');

-- ----------------------------
-- Table structure for `second_attribute`
-- ----------------------------
DROP TABLE IF EXISTS `second_attribute`;
CREATE TABLE `second_attribute` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` varchar(45) DEFAULT NULL,
  `name` varchar(180) DEFAULT NULL,
  `first_attribute_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of second_attribute
-- ----------------------------
INSERT INTO `second_attribute` VALUES ('1', 'ada92582-7bd3-11ea-bb37-507b9df87d73', '6+64G', '1');
INSERT INTO `second_attribute` VALUES ('2', 'd729b1ad-7bd3-11ea-bb37-507b9df87d73', '128G', '1');
INSERT INTO `second_attribute` VALUES ('3', 'e1a913fd-7bd3-11ea-bb37-507b9df87d73', '256G', '1');
INSERT INTO `second_attribute` VALUES ('4', '243be5f1-7bd4-11ea-bb37-507b9df87d73', '不分颜色', '3');
INSERT INTO `second_attribute` VALUES ('5', '96ea87f2-7bd4-11ea-bb37-507b9df87d73', '全网通', '7');
INSERT INTO `second_attribute` VALUES ('6', 'ae7dd64c-7bd4-11ea-bb37-507b9df87d73', '60天以上', '4');
INSERT INTO `second_attribute` VALUES ('12', '0fee07a5-7bd7-11ea-bb37-507b9df87d73', '白色', '3');
INSERT INTO `second_attribute` VALUES ('8', 'db79b96a-7bd4-11ea-bb37-507b9df87d73', '金色', '3');
INSERT INTO `second_attribute` VALUES ('9', 'e4629a43-7bd4-11ea-bb37-507b9df87d73', '中国红', '3');
INSERT INTO `second_attribute` VALUES ('10', 'f0aaca4e-7bd4-11ea-bb37-507b9df87d73', '银色', '3');
INSERT INTO `second_attribute` VALUES ('11', 'f9f95214-7bd4-11ea-bb37-507b9df87d73', '灰色', '3');

-- ----------------------------
-- Table structure for `setting`
-- ----------------------------
DROP TABLE IF EXISTS `setting`;
CREATE TABLE `setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` varchar(45) NOT NULL,
  `name` varchar(200) DEFAULT NULL,
  `type` varchar(45) DEFAULT NULL,
  `pic_url` varchar(200) DEFAULT NULL,
  `desc` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of setting
-- ----------------------------

-- ----------------------------
-- Table structure for `store_option`
-- ----------------------------
DROP TABLE IF EXISTS `store_option`;
CREATE TABLE `store_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` varchar(45) DEFAULT NULL,
  `store_user_id` int(11) DEFAULT NULL,
  `first_product_id` int(11) DEFAULT NULL,
  `symbol` int(11) DEFAULT NULL,
  `store_price` varchar(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of store_option
-- ----------------------------
INSERT INTO `store_option` VALUES ('4', '1b37e9f2-83d4-11ea-b1c8-507b9df87d73', '2', '1', '1', '70');
INSERT INTO `store_option` VALUES ('5', '1b37fecb-83d4-11ea-b1c8-507b9df87d73', '2', '7', '-1', '90');
INSERT INTO `store_option` VALUES ('11', '6d7e9695-83dd-11ea-b1c8-507b9df87d73', '2', '6', '1', '77');

-- ----------------------------
-- Table structure for `store_user`
-- ----------------------------
DROP TABLE IF EXISTS `store_user`;
CREATE TABLE `store_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` varchar(45) DEFAULT NULL,
  `store_name` varchar(90) DEFAULT NULL,
  `store_phone` varchar(45) DEFAULT NULL,
  `store_address` varchar(200) DEFAULT NULL,
  `password` varchar(45) DEFAULT NULL,
  `wx_appid` varchar(90) DEFAULT NULL,
  `type` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of store_user
-- ----------------------------
INSERT INTO `store_user` VALUES ('2', '39cada29-8250-11ea-ada0-507b9df87d73', '佰川回收2', '18224991617', '花溪区', 'MTIzNDU2', 'wx18224991617', 'normal');
INSERT INTO `store_user` VALUES ('1', 'f6b3c853-818d-11ea-bb4a-507b9df87d73', '佰川回收', '18224995161', '南明区123', 'YWRtaW4=', 'wx7717d96c45ce7e7d', 'admin');
INSERT INTO `store_user` VALUES ('3', '12f63bf0-8250-11ea-ada0-507b9df87d73', '佰川回收1', '18224995161', '云岩区', 'MTIzNDU2', 'wx18224995161', 'normal');
INSERT INTO `store_user` VALUES ('4', '542c4c66-8250-11ea-ada0-507b9df87d73', '佰川回收3', '12312312', '观山湖去', 'MTIzNDU2', 'wx12312312', 'normal');

-- ----------------------------
-- Table structure for `user`
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` varchar(45) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `gender` varchar(45) DEFAULT NULL,
  `openid` varchar(180) DEFAULT NULL,
  `login_date` datetime DEFAULT NULL,
  `out_date` datetime DEFAULT NULL,
  `cellphone` varchar(45) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `wx_appid` varchar(90) DEFAULT NULL,
  `wx_avatar` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('4', 'dc5db5df-86fe-11ea-a0b3-507b9df87d73', '你瞅啥', '1', 'odEE_5fezRB3UeehEAf3BvNrjaxM', '2020-04-30 00:44:11', null, '18212446530', '1', 'wx7717d96c45ce7e7d', 'https://wx.qlogo.cn/mmopen/vi_32/YiaffHxMwJicpiaCWhAqRB36g1KWhbibmKpWbwywVNey3TpsfhNMWvWQl5yVb10e1Wflib07TuT3j2dkUFJARG9Ad1A/132');
