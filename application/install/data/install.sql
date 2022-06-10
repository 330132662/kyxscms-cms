DROP TABLE IF EXISTS `ky_ad`;
CREATE TABLE `ky_ad` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` char(100) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '数据状态',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='广告';

DROP TABLE IF EXISTS `ky_addons`;
CREATE TABLE `ky_addons` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(40) NOT NULL COMMENT '插件名或标识',
  `title` varchar(20) NOT NULL DEFAULT '' COMMENT '中文名',
  `description` text COMMENT '插件描述',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `config` text COMMENT '配置',
  `author` varchar(40) DEFAULT '' COMMENT '作者',
  `version` varchar(20) DEFAULT '' COMMENT '版本号',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '安装时间',
  `has_hook` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否钩子插件',
  `group` varchar(30) DEFAULT NULL COMMENT '配置分组',
  `mold` varchar(50) NOT NULL DEFAULT '' COMMENT '类型',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序（同级有效）',
  `exclusive` tinyint(1) unsigned DEFAULT '0' COMMENT '排他',
  `nav_display` tinyint(1) unsigned DEFAULT '0' COMMENT '导航显示',
  PRIMARY KEY (`id`),
  KEY `group` (`group`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='插件表';

INSERT INTO `ky_addons` VALUES (1,'SendEmail','发送Email','发送Email接口',1,'{\"mail_host\":\"\",\"mail_port\":\"\",\"mail_ssl\":\"0\",\"mail_smtpauth\":\"1\",\"mail_username\":\"\",\"mail_password\":\"\",\"mail_fromname\":\"\",\"mail_from\":\"\",\"mail_ishtml\":\"1\",\"mail_charset\":\"utf-8\",\"tpl_reg\":\"<div class=\\\"wrapper\\\" style=\\\"margin: 20px auto 0; width: 500px; padding-top:16px; padding-bottom:10px;\\\"><br style=\\\"clear:both; height:0\\\"><div class=\\\"content\\\" style=\\\"background: none repeat scroll 0 0 #FFFFFF; border: 1px solid #E9E9E9; margin: 2px 0 0; padding: 30px;\\\"><p>\\u60a8\\u597d: <\\/p><p>\\u611f\\u8c22\\u60a8\\u6ce8\\u518c <a href=\\\"{$web_url}\\\">{$web_name}<\\/a><\\/p><p style=\\\"border-top: 1px solid #DDDDDD;margin: 15px 0 25px;padding: 15px;\\\">\\u9a8c\\u8bc1\\u7801: <span style=\\\"color:red\\\">{$code}<\\/span><\\/p><p style=\\\"border-top: 1px solid #DDDDDD; padding-top:6px; margin-top:25px; color:#838383;\\\"><p>\\u8bf7\\u52ff\\u56de\\u590d\\u672c\\u90ae\\u4ef6, \\u6b64\\u90ae\\u7bb1\\u672a\\u53d7\\u76d1\\u63a7, \\u60a8\\u4e0d\\u4f1a\\u5f97\\u5230\\u4efb\\u4f55\\u56de\\u590d\\u3002<\\/p><\\/p><\\/div><\\/div>\",\"tpl_passw\":\"<div class=\\\"wrapper\\\" style=\\\"margin: 20px auto 0; width: 500px; padding-top:16px; padding-bottom:10px;\\\"><br style=\\\"clear:both; height:0\\\"><div class=\\\"content\\\" style=\\\"background: none repeat scroll 0 0 #FFFFFF; border: 1px solid #E9E9E9; margin: 2px 0 0; padding: 30px;\\\"><p>\\u60a8\\u6b63\\u5728\\u8fdb\\u884c<a href=\\\"{$web_url}\\\">{$web_name}<\\/a>\\u5bc6\\u7801\\u627e\\u56de<\\/p><p style=\\\"border-top: 1px solid #DDDDDD;margin: 15px 0 25px;padding: 15px;\\\">\\u9a8c\\u8bc1\\u7801: <span style=\\\"color:red\\\">{$code}<\\/span><\\/p><p style=\\\"border-top: 1px solid #DDDDDD; padding-top:6px; margin-top:25px; color:#838383;\\\"><p>\\u8bf7\\u52ff\\u56de\\u590d\\u672c\\u90ae\\u4ef6, \\u6b64\\u90ae\\u7bb1\\u672a\\u53d7\\u76d1\\u63a7, \\u60a8\\u4e0d\\u4f1a\\u5f97\\u5230\\u4efb\\u4f55\\u56de\\u590d\\u3002<\\/p><\\/p><\\/div><\\/div>\",\"tpl_bind\":\"<div class=\\\"wrapper\\\" style=\\\"margin: 20px auto 0; width: 500px; padding-top:16px; padding-bottom:10px;\\\"><br style=\\\"clear:both; height:0\\\"><div class=\\\"content\\\" style=\\\"background: none repeat scroll 0 0 #FFFFFF; border: 1px solid #E9E9E9; margin: 2px 0 0; padding: 30px;\\\"><p>\\u60a8\\u6b63\\u5728\\u8fdb\\u884c<a href=\\\"{$web_url}\\\">{$web_name}<\\/a>\\u7ed1\\u5b9a\\u90ae\\u7bb1<\\/p><p style=\\\"border-top: 1px solid #DDDDDD;margin: 15px 0 25px;padding: 15px;\\\">\\u9a8c\\u8bc1\\u7801: <span style=\\\"color:red\\\">{$code}<\\/span><\\/p><p style=\\\"border-top: 1px solid #DDDDDD; padding-top:6px; margin-top:25px; color:#838383;\\\"><p>\\u8bf7\\u52ff\\u56de\\u590d\\u672c\\u90ae\\u4ef6, \\u6b64\\u90ae\\u7bb1\\u672a\\u53d7\\u76d1\\u63a7, \\u60a8\\u4e0d\\u4f1a\\u5f97\\u5230\\u4efb\\u4f55\\u56de\\u590d\\u3002<\\/p><\\/p><\\/div><\\/div>\"}','kyxscms','1.0.2',1548661037,0,'email','web,wap,wechat',0,0,0);

DROP TABLE IF EXISTS `ky_bookshelf`;
CREATE TABLE `ky_bookshelf` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `novel_id` int(11) unsigned NOT NULL DEFAULT '0',
  `chapter_id` int(11) unsigned NOT NULL DEFAULT '0',
  `chapter_key` char(20) DEFAULT NULL,
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '数据状态',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='用户书架';

DROP TABLE IF EXISTS `ky_category`;
CREATE TABLE `ky_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '分类ID',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '标题',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序（同级有效）',
  `meta_title` varchar(50) NOT NULL DEFAULT '' COMMENT 'SEO的网页标题',
  `meta_keywords` varchar(255) NOT NULL DEFAULT '' COMMENT '关键字',
  `meta_description` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `icon` varchar(255) NOT NULL DEFAULT '' COMMENT '图标',
  `template_index` varchar(100) DEFAULT NULL COMMENT '频道页模板',
  `template_detail` varchar(100) DEFAULT NULL COMMENT '详情页模板',
  `template_filter` varchar(100) DEFAULT NULL COMMENT '筛选页模板',
  `link` varchar(255) NOT NULL DEFAULT '' COMMENT '外链地址',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '数据状态',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '分类模型',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='分类表';

INSERT INTO `ky_category` VALUES (4,'男生',0,1,'','','','','type.html','novel.html','lists.html','',1440469030,1539841781,1,0),(8,'女生',0,2,'','','','','type.html','novel.html','lists.html','',1450770206,1536657804,1,0),(18,'奇幻玄幻',4,0,'','','','','lists.html','novel.html','lists.html','',1536657923,1536657923,1,0),(19,'武侠仙侠',4,0,'','','','','lists.html','novel.html','lists.html','',1536657995,1536657995,1,0),(20,'历史军事',4,0,'','','','','lists.html','novel.html','lists.html','',1536658006,1536658006,1,0),(21,'都市娱乐',4,0,'','','','','lists.html','novel.html','lists.html','',1536658015,1536658015,1,0),(22,'科幻末日',4,0,'','','','','lists.html','novel.html','lists.html','',1536658027,1536658027,1,0),(23,'悬疑灵异',4,0,'','','','','lists.html','novel.html','lists.html','',1536658034,1536995233,1,0),(25,'古装言情',8,0,'','','','','lists.html','novel.html','lists.html','',1536658137,1536658137,1,0),(26,'都市言情',8,0,'','','','','lists.html','novel.html','lists.html','',1536658146,1536658146,1,0),(27,'浪漫青春',8,0,'','','','','lists.html','novel.html','lists.html','',1536658154,1536658154,1,0),(28,'幻想言情',8,0,'','','','','lists.html','novel.html','lists.html','',1536658169,1536658169,1,0),(31,'文章',0,5,'','','','','newslists.html','news.html','lists.html','',1537596272,1548416358,1,1),(34,'游戏竞技',4,0,'','','','','lists.html','novel.html','lists.html','',1539845119,1539845119,1,0),(35,'其他',4,0,'','','','','lists.html','novel.html','lists.html','',1539845177,1539845177,1,0),(36,'书库',0,3,'','','','','lists.html','lists.html','lists.html','home/lists/lists',1546912527,1547957943,1,3),(37,'科幻空间',8,0,'','','','','lists.html','novel.html','lists.html','',1548402683,1548402683,1,0),(38,'灵异悬疑',8,0,'','','','','lists.html','novel.html','lists.html','',1548402695,1548402695,1,0),(39,'同人衍生',8,0,'','','','','lists.html','novel.html','lists.html','',1548402705,1548402705,1,0),(40,'耽美百合',8,0,'','','','','lists.html','novel.html','lists.html','',1548402718,1548402718,1,0),(41,'排行',0,4,'','','','','rank.html','rank.html','lists.html','',1548416346,1548416366,1,2);

DROP TABLE IF EXISTS `ky_collect`;
CREATE TABLE `ky_collect` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` char(100) DEFAULT NULL,
  `charset` char(20) DEFAULT NULL COMMENT '网站编码',
  `type` char(20) DEFAULT NULL COMMENT '类型',
  `url_complete` tinyint(1) DEFAULT '0' COMMENT '网址补全',
  `url_reverse` tinyint(1) DEFAULT '0' COMMENT '倒序采集',
  `pic_local` tinyint(1) DEFAULT '0' COMMENT '图片本地化',
  `source_url` text COMMENT '列表地址',
  `section` text COMMENT '列表区间',
  `url_rule` text COMMENT '网址规则',
  `url_merge` varchar(255) DEFAULT NULL COMMENT '拼接网址',
  `url_must` varchar(255) DEFAULT NULL COMMENT '必须包含',
  `url_ban` varchar(255) DEFAULT NULL COMMENT '不能包含',
  `relation_url` text COMMENT '关联url',
  `rule` text COMMENT '规则',
  `category_way` tinyint(2) DEFAULT '0' COMMENT '入库方式',
  `category_fixed` int(11) DEFAULT '0' COMMENT '固定分类',
  `category_equivalents` text COMMENT '栏目转换',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '数据状态',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `collect_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '采集时间',
  `update` tinyint(3) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='自定义采集';

DROP TABLE IF EXISTS `ky_comment`;
CREATE TABLE `ky_comment` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `content` text NOT NULL,
  `create_time` int(11) unsigned NOT NULL DEFAULT '0',
  `uid` int(11) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(2) NOT NULL DEFAULT '1',
  `up` int(11) unsigned DEFAULT '0',
  `pid` int(11) unsigned DEFAULT '0',
  `mid` int(11) unsigned DEFAULT '0',
  `type` char(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='评论表';

DROP TABLE IF EXISTS `ky_config`;
CREATE TABLE `ky_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '配置ID',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '配置名称',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '配置类型',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '配置说明',
  `group` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '配置分组',
  `extra` varchar(255) NOT NULL DEFAULT '' COMMENT '配置值',
  `remark` varchar(100) NOT NULL COMMENT '配置说明',
  `value` text NOT NULL COMMENT '配置值',
  `sort` smallint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `display` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '可见性',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `group` (`group`),
  KEY `type` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='配置';

INSERT INTO `ky_config` VALUES (2,'meta_description',2,'SEO描述',1,'','网站搜索引擎描述','狂雨小说',6,1),(3,'meta_keywords',2,'SEO关键字',1,'','网站搜索引擎关键字','狂雨小说',5,1),(4,'close',4,'站点状态',1,'0:关闭\r\n1:开启','站点关闭后不能访问','1',8,1),(5,'icp',1,'网站备案号',1,'','设置在网站底部显示的备案号，如“沪ICP备12007941号-2','',7,1),(6,'document_position',3,'文档推荐位',2,'','文档推荐位，推荐到多个位置KEY值相加即可','1:列表推荐\r\n2:频道推荐\r\n4:首页推荐',3,0),(7,'close_tip',2,'关闭提示',1,'','','网站维护中，请稍后访问。',9,1),(13,'config_group_list',3,'配置分组',4,'','配置分组','1:基本\r\n2:内容\r\n3:用户\r\n4:备份\r\n5:附件\r\n6:api',4,0),(14,'list_rows',0,'后台记录数',2,'','后台数据每页显示记录数','20',0,1),(15,'user_allow_register',4,'注册开关',3,'0:关闭\r\n1:开启','是否开放用户注册','1',1,1),(17,'data_backup_path',0,'根路径',4,'','路径必须以 / 结尾','./public/database/',1,1),(18,'data_backup_part_size',0,'份卷大小',4,'','该值用于限制压缩后的分卷最大长度。单位：B；建议设置20M','20971520',2,1),(19,'data_backup_compress',4,'启用压缩',4,'0:不压缩\r\n1:压缩','压缩备份文件需要PHP环境支持gzopen,gzwrite函数','1',3,1),(20,'data_backup_compress_level',6,'压缩级别',4,'1:普通\r\n4:一般\r\n9:最高','数据库备份文件的压缩级别，该配置在开启压缩时生效','9',4,1),(22,'url',1,'网站地址',1,'','网站域名地址','',2,1),(23,'meta_title',1,'网站名称',1,'','网站名称','狂雨小说',1,1),(24,'logo',7,'网站logo',1,'','','/logo.png',3,1),(29,'default_tpl',1,'模板目录',1,'','','template/home',0,0),(32,'search_on',4,'搜索开关',2,'0:关闭\r\n1:开启','搜索开关','1',1,1),(33,'search_timespan',0,'搜索间隔',2,'','单位秒，建议设置为3秒以上','3',2,1),(76,'user_model_status',4,'会员模块',3,'0:关闭\r\n1:开启','是否开启会员模块','1',0,1),(77,'user_reg_status',4,'注册状态',3,'0:未审\r\n1:已审','','1',2,1),(78,'user_reg_verify',4,'注册验证码',3,'0:关闭\r\n1:开启','','0',3,1),(79,'user_login_verify',4,'登录验证码',3,'0:关闭\r\n1:开启','','0',4,1),(80,'user_reg_integral',0,'默认积分',3,'','','0',5,1),(82,'data_cache',4,'数据缓存',2,'0:关闭\r\n1:开启','','1',3,1),(83,'html_cache',4,'页面缓存',2,'0:关闭\r\n1:开启','','0',5,0),(88,'upload_path',0,'上传目录',5,'','','./uploads/',0,1),(89,'filter_size',3,'字数筛选',2,'','每行一个使用:分割开前面为参数后面为名称','< 300000:30万字以下\nbetween 300000,500000:30-50万字\nbetween 500000,1000000:50-100万字\nbetween 1000000,2000000:100-200万字\n> 2000000:200万字以上',5,1),(90,'filter_serialize',3,'连载筛选',2,'','每行一个使用:分割开前面为参数后面为名称','0:连载\n1:完本',6,1),(91,'filter_update',3,'更新筛选',2,'','每行一个使用:分割开前面为参数后面为名称','-3 day:三日内\nweek:七日内\n-15 day:半月内\nmonth:一月内',7,1),(92,'official_url',1,'官网地址',6,'','','http://www.kyxscms.com',1,0),(93,'version',0,'系统版本',1,'','','1.4.7',10,1),(94,'client_id',0,'客户ID',0,'','','',0,0),(95,'client_secret',0,'客户SECRET',0,'','','',0,0),(96,'uinon_collect_chapter_save',4,'章节保存',7,'0:访问保存\r\n1:采集保存','建议使用访问保存加快采集速度','0',0,0),(97,'union_collect_pic_save',4,'图片保存',7,'0:关闭\r\n1:开启','是否将图片保存到本地','1',0,1),(98,'union_collect_update_novel',5,'小说更新',7,'title:名称\r\ncategory:分类\r\nauthor:作者\r\npic:图片\r\ntag:标签\r\nrating:总评分\r\nrating_count:总评次\r\ncontent:介绍\r\nword:字数\r\nserialize:连载','','tag,rating,rating_count,word,serialize',0,1),(99,'union_collect_field',2,'联盟数据字段',7,'','','{\"novel\":[\"title\",\"category\",\"author\",\"pic\",\"content\",\"tag\",\"rating\",\"rating_count\",\"serialize\",\"create_time\",\"update_time\",\"word\"],\"news\":[\"title\",\"category\",\"pic\",\"content\"]}',0,0),(100,'union_collect_chapter_page',0,'章节分页',7,'','章节分页采集数量','1000',0,0),(101,'comment_key',2,'评论敏感词',2,'','评论敏感词,每行一个','www\nhttp://\n.com',8,1),(102,'collect_sleep',0,'采集间隔',8,'','采集每个地址等待时间，单位为秒','0',0,1),(103,'collect_thread_num',0,'线程数量',8,'','采集线程数量，根据自身服务器和网络情况设置','5',0,1),(105,'data_save_compress',4,'数据压缩',2,'0:关闭\r\n1:开启','是否启用数据压缩，在添加小说数据前修改，有了小说数据后不要修改否则会到导致出错','1',2,1),(106,'data_save_compress_level',6,'数据压缩等级',2,'1:普通\r\n4:一般\r\n9:最高','','4',2,1),(107,'union_collect_thread_num',0,'线程数量',7,'','采集线程数量，根据自身服务器和网络情况设置','5',0,1),(108,'wap_url',1,'手机域名',1,'','网站手机访问域名,无特定域名为空就可以了,域名设置例如 m.xsz.com','',2,1),(109,'chapter_txt',4,'章节保存',8,'0:关闭\r\n1:开启','保存章节内容，关闭将每次从采集源站获取','1',2,1),(110,'chapter_time_interval',0,'章节刷新间隔',8,'','单位分钟，章节列表自动更新间隔，间隔时间不要太短','20',3,1),(111,'chapter_time_interval_over',0,'章节刷新成功间隔',8,'','单位分钟，章节列表自动更新成功后间隔，间隔时间不要太短','120',4,1),(112,'chapter_preloading_num',0,'章节预加载',8,'','单位章，章节阅读器会预采集后面章节内容','3',5,1),(113,'api_key',0,'api调用key',6,'','api调用的钥密','',0,1),(114,'login_reader',4,'登录阅读',3,'0:关闭\r\n1:开启','登录后才可以阅读','0',6,1),(115,'login_reader_num',0,'登录阅读数量',3,'','阅读同一小说多少章后提示登录阅读，0为登录阅读','5',7,1);

DROP TABLE IF EXISTS `ky_crontab`;
CREATE TABLE `ky_crontab` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `content` varchar(255) DEFAULT NULL,
  `interval` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '时间间隔',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '数据状态',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `run_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '运行时间',
  `display` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '可见性',
  `type` tinyint(2) DEFAULT '0',
  `relation_id` int(11) unsigned DEFAULT NULL,
  `class_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='定时任务';

INSERT INTO `ky_crontab` VALUES (1,'{\"url\":\"admin\\/Check\",\"layer\":\"index\",\"vars\":\"\"}',86400,1,unix_timestamp(now()),unix_timestamp(now()),unix_timestamp(now()),0,2,NULL,'admin/Check'),(2,'{\"url\":\"api\\/Oauthcall\",\"layer\":\"check_order\",\"vars\":\"\"}',86400,1,unix_timestamp(now()),unix_timestamp(now()),unix_timestamp(now()),0,1,NULL,'api/Oauthcall');

DROP TABLE IF EXISTS `ky_link`;
CREATE TABLE `ky_link` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` char(100) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `sort` smallint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '数据状态',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

INSERT INTO `ky_link` VALUES (1,'狂雨小说cms','http://www.kyxscms.com',1,1,1491377962,1547270745);

DROP TABLE IF EXISTS `ky_member`;
CREATE TABLE `ky_member` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `username` char(16) DEFAULT NULL COMMENT '用户名',
  `password` char(32) DEFAULT NULL COMMENT '密码',
  `login` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '登录次数',
  `last_login_ip` bigint(20) NOT NULL DEFAULT '0' COMMENT '最后登录IP',
  `last_login_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '用户状态',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED COMMENT='管理表';

DROP TABLE IF EXISTS `ky_member_log`;
CREATE TABLE `ky_member_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `member_id` int(11) NOT NULL DEFAULT '0',
  `time` int(10) NOT NULL DEFAULT '0',
  `ip` bigint(20) NOT NULL DEFAULT '0',
  `method` char(50) DEFAULT NULL,
  `controller` char(50) DEFAULT NULL,
  `action` char(50) DEFAULT NULL,
  `param` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED COMMENT='日志表';

DROP TABLE IF EXISTS `ky_menu`;
CREATE TABLE `ky_menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '文档ID',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '标题',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序（同级有效）',
  `url` char(255) NOT NULL DEFAULT '' COMMENT '链接地址',
  `hide` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否隐藏',
  `tip` varchar(255) NOT NULL DEFAULT '' COMMENT '提示',
  `group` varchar(50) DEFAULT '' COMMENT '分组',
  `icon` varchar(50) DEFAULT NULL COMMENT '图标',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='菜单';

INSERT INTO `ky_menu` VALUES (1,'管理首页',0,0,'admin/index/index',0,'','',''),(2,'数据管理',0,1,'admin/data/index',0,'','',''),(3,'用户管理',0,2,'admin/data/review',0,'','',''),(4,'数据采集',0,3,'admin/collect/index',0,'','',''),(5,'系统扩展',0,4,'admin/addons/index',0,'','',NULL),(100,'设置',1,1,'admin/config/index',0,'','设置','layui-icon layui-icon-set'),(101,'管理员',1,2,'admin/member/index',0,'','管理员','layui-icon layui-icon-username'),(102,'列表',101,1,'admin/member/index',0,'','',''),(103,'添加',101,2,'admin/member/add',0,'','',''),(104,'修改',101,3,'admin/member/password',0,'','',''),(105,'权限管理',1,3,'admin/auth/index',1,'','权限','layui-icon layui-icon-auz'),(106,'用户组列表',105,1,'admin/auth/index',0,'','',''),(107,'新增用户组',105,2,'admin/auth/add',0,'','',''),(108,'修改用户组',105,3,'admin/auth/edit',0,'','',''),(109,'访问授权',105,4,'admin/auth/access',0,'','',''),(110,'成员授权',105,5,'admin/auth/user',0,'','',''),(111,'路由管理',1,4,'admin/route/index',0,'','路由','layui-icon layui-icon-senior'),(112,'列表',111,1,'admin/route/index',0,'','',''),(113,'修改',111,2,'admin/route/edit',0,'','',''),(114,'备份数据',1,5,'admin/database/index?type=export',0,'','数据','layui-icon layui-icon-console'),(115,'还原数据',1,6,'admin/database/index?type=import',0,'','数据',''),(116,'备份',114,1,'admin/database/export',0,'','',''),(117,'优化',114,2,'admin/database/optimize',0,'','',''),(118,'修复',114,3,'admin/database/repair',0,'','',''),(119,'还原',115,1,'admin/database/import',0,'','',''),(120,'删除',115,2,'admin/database/del',0,'','',''),(121,'用户组删除',105,6,'admin/auth/del',0,'','',''),(122,'删除',101,4,'admin/member/del',0,'','',''),(123,'权限',101,5,'admin/member/group',0,'','',''),(124,'友情链接',1,7,'admin/link/index',0,'','链接','layui-icon layui-icon-link'),(125,'列表',124,1,'admin/link/index',0,'','',''),(126,'添加',124,2,'admin/link/add',0,'','',''),(127,'修改',124,3,'admin/link/edit',0,'','',''),(128,'删除',124,4,'admin/link/del',0,'','',''),(129,'升级',1,8,'admin/upgrade/index',0,'','升级','layui-icon layui-icon-upload-drag'),(130,'列表',129,0,'admin/upgrade/lists',0,'','',NULL),(131,'安装',129,0,'admin/upgrade/install',0,'','',NULL),(132,'操作记录',1,3,'admin/log/index',0,'','操作记录','layui-icon layui-icon-log'),(201,'分类管理',2,2,'admin/category/index',0,'','分类','layui-icon layui-icon-more'),(202,'列表',201,1,'admin/category/index',0,'','',''),(203,'添加',201,2,'admin/category/add',0,'','',''),(204,'修改',201,3,'admin/category/edit',0,'','',''),(205,'删除',201,4,'admin/category/del',0,'','',''),(206,'移动',201,5,'admin/category/operate?type=move',0,'','',''),(207,'合并',201,6,'admin/category/operate?type=merge',0,'','',''),(208,'小说管理',2,3,'admin/novel/index',0,'','小说','layui-icon layui-icon-read'),(209,'列表',208,1,'admin/novel/index',0,'','',''),(210,'添加',208,2,'admin/novel/add',0,'','',''),(211,'修改',208,3,'admin/novel/edit',0,'','',''),(212,'删除',208,4,'admin/novel/del',0,'','',''),(218,'幻灯',2,1,'admin/slider/index',0,'','幻灯','layui-icon layui-icon-carousel'),(219,'列表',218,1,'admin/slider/index',0,'','',''),(220,'添加',218,2,'admin/slider/add',0,'','',''),(221,'修改',218,3,'admin/slider/edit',0,'','',''),(222,'删除',218,4,'admin/slider/del',0,'','',''),(233,'用户管理',3,0,'admin/user/index',0,'','用户','layui-icon layui-icon-user'),(234,'列表',233,0,'admin/user/index',0,'','',''),(235,'删除',233,2,'admin/user/del',0,'','',''),(236,'修改',233,1,'admin/user/edit',0,'','',''),(238,'修改密码',233,4,'admin/user/password',0,'','',''),(244,'小说评论',2,4,'admin/comment/index?type=novel',0,'','小说',''),(245,'文章管理',2,5,'admin/news/index',0,'','文章','layui-icon layui-icon-form'),(246,'列表',245,1,'admin/news/index',0,'','',NULL),(247,'添加',245,2,'admin/news/add',0,'','',NULL),(248,'修改',245,3,'admin/news/edit',0,'','',NULL),(249,'删除',245,4,'admin/news/del',0,'','',NULL),(250,'文章评论',2,5,'admin/comment/index?type=news',0,'','文章',NULL),(261,'广告管理',2,6,'admin/ad/index',0,'','广告','layui-icon layui-icon-template'),(262,'列表',261,1,'admin/ad/index',0,'','',NULL),(263,'添加',261,2,'admin/ad/add',0,'','',NULL),(264,'修改',261,3,'admin/ad/edit',0,'','',NULL),(265,'删除',261,4,'admin/ad/del',0,'','',NULL),(310,' 用户组管理',3,1,'admin/user_group/index',0,'','用户组','layui-icon layui-icon-user'),(311,'列表',310,1,'admin/user_group/index',0,'','',NULL),(312,'添加',310,2,'admin/user_group/add',0,'','',NULL),(313,'修改',310,3,'admin/user_group/edit',0,'','',NULL),(314,'删除',310,4,'admin/user_group/del',0,'','',NULL),(401,'采集管理',4,0,'admin/collect/index',0,'','采集','layui-icon layui-icon-senior'),(402,'添加',401,1,'admin/collect/add',0,'','',''),(403,'编辑',401,2,'admin/collect/edit',0,'','',''),(404,'删除',401,0,'admin/collect/del',0,'','',''),(405,'采集',401,0,'admin/collect/collect',0,'','',NULL),(406,'规则测试',401,0,'admin/collect/test',0,'','',NULL),(407,'资源列表',4,1,'admin/union_collect/index',0,'','数据联盟','layui-icon layui-icon-website'),(408,'添加',401,3,'admin/union_collect/add',0,'','',''),(409,'列表',401,4,'admin/union_collect/lists',0,'','',''),(410,'编辑',401,5,'admin/union_collect/edit',0,'','',NULL),(412,'删除',401,0,'admin/union_collect/del',0,'','',NULL),(413,'采集',401,0,'admin/union_collect/collect',0,'','',NULL),(414,'绑定分类',401,0,'admin/union_coolect/bind_type',0,'','',NULL),(415,'联盟采集设置',4,2,'admin/config/index?id=7',0,'','数据联盟',NULL),(416,'采集设置',4,2,'admin/config/index?id=8',0,'','采集',NULL),(417,'采集发布',401,0,'admin/collect_union/release',0,'','采集',NULL),(418,'采集规则联盟',4,3,'admin/collect_union/index',0,'','采集',NULL),(501,'插件管理',5,0,'admin/addons/index',0,'','插件','layui-icon layui-icon-component'),(502,'安装',501,0,'admin/addons/install',0,'','',NULL),(503,'设置',501,0,'admin/addons/config',0,'','',NULL),(504,'列表',501,0,'admin/addons/index',0,'','',NULL),(505,'启用',501,0,'admin/addons/enable',0,'','',NULL),(506,'禁用',501,0,'admin/addons/disable',0,'','',NULL),(508,'插件市场',5,1,'admin/market/addons',0,'','插件',NULL),(509,'模版管理',5,3,'admin/template/index',0,'','模版','layui-icon layui-icon-template-1'),(510,'安装',509,0,'admin/template/install',0,'','',NULL),(511,'设置',509,0,'admin/template/config',0,'','',NULL),(512,'编辑',509,0,'admin/template/edit',0,'','',NULL),(513,'模版市场',5,4,'admin/market/template',0,'','模版',NULL),(514,'个人中心',5,5,'admin/union/user',0,'','联盟','layui-icon layui-icon-share'),(515,'消费记录',5,6,'admin/union/data_log',0,'','联盟',NULL),(516,'数据联盟',5,7,'admin/union_collect/slist',0,'','联盟',NULL),(517,'提现记录',5,8,'admin/union_cash/lists',0,'','联盟',NULL),(518,'积分中心',5,10,'admin/union/integral',0,'','联盟',NULL),(519,'友链交换',5,9,'admin/union/link',1,'','联盟',NULL),(540,'数据转换',5,11,'admin/tool/datato',0,'','工具','layui-icon layui-icon-util'),(541,'清除数据',5,12,'admin/tool/datadel',0,'','工具',NULL),(542,'数据去重',5,13,'admin/tool/duplication',0,'','工具',NULL),(543,'生成sitemap',5,13,'admin/tool/sitemap',0,'','工具',NULL),(544,'SQL语句执行',5,14,'admin/tool/sqlexecute',0,'','工具',NULL);

DROP TABLE IF EXISTS `ky_news`;
CREATE TABLE `ky_news` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `category` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属分类',
  `title` char(80) NOT NULL DEFAULT '' COMMENT '名称',
  `pic` varchar(255) DEFAULT NULL COMMENT '封面',
  `content` text COMMENT '介绍',
  `up` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '顶',
  `down` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '踩',
  `hits` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '浏览数量',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态',
  `position` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '推荐位',
  `reurl` char(255) DEFAULT NULL COMMENT '来源',
  `template` varchar(100) DEFAULT NULL COMMENT '模板',
  `link` varchar(255) NOT NULL DEFAULT '' COMMENT '外链地址',
  `hits_day` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '日浏览',
  `hits_week` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '周浏览',
  `hits_month` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '月浏览',
  `hits_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '浏览时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='新闻表';

DROP TABLE IF EXISTS `ky_novel`;
CREATE TABLE `ky_novel` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `category` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属分类',
  `title` char(80) NOT NULL DEFAULT '' COMMENT '名称',
  `author` char(120) DEFAULT NULL COMMENT '作者',
  `pic` varchar(255) DEFAULT NULL COMMENT '图片',
  `content` text COMMENT '说明',
  `tag` varchar(255) DEFAULT NULL COMMENT '标签',
  `up` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '顶',
  `down` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '踩',
  `hits` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '浏览数量',
  `rating` char(10) NOT NULL DEFAULT '0' COMMENT '评分',
  `rating_count` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '评分人数',
  `serialize` tinyint(2) DEFAULT '0' COMMENT '连载',
  `favorites` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '收藏',
  `position` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '推荐位',
  `template` varchar(100) DEFAULT NULL COMMENT '模板',
  `link` varchar(255) DEFAULT NULL COMMENT '外链地址',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `reurl` char(255) DEFAULT NULL COMMENT '来源',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态',
  `hits_day` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '日浏览',
  `hits_week` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '周浏览',
  `hits_month` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '月浏览',
  `hits_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '浏览时间',
  `word` int(11) DEFAULT '0' COMMENT '字数',
  `recommend` int(11) DEFAULT '0' COMMENT '推荐票',
  `sole` tinyint(2) NOT NULL DEFAULT '3' COMMENT '0：未审核，1：提交审核，2:已审核,3：非独家',
  `author_id` int(11) DEFAULT NULL COMMENT '作者ID',
  PRIMARY KEY (`id`),
  KEY `title` (`title`),
  KEY `reurl` (`reurl`),
  KEY `update_time` (`update_time`),
  KEY `author` (`author`),
  KEY `hist` (`hits`),
  KEY `serialize` (`serialize`),
  KEY `category` (`category`),
  KEY `up` (`up`),
  KEY `down` (`down`),
  KEY `rating` (`rating`),
  KEY `position` (`position`),
  KEY `status` (`status`),
  KEY `hits_day` (`hits_day`),
  KEY `hits_week` (`hits_week`),
  KEY `hits_month` (`hits_month`),
  KEY `word` (`word`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='小说表';

CREATE TABLE `ky_novel_chapter` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `novel_id` int(11) unsigned NOT NULL DEFAULT '0',
  `source` varchar(255) NOT NULL DEFAULT '' COMMENT '源名称',
  `chapter` longtext COMMENT '内容',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态',
  `reurl` char(255) DEFAULT NULL COMMENT '来源',
  `collect_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '采集id',
  `run_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '运行时间',
  `updated` varchar(255) DEFAULT NULL COMMENT '最新内容',
  PRIMARY KEY (`id`),
  KEY `novel_id` (`novel_id`),
  KEY `status` (`status`),
  KEY `collect_id` (`collect_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='小说章节';

DROP TABLE IF EXISTS `ky_route`;
CREATE TABLE `ky_route` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '配置ID',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '配置名称',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '配置说明',
  `group` varchar(30) DEFAULT NULL COMMENT '配置分组',
  `value` text NOT NULL COMMENT '配置值',
  `display` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '可见性',
  `addons` varchar(40) DEFAULT NULL COMMENT '对应插件标识 会进入导航选择',
  PRIMARY KEY (`id`),
  KEY `group` (`group`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='路由';

INSERT INTO `ky_route` VALUES (1,'/','首页','','[\"home/index/index\"]',1,NULL),(2,'lists/:id','列表','','[\"home/lists/index\"]',1,NULL),(3,'all','书库','','[\"home/lists/lists\"]',1,NULL),(4,'novel/:id','小说介绍','','[\"home/novel/index\"]',1,NULL),(5,'book/:id/:key','阅读器','','[\"home/chapter/index\"]',1,NULL),(6,'news/:id','文章','','[\"home/news/index\"]',1,NULL),(7,'other/:tpl','其他','','[\"home/other/index\"]',1,NULL),(8,'search/[:keyword]','搜索','','[\"home/search/index\"]',1,NULL);

DROP TABLE IF EXISTS `ky_slider`;
CREATE TABLE `ky_slider` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` char(80) NOT NULL DEFAULT '',
  `type` tinyint(2) DEFAULT '0',
  `picpath` varchar(255) NOT NULL DEFAULT '0',
  `link` varchar(255) DEFAULT '',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序（同级有效）',
  `status` tinyint(2) NOT NULL DEFAULT '1',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='幻灯';

INSERT INTO `ky_slider` VALUES (1,'太古剑尊',0,'/uploads/slider/20190125/2356e25f8027f1223dcb8509d35d2f26.jpg','/home/novel/index/id/1.html',0,1,1548404324,1548671504),(2,'双面傲妻宠不停一吻情深，双面傲妻宠不停',0,'/uploads/slider/20190125/e3023f455f00de608a89c976e0977e38.png','/home/novel/index/id/6.html',0,1,1548404455,1548671511),(3,'婚途陌路：冷少，来试爱',0,'/uploads/slider/20190125/75f2d003c509dc2b9d3019b874b2903d.jpg','/home/novel/index/id/11.html',0,1,1548404474,1548671518),(4,'帝霸苍穹',0,'/uploads/slider/20190125/8d1c8e0332273e223adbcf21b770edcf.jpg','/home/novel/index/id/17.html',0,1,1548404494,1548671527);

DROP TABLE IF EXISTS `ky_template`;
CREATE TABLE `ky_template` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(40) NOT NULL COMMENT '插件名或标识',
  `title` varchar(20) NOT NULL DEFAULT '' COMMENT '中文名',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `author` varchar(40) DEFAULT '' COMMENT '作者',
  `version` varchar(20) DEFAULT '' COMMENT '版本号',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '安装时间',
  `mold` varchar(50) NOT NULL DEFAULT '' COMMENT '类型',
  `default` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='模版表';

INSERT INTO `ky_template` VALUES (1,'default_web','默认模版WEB',1,'kyxscms','1.0.7',0,'web,wap',1);

DROP TABLE IF EXISTS `ky_user`;
CREATE TABLE `ky_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `username` char(64) DEFAULT '' COMMENT '用户帐号',
  `email` char(32) NOT NULL DEFAULT '' COMMENT '用户邮箱',
  `password` char(32) NOT NULL COMMENT '密码',
  `sex` int(10) unsigned DEFAULT '0' COMMENT '用户的性别，值为1时是男性，值为2时是女性，值为0时是未知',
  `province` char(20) DEFAULT NULL COMMENT '用户个人资料填写的省份',
  `city` char(20) DEFAULT NULL COMMENT '普通用户个人资料填写的城市',
  `country` char(20) DEFAULT NULL COMMENT '国家，如中国为CN',
  `headimgurl` varchar(255) DEFAULT NULL COMMENT '头像',
  `introduce` varchar(255) DEFAULT NULL COMMENT '介绍',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '用户状态',
  `login` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '登录次数',
  `login_ip` bigint(20) NOT NULL DEFAULT '0' COMMENT '最后登录IP',
  `login_time` int(11) unsigned DEFAULT '0' COMMENT '最后登录时间',
  `exp` int(11) DEFAULT '0' COMMENT '经验',
  `integral` int(11) NOT NULL DEFAULT '0' COMMENT '积分',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `recommend` tinyint(3) DEFAULT '0' COMMENT '推荐票',
  `recommend_time` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='用户表';

DROP TABLE IF EXISTS `ky_user_group`;
CREATE TABLE `ky_user_group` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(12) NOT NULL,
  `exp_min` int(11) DEFAULT '0' COMMENT '经验',
  `exp_max` int(11) DEFAULT '0' COMMENT '经验',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '状态',
  `json` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `exp_min` (`exp_min`),
  KEY `exp_max` (`exp_max`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='用户组';

INSERT INTO `ky_user_group` VALUES (1,'新手上路',0,999,1,'{\"comment\":\"1\",\"comment_exp\":\"10\",\"comment_integral\":\"1\",\"faces\":\"0\",\"bookshelf\":\"1\",\"bookshelf_num\":\"5\",\"reader_exp\":\"1\",\"reader_integral\":\"1\",\"recommend\":\"1\"}'),(2,'中级会员',1000,19999,1,'{\"comment\":\"1\",\"comment_exp\":\"20\",\"comment_integral\":\"2\",\"faces\":\"1\",\"bookshelf\":\"1\",\"bookshelf_num\":\"5\",\"reader_exp\":\"2\",\"reader_integral\":\"2\",\"recommend\":\"2\"}'),(3,'高级会员',20000,99999,1,'{\"comment\":\"1\",\"comment_exp\":\"50\",\"comment_integral\":\"3\",\"faces\":\"1\",\"bookshelf\":\"1\",\"bookshelf_num\":\"20\",\"reader_exp\":\"5\",\"reader_integral\":\"3\",\"recommend\":\"3\"}'),(4,'金牌会员',100000,999999,1,'{\"comment\":\"1\",\"comment_exp\":\"100\",\"comment_integral\":\"5\",\"faces\":\"1\",\"bookshelf\":\"1\",\"bookshelf_num\":\"50\",\"reader_exp\":\"10\",\"reader_integral\":\"5\",\"recommend\":\"4\"}');

DROP TABLE IF EXISTS `ky_user_menu`;
CREATE TABLE `ky_user_menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '文档ID',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '标题',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序（同级有效）',
  `url` char(255) NOT NULL DEFAULT '' COMMENT '链接地址',
  `hide` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否隐藏',
  `icon` varchar(50) DEFAULT NULL COMMENT '图标',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='用户菜单';

INSERT INTO `ky_user_menu` VALUES (1,'关注中心',0,'',0,'&#xe64a;',0),(2,'我的书架',0,'user/bookshelf/index',0,'&#xe605;',1),(3,'最近阅读',0,'user/recentread/index',0,'&#xe67e;',1),(4,'我的评论',0,'user/comment/index',0,'&#xe610;',1),(7,'设置',0,'',0,'&#xe636;',0),(9,'个人信息',0,'user/user/info',0,'&#xe64a;',7),(10,'修改密码',0,'user/user/password',0,'&#xe60c;',7);