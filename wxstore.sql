-- --------------------------------------------------------
-- 主机:                           localhost
-- 服务器版本:                        5.7.19 - MySQL Community Server (GPL)
-- 服务器操作系统:                      Win64
-- HeidiSQL 版本:                  9.4.0.5125
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- 导出  表 wxstore.account_logs 结构
CREATE TABLE IF NOT EXISTS `account_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '账变订单id',
  `no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '订单ID',
  `user_id` int(10) unsigned NOT NULL COMMENT '用户ID',
  `amount` bigint(20) DEFAULT NULL COMMENT '交易金额',
  `balance` bigint(20) DEFAULT NULL COMMENT '实际金额',
  `type` tinyint(3) unsigned NOT NULL COMMENT '账变类型',
  `flow` tinyint(3) unsigned NOT NULL COMMENT '资金流向 1：转入 2：转出',
  `snap` text COLLATE utf8mb4_unicode_ci COMMENT '快照',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `business` tinyint(4) DEFAULT NULL COMMENT '业务类型',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=133 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 数据导出被取消选择。
-- 导出  表 wxstore.action_logs 结构
CREATE TABLE IF NOT EXISTS `action_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) unsigned NOT NULL COMMENT '管理员ID',
  `type` tinyint(3) unsigned NOT NULL COMMENT '操作类型',
  `url` text COLLATE utf8mb4_unicode_ci COMMENT '操作地址',
  `data` text COLLATE utf8mb4_unicode_ci COMMENT '操作数据',
  `note` text COLLATE utf8mb4_unicode_ci COMMENT '操作说明',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=713 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 数据导出被取消选择。
-- 导出  表 wxstore.admins 结构
CREATE TABLE IF NOT EXISTS `admins` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mobile` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '账户状态 1正常 2冻结',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admins_mobile_unique` (`mobile`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 数据导出被取消选择。
-- 导出  表 wxstore.admin_role 结构
CREATE TABLE IF NOT EXISTS `admin_role` (
  `admin_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 数据导出被取消选择。
-- 导出  表 wxstore.advertising 结构
CREATE TABLE IF NOT EXISTS `advertising` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '广告图片地址',
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '跳转链接',
  `orderby` int(10) unsigned NOT NULL COMMENT '排序值',
  `note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '备注',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '1显示、2隐藏',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 数据导出被取消选择。
-- 导出  表 wxstore.api_logs 结构
CREATE TABLE IF NOT EXISTS `api_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL COMMENT '用户ID',
  `note` text COLLATE utf8mb4_unicode_ci COMMENT '操作日志',
  `request` longtext COLLATE utf8mb4_unicode_ci COMMENT '请求数据',
  `response` longtext COLLATE utf8mb4_unicode_ci COMMENT '响应数据',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 数据导出被取消选择。
-- 导出  表 wxstore.bank 结构
CREATE TABLE IF NOT EXISTS `bank` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bank_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '银行简称',
  `bank_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '银行名',
  `detail` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '说明',
  `img` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '图片',
  `order` int(11) NOT NULL DEFAULT '1',
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 数据导出被取消选择。
-- 导出  表 wxstore.bank_branches 结构
CREATE TABLE IF NOT EXISTS `bank_branches` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bank_branch_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '银行支行名称',
  `bank_branch_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '银行分行代码',
  `province` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '省份',
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '城市',
  `bank_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '银行名称',
  `bank_id` int(11) DEFAULT NULL COMMENT '银行id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 数据导出被取消选择。
-- 导出  表 wxstore.card_credit_banks 结构
CREATE TABLE IF NOT EXISTS `card_credit_banks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bank_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '姓名',
  `url` text COLLATE utf8mb4_unicode_ci COMMENT '图片',
  `order` int(11) DEFAULT NULL COMMENT '排序',
  `status` int(11) DEFAULT NULL,
  `bank_short_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '姓名',
  `day_limit` int(11) DEFAULT NULL COMMENT '单日限额',
  `single_limit` int(11) DEFAULT NULL COMMENT '单笔限额',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 数据导出被取消选择。
-- 导出  表 wxstore.card_savings_banks 结构
CREATE TABLE IF NOT EXISTS `card_savings_banks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bank_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '银行卡id',
  `bank_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '行号',
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '图片路径',
  `bank_short_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '银行卡简称',
  `bank_address_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bank_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '银行名称',
  `bank_province` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '银行省市',
  `bank_city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '银行城市',
  `bank_address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '银行地址',
  `order` smallint(6) DEFAULT NULL COMMENT '排序',
  `status` tinyint(4) DEFAULT NULL COMMENT '状态',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 数据导出被取消选择。
-- 导出  表 wxstore.channels 结构
CREATE TABLE IF NOT EXISTS `channels` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '渠道名称',
  `display` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '渠道显示名称',
  `tube_id` int(11) NOT NULL COMMENT '通道名称',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '渠道状态',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 数据导出被取消选择。
-- 导出  表 wxstore.messages 结构
CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '消息标题',
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '内容',
  `top` int(10) unsigned NOT NULL DEFAULT '2' COMMENT '是否置顶,默认为2，不置顶',
  `orderby` int(10) unsigned DEFAULT NULL COMMENT '排序',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '状态值：默认为1，为显示状态',
  `now` timestamp NULL DEFAULT NULL COMMENT '发布时间',
  `is_read` tinyint(4) NOT NULL DEFAULT '0' COMMENT '阅读状态:0未读,1已读',
  `text` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '消息简介',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 数据导出被取消选择。
-- 导出  表 wxstore.meta_data 结构
CREATE TABLE IF NOT EXISTS `meta_data` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '元数据键',
  `value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '元数据值',
  `group` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '元数据组',
  PRIMARY KEY (`id`),
  UNIQUE KEY `meta_data_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 数据导出被取消选择。
-- 导出  表 wxstore.migrations 结构
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 数据导出被取消选择。
-- 导出  表 wxstore.orders 结构
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL COMMENT '用户ID',
  `order_no` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '内部订单号',
  `out_order_no` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '外部订单号',
  `notify_url` text COLLATE utf8mb4_unicode_ci COMMENT '异步通知地址',
  `merchant_out_order_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '商户订单号',
  `body` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '名称',
  `channel_id` int(10) unsigned NOT NULL COMMENT '渠道ID',
  `status` tinyint(3) unsigned NOT NULL COMMENT '订单状态',
  `pay_status` tinyint(3) unsigned NOT NULL COMMENT '订单支付状态',
  `trade_amount` decimal(12,2) DEFAULT NULL COMMENT '交易金额',
  `real_amount` decimal(12,2) DEFAULT NULL COMMENT '实际支付金额',
  `paid_at` timestamp NULL DEFAULT NULL COMMENT '支付时间',
  `is_settle` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否结算',
  `note` text COLLATE utf8mb4_unicode_ci COMMENT '备注',
  `request` text COLLATE utf8mb4_unicode_ci COMMENT '请求数据',
  `response` text COLLATE utf8mb4_unicode_ci COMMENT '响应数据',
  `snap` longtext COLLATE utf8mb4_unicode_ci COMMENT '快照',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_order_no_unique` (`order_no`)
) ENGINE=InnoDB AUTO_INCREMENT=50666 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 数据导出被取消选择。
-- 导出  表 wxstore.password_resets 结构
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 数据导出被取消选择。
-- 导出  表 wxstore.permissions 结构
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 数据导出被取消选择。
-- 导出  表 wxstore.permission_role 结构
CREATE TABLE IF NOT EXISTS `permission_role` (
  `permission_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 数据导出被取消选择。
-- 导出  表 wxstore.roles 结构
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '角色名称',
  `display` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '显示名称',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 数据导出被取消选择。
-- 导出  表 wxstore.sessions 结构
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` int(11) NOT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int(11) NOT NULL,
  UNIQUE KEY `sessions_id_unique` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 数据导出被取消选择。
-- 导出  表 wxstore.settle_logs 结构
CREATE TABLE IF NOT EXISTS `settle_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL COMMENT '用户ID 通道结算单有 商户结算单为空',
  `tube_id` int(11) NOT NULL COMMENT '通道ID',
  `status` tinyint(3) unsigned NOT NULL COMMENT '结算状态',
  `settle_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '结算号',
  `snap` longtext COLLATE utf8mb4_unicode_ci COMMENT '快照',
  `settled_amount` bigint(20) NOT NULL COMMENT '已结算金额',
  `waiting_amount` bigint(20) NOT NULL COMMENT '待结算金额',
  `settled_orders` text COLLATE utf8mb4_unicode_ci COMMENT '已结算订单 逗号分隔',
  `waiting_orders` text COLLATE utf8mb4_unicode_ci COMMENT '待结算订单 逗号分隔',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `total_amount` bigint(20) NOT NULL COMMENT '总金额',
  `refund_amount` bigint(20) NOT NULL COMMENT '退款金额',
  `real_amount` bigint(20) NOT NULL COMMENT '实际金额',
  `type` tinyint(4) NOT NULL COMMENT '结算单类型 1：商户结算单 2：代理结算单',
  `charge_amount` bigint(20) NOT NULL COMMENT '手续费金额',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=246 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 数据导出被取消选择。
-- 导出  表 wxstore.side 结构
CREATE TABLE IF NOT EXISTS `side` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '幻灯片地址',
  `group_id` int(10) unsigned NOT NULL COMMENT '幻灯片分组:1商户端、2代理端',
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '跳转链接',
  `orderby` int(10) unsigned NOT NULL COMMENT '排序值',
  `note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '备注',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '1显示、2隐藏',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 数据导出被取消选择。
-- 导出  表 wxstore.tubes 结构
CREATE TABLE IF NOT EXISTS `tubes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '通道名称',
  `display` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '通道显示名称',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '通道状态',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 数据导出被取消选择。
-- 导出  表 wxstore.users 结构
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `real_mobile` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '真实手机号码',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '秘钥',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `level` tinyint(3) unsigned DEFAULT NULL COMMENT '等级 1平台 100代理 200商户',
  `aid` int(11) NOT NULL DEFAULT '1' COMMENT '上级ID',
  `aids` text COLLATE utf8mb4_unicode_ci COMMENT '所有上级ID',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '账户状态 1正常 2冻结',
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '头像',
  `wx_openid` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '微信openid',
  `wx_nickname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '微信昵称',
  `wx_headimgurl` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '微信头像',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_mobile_unique` (`mobile`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 数据导出被取消选择。
-- 导出  表 wxstore.user_accounts 结构
CREATE TABLE IF NOT EXISTS `user_accounts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `tube_id` int(11) NOT NULL COMMENT '通道ID',
  `balance` bigint(20) NOT NULL COMMENT '余额 单位分',
  `change` int(11) NOT NULL COMMENT '积累的小钱 1/100 分',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态 1:有效 2:无效',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 数据导出被取消选择。
-- 导出  表 wxstore.user_agent_channels 结构
CREATE TABLE IF NOT EXISTS `user_agent_channels` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `channel_id` int(11) NOT NULL COMMENT '渠道ID',
  `profit_rate` int(11) NOT NULL DEFAULT '0' COMMENT '利润率 1/10000',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=167 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 数据导出被取消选择。
-- 导出  表 wxstore.user_agent_infos 结构
CREATE TABLE IF NOT EXISTS `user_agent_infos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `type` tinyint(4) NOT NULL COMMENT '账户类型 1：个人 2：个体工商户 3：公司',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '审核状态 1：审核中 2：已审核 3：已拒绝',
  `reject_reason` text COLLATE utf8mb4_unicode_ci COMMENT '拒绝理由',
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '公司营业名称',
  `company_business_licence` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '公司营业执照',
  `company_account` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '公司对公账户',
  `legal_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '法人姓名',
  `legal_idcard` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '法人身份证号',
  `legal_idcard_front` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '法人身份证正面',
  `legal_idcard_back` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '法人身份证反面',
  `manager_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '负责人姓名',
  `manager_mobile` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '负责人手机号',
  `cleaner_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '结算人姓名',
  `cleaner_mobile` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '结算人手机号',
  `cleaner_deposit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '结算卡',
  `cleaner_idcard` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '结算人身份证号',
  `cleaner_idcard_front` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '结算人身份证正面',
  `cleaner_idcard_back` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '结算人身份证反面',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 数据导出被取消选择。
-- 导出  表 wxstore.user_credit_cards 结构
CREATE TABLE IF NOT EXISTS `user_credit_cards` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `bankcode_id` int(11) NOT NULL,
  `account_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '姓名',
  `account_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '账户',
  `credit_mobile` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '信用卡预留手机号',
  `status` int(11) NOT NULL DEFAULT '0',
  `cvv2` int(11) DEFAULT NULL COMMENT '信用卡卡背后三位数',
  `indate` int(11) DEFAULT NULL COMMENT '信用卡有效期 月年',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 数据导出被取消选择。
-- 导出  表 wxstore.user_merchant_infos 结构
CREATE TABLE IF NOT EXISTS `user_merchant_infos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `wechat_merchant_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '微信商户号',
  `ali_merchant_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '支付宝商户号',
  `ali_auth_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '支付宝授权码',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT ' 商户营业名称',
  `business_person` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '法人姓名',
  `identity_front` text COLLATE utf8mb4_unicode_ci COMMENT ' 身份证正面',
  `identity_contrary` text COLLATE utf8mb4_unicode_ci COMMENT ' 身份证反面',
  `merchant_license` text COLLATE utf8mb4_unicode_ci COMMENT ' 商户执照',
  `restaurant_license` text COLLATE utf8mb4_unicode_ci COMMENT '餐饮许可证（可不上传',
  `registration_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT ' 营业注册号',
  `identity_num` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '身份证号码',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '商户邮箱',
  `contract_tenancy` text COLLATE utf8mb4_unicode_ci COMMENT ' 门店招牌',
  `interior_picture` text COLLATE utf8mb4_unicode_ci COMMENT '门店内景',
  `registrantname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT ' 注册者姓名 ',
  `mobile` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '注册者手机号',
  `status` int(11) DEFAULT '4' COMMENT '状态1审核中。2审核通过，3未通过',
  `feedback` text COLLATE utf8mb4_unicode_ci COMMENT '拒绝理由',
  `merchant_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '门店地址',
  `alipay` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '支付宝账号',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 数据导出被取消选择。
-- 导出  表 wxstore.user_merchant_tubes 结构
CREATE TABLE IF NOT EXISTS `user_merchant_tubes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `tube_id` int(11) NOT NULL COMMENT '通道ID',
  `profit_rate` int(11) NOT NULL DEFAULT '0' COMMENT '利润率 1/10000',
  `tube_rate` int(11) NOT NULL DEFAULT '0' COMMENT '通道费率 1/10000',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 数据导出被取消选择。
-- 导出  表 wxstore.user_savings_cards 结构
CREATE TABLE IF NOT EXISTS `user_savings_cards` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `saving_bank_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '储蓄卡开户行信息',
  `account_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '银行卡号',
  `account_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '开卡人姓名',
  `opening_account` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '开户省市区',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL COMMENT '软删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 数据导出被取消选择。
-- 导出  表 wxstore.withdrawals 结构
CREATE TABLE IF NOT EXISTS `withdrawals` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL COMMENT '用户ID',
  `order_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '内部订单号',
  `out_order_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '内部订单号',
  `trade_amount` bigint(20) DEFAULT NULL COMMENT '交易金额',
  `real_amount` bigint(20) DEFAULT NULL COMMENT '实际金额',
  `type` tinyint(3) unsigned NOT NULL COMMENT '提现类型',
  `status` tinyint(3) unsigned NOT NULL COMMENT '提现状态',
  `note` text COLLATE utf8mb4_unicode_ci COMMENT '操作日志',
  `refuse_reason` text COLLATE utf8mb4_unicode_ci COMMENT '拒绝理由',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `tube_id` int(11) DEFAULT NULL COMMENT '通道ID',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 数据导出被取消选择。
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
