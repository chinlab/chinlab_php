ALTER TABLE `db_doctor`.`tuser_order` ADD INDEX `user_id` (`user_id`);
ALTER TABLE tuser DROP INDEX user_name;
ALTER TABLE tuser DROP INDEX user_mobile;
ALTER TABLE `db_doctor`.`tuser` ADD UNIQUE `user_mobile` (`user_mobile`);
ALTER TABLE `db_doctor`.`tuser` ADD INDEX `session_key` (`session_key`);

//2016-12-22  zhangka 添加用户个推CID

ALTER TABLE `tuser`
ADD COLUMN `user_cid`  varchar(32) NOT NULL DEFAULT '' COMMENT '用户个推CID' AFTER `role`;


ALTER TABLE `tadmin`
ADD COLUMN `nickname`  varchar(32) NOT NULL DEFAULT '' COMMENT '管理员昵称' AFTER `email`;


ALTER TABLE `tadmin`
ADD COLUMN `userphone`  varchar(32) NOT NULL DEFAULT '' COMMENT '管理员手机号' AFTER `nickname`;

#订单表
CREATE TABLE `order_accompany` (
  `order_id` bigint(20) NOT NULL COMMENT '订单ID',
  `user_id` bigint(20) NOT NULL COMMENT '用户id',
  `requirement` json NOT NULL COMMENT '用户需求{"hospital_id":"医院ID","hospital_name":"医院名称","hospital_section_id":"医院科室ID","section_id":"科室ID","section_name":"科室名称","doctor_id":"医生ID","doctor_name":"医生姓名"....}',
  `disease_desc` json NOT NULL COMMENT '病情描述{"disease_name":"疾病名称","disease_des":"疾病描述","order_design":"预约备注","order_file":"病情图片"',
  `patient_info` json NOT NULL COMMENT '患者信息{"id_card":"身份证号","order_name":"姓名","order_gender":"用户性别","order_phone":"手机号码","	order_age":"年龄","order_city":"地区code","order_city_name":"地区名称","order_date":"期望就诊时间",""}',
  `process_record` json NOT NULL COMMENT '{"t1"=>{"option_id":"操作者ID","option_type":"操作者类别 用户或者后台管理人员","option_name":"操作者姓名","option_time":"操作时间","option_money":"设置费用或者用户支付的费用"}}',
  `select_info` json NOT NULL COMMENT '{"vip_type":"用户选择的服务类型",....."}',
  `order_type` tinyint(2) NOT NULL COMMENT '订单类型',
  `can_pay` tinyint(1) NOT NULL COMMENT '是否需要支付',
  `pay_money` char(50) NOT NULL COMMENT '支付金额',
  `order_state` tinyint(2) NOT NULL COMMENT '订单状态',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '修改时间',
  `is_delete` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1代表未删除，2已经删除'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='vip陪诊订单';

-- --------------------------------------------------------

--
-- 表的结构 `order_commonweal`
--

CREATE TABLE `order_commonweal` (
  `order_id` bigint(20) NOT NULL COMMENT '订单ID',
  `user_id` bigint(20) NOT NULL COMMENT '用户id',
  `requirement` json NOT NULL COMMENT '用户需求{"hospital_id":"医院ID","hospital_name":"医院名称","hospital_section_id":"医院科室ID","section_id":"科室ID","section_name":"科室名称","doctor_id":"医生ID","doctor_name":"医生姓名"....}',
  `disease_desc` json NOT NULL COMMENT '病情描述{"disease_name":"疾病名称","disease_des":"疾病描述","order_design":"预约备注","order_file":"病情图片"',
  `patient_info` json NOT NULL COMMENT '患者信息{"id_card":"身份证号","order_name":"姓名","order_gender":"用户性别","order_phone":"手机号码","	order_age":"年龄","order_city":"地区code","order_city_name":"地区名称","order_date":"期望就诊时间",""}',
  `process_record` json NOT NULL COMMENT '{"t1"=>{"option_id":"操作者ID","option_type":"操作者类别 用户或者后台管理人员","option_name":"操作者姓名","option_time":"操作时间","option_money":"设置费用或者用户支付的费用"}}',
  `select_info` json NOT NULL COMMENT '{"vip_type":"用户选择的服务类型",....."}',
  `order_type` tinyint(2) NOT NULL COMMENT '订单类型',
  `can_pay` tinyint(1) NOT NULL COMMENT '是否需要支付',
  `pay_money` char(50) NOT NULL COMMENT '支付金额',
  `order_state` tinyint(2) NOT NULL COMMENT '订单状态',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '修改时间',
  `is_delete` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1代表未删除，2已经删除'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='慈善公益';

-- --------------------------------------------------------

--
-- 表的结构 `order_overseas`
--

CREATE TABLE `order_overseas` (
  `order_id` bigint(20) NOT NULL COMMENT '订单ID',
  `user_id` bigint(20) NOT NULL COMMENT '用户id',
  `requirement` json NOT NULL COMMENT '用户需求{"hospital_id":"医院ID","hospital_name":"医院名称","hospital_section_id":"医院科室ID","section_id":"科室ID","section_name":"科室名称","doctor_id":"医生ID","doctor_name":"医生姓名"....}',
  `disease_desc` json NOT NULL COMMENT '病情描述{"disease_name":"疾病名称","disease_des":"疾病描述","order_design":"预约备注","order_file":"病情图片"',
  `patient_info` json NOT NULL COMMENT '患者信息{"id_card":"身份证号","order_name":"姓名","order_gender":"用户性别","order_phone":"手机号码","	order_age":"年龄","order_city":"地区code","order_city_name":"地区名称","order_date":"期望就诊时间",""}',
  `process_record` json NOT NULL COMMENT '{"t1"=>{"option_id":"操作者ID","option_type":"操作者类别 用户或者后台管理人员","option_name":"操作者姓名","option_time":"操作时间","option_money":"设置费用或者用户支付的费用"}}',
  `select_info` json NOT NULL COMMENT '{"vip_type":"用户选择的服务类型",....."}',
  `order_type` tinyint(2) NOT NULL COMMENT '订单类型',
  `can_pay` tinyint(1) NOT NULL COMMENT '是否需要支付',
  `pay_money` char(50) NOT NULL COMMENT '支付金额',
  `order_state` tinyint(2) NOT NULL COMMENT '订单状态',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '修改时间',
  `is_delete` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1代表未删除，2已经删除'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='海外就医';

-- --------------------------------------------------------

--
-- 表的结构 `order_surgery`
--

CREATE TABLE `order_surgery` (
  `order_id` bigint(20) NOT NULL COMMENT '订单ID',
  `user_id` bigint(20) NOT NULL COMMENT '用户id',
  `requirement` json NOT NULL COMMENT '用户需求{"hospital_id":"医院ID","hospital_name":"医院名称","hospital_section_id":"医院科室ID","section_id":"科室ID","section_name":"科室名称","doctor_id":"医生ID","doctor_name":"医生姓名"....}',
  `disease_desc` json NOT NULL COMMENT '病情描述{"disease_name":"疾病名称","disease_des":"疾病描述","order_design":"预约备注","order_file":"病情图片"',
  `patient_info` json NOT NULL COMMENT '患者信息{"id_card":"身份证号","order_name":"姓名","order_gender":"用户性别","order_phone":"手机号码","	order_age":"年龄","order_city":"地区code","order_city_name":"地区名称","order_date":"期望就诊时间",""}',
  `process_record` json NOT NULL COMMENT '{"t1"=>{"option_id":"操作者ID","option_type":"操作者类别 用户或者后台管理人员","option_name":"操作者姓名","option_time":"操作时间","option_money":"设置费用或者用户支付的费用"}}',
  `select_info` json NOT NULL COMMENT '{"vip_type":"用户选择的服务类型",....."}',
  `order_type` tinyint(2) NOT NULL COMMENT '订单类型',
  `can_pay` tinyint(1) NOT NULL COMMENT '是否需要支付',
  `pay_money` char(50) NOT NULL COMMENT '支付金额',
  `order_state` tinyint(2) NOT NULL COMMENT '订单状态',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '修改时间',
  `is_delete` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1代表未删除，2已经删除'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='手术预约';

--
-- 表的结构 `order_treat`
--

CREATE TABLE `order_treat` (
  `order_id` bigint(20) NOT NULL COMMENT '订单ID',
  `user_id` bigint(20) NOT NULL COMMENT '用户id',
  `requirement` json NOT NULL COMMENT '用户需求{"hospital_id":"医院ID","hospital_name":"医院名称","hospital_section_id":"医院科室ID","section_id":"科室ID","section_name":"科室名称","doctor_id":"医生ID","doctor_name":"医生姓名"....}',
  `disease_desc` json NOT NULL COMMENT '病情描述{"disease_name":"疾病名称","disease_des":"疾病描述","order_design":"预约备注","order_file":"病情图片"',
  `patient_info` json NOT NULL COMMENT '患者信息{"id_card":"身份证号","order_name":"姓名","order_gender":"用户性别","order_phone":"手机号码","	order_age":"年龄","order_city":"地区code","order_city_name":"地区名称","order_date":"期望就诊时间",""}',
  `process_record` json NOT NULL COMMENT '{"t1"=>{"option_id":"操作者ID","option_type":"操作者类别 用户或者后台管理人员","option_name":"操作者姓名","option_time":"操作时间","option_money":"设置费用或者用户支付的费用"}}',
  `select_info` json NOT NULL COMMENT '{"vip_type":"用户选择的服务类型",....."}',
  `order_type` tinyint(2) NOT NULL COMMENT '订单类型',
  `can_pay` tinyint(1) NOT NULL COMMENT '是否需要支付',
  `pay_money` char(50) NOT NULL COMMENT '支付金额',
  `order_state` tinyint(2) NOT NULL COMMENT '订单状态',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '修改时间',
  `is_delete` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1代表未删除，2已经删除'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='预约诊疗';

-- --------------------------------------------------------

--
-- 表的结构 `pay_accompany`
--

CREATE TABLE `pay_accompany` (
  `pay_id` bigint(20) NOT NULL COMMENT '支付ID号',
  `user_id` bigint(20) NOT NULL COMMENT '用户id',
  `order_id` bigint(20) NOT NULL COMMENT '订单ID',
  `pay_money` char(50) NOT NULL COMMENT '支付订单金额',
  `pay_type` tinyint(1) NOT NULL COMMENT '1，支付宝 2,微信 3银联',
  `pay_status` tinyint(1) NOT NULL COMMENT '0 未支付， 1支付失败， 2支付成功',
  `pay_account` char(200) NOT NULL COMMENT '支付账号',
  `pay_order_id` char(200) NOT NULL COMMENT '第三方ID',
  `order_type` tinyint(2) NOT NULL COMMENT '订单类型',
  `order_state` tinyint(2) NOT NULL COMMENT '订单状态',
  `requestParams` json NOT NULL COMMENT '第三方回调参数',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '修改时间',
  `is_delete` int(11) NOT NULL COMMENT '1代表未删除，2已经删除'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='vip陪诊订单';

-- --------------------------------------------------------

--
-- 表的结构 `pay_commonweal`
--

CREATE TABLE `pay_commonweal` (
  `pay_id` bigint(20) NOT NULL COMMENT '支付ID号',
  `user_id` bigint(20) NOT NULL COMMENT '用户id',
  `order_id` bigint(20) NOT NULL COMMENT '订单ID',
  `pay_money` char(50) NOT NULL COMMENT '支付订单金额',
  `pay_type` tinyint(1) NOT NULL COMMENT '1，支付宝 2,微信 3银联',
  `pay_status` tinyint(1) NOT NULL COMMENT '0 未支付， 1支付失败， 2支付成功',
  `pay_account` char(200) NOT NULL COMMENT '支付账号',
  `pay_order_id` char(200) NOT NULL COMMENT '第三方ID',
  `order_type` tinyint(2) NOT NULL COMMENT '订单类型',
  `order_state` tinyint(2) NOT NULL COMMENT '订单状态',
  `requestParams` json NOT NULL COMMENT '第三方回调参数',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '修改时间',
  `is_delete` int(11) NOT NULL COMMENT '1代表未删除，2已经删除'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='慈善公益';

-- --------------------------------------------------------

--
-- 表的结构 `pay_overseas`
--

CREATE TABLE `pay_overseas` (
  `pay_id` bigint(20) NOT NULL COMMENT '支付ID号',
  `user_id` bigint(20) NOT NULL COMMENT '用户id',
  `order_id` bigint(20) NOT NULL COMMENT '订单ID',
  `pay_money` char(50) NOT NULL COMMENT '支付订单金额',
  `pay_type` tinyint(1) NOT NULL COMMENT '1，支付宝 2,微信 3银联',
  `pay_status` tinyint(1) NOT NULL COMMENT '0 未支付， 1支付失败， 2支付成功',
  `pay_account` char(200) NOT NULL COMMENT '支付账号',
  `pay_order_id` char(200) NOT NULL COMMENT '第三方ID',
  `order_type` tinyint(2) NOT NULL COMMENT '订单类型',
  `order_state` tinyint(2) NOT NULL COMMENT '订单状态',
  `requestParams` json NOT NULL COMMENT '第三方回调参数',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '修改时间',
  `is_delete` int(11) NOT NULL COMMENT '1代表未删除，2已经删除'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='海外就医';

-- --------------------------------------------------------

--
-- 表的结构 `pay_surgery`
--

CREATE TABLE `pay_surgery` (
  `pay_id` bigint(20) NOT NULL COMMENT '支付ID号',
  `user_id` bigint(20) NOT NULL COMMENT '用户id',
  `order_id` bigint(20) NOT NULL COMMENT '订单ID',
  `pay_money` char(50) NOT NULL COMMENT '支付订单金额',
  `pay_type` tinyint(1) NOT NULL COMMENT '1，支付宝 2,微信 3银联',
  `pay_status` tinyint(1) NOT NULL COMMENT '0 未支付， 1支付失败， 2支付成功',
  `pay_account` char(200) NOT NULL COMMENT '支付账号',
  `pay_order_id` char(200) NOT NULL COMMENT '第三方ID',
  `order_type` tinyint(2) NOT NULL COMMENT '订单类型',
  `order_state` tinyint(2) NOT NULL COMMENT '订单状态',
  `requestParams` json NOT NULL COMMENT '第三方回调参数',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '修改时间',
  `is_delete` int(11) NOT NULL COMMENT '1代表未删除，2已经删除'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='手术支付';

-- --------------------------------------------------------

--
-- 表的结构 `pay_treat`
--

CREATE TABLE `pay_treat` (
  `pay_id` bigint(20) NOT NULL COMMENT '支付ID号',
  `user_id` bigint(20) NOT NULL COMMENT '用户id',
  `order_id` bigint(20) NOT NULL COMMENT '订单ID',
  `pay_money` char(50) NOT NULL COMMENT '支付订单金额',
  `pay_type` tinyint(1) NOT NULL COMMENT '1，支付宝 2,微信 3银联',
  `pay_status` tinyint(1) NOT NULL COMMENT '0 未支付， 1支付失败， 2支付成功',
  `pay_account` char(200) NOT NULL COMMENT '支付账号',
  `pay_order_id` char(200) NOT NULL COMMENT '第三方ID',
  `order_type` tinyint(2) NOT NULL COMMENT '订单类型',
  `order_state` tinyint(2) NOT NULL COMMENT '订单状态',
  `requestParams` json NOT NULL COMMENT '第三方回调参数',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '修改时间',
  `is_delete` int(11) NOT NULL COMMENT '1代表未删除，2已经删除'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='预约诊疗';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `order_accompany`
--
ALTER TABLE `order_accompany`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_commonweal`
--
ALTER TABLE `order_commonweal`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_overseas`
--
ALTER TABLE `order_overseas`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_surgery`
--
ALTER TABLE `order_surgery`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_treat`
--
ALTER TABLE `order_treat`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `pay_accompany`
--
ALTER TABLE `pay_accompany`
  ADD PRIMARY KEY (`pay_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `pay_commonweal`
--
ALTER TABLE `pay_commonweal`
  ADD PRIMARY KEY (`pay_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `pay_overseas`
--
ALTER TABLE `pay_overseas`
  ADD PRIMARY KEY (`pay_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `pay_surgery`
--
ALTER TABLE `pay_surgery`
  ADD PRIMARY KEY (`pay_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `pay_treat`
--
ALTER TABLE `pay_treat`
  ADD PRIMARY KEY (`pay_id`),
  ADD KEY `user_id` (`user_id`);

 CREATE TABLE `tlink_page` (
  `page_id` bigint(20) NOT NULL COMMENT '主键ID',
  `page_image` char(255) NOT NULL COMMENT '图片地址',
  `page_url` char(255) NOT NULL COMMENT '跳转地址',
  `create_time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='引导页';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tlink_page`
--
ALTER TABLE `tlink_page`
  ADD PRIMARY KEY (`page_id`);


---版本是否强制更新--

ALTER TABLE `tversion`
ADD COLUMN `version_force`  tinyint(2) NULL DEFAULT 0 COMMENT '是否强制更新1：是0：否' AFTER `version_device`;

---管理员订单修改日志记录----
CREATE TABLE `order_operation_log` (
  `operation_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '主键',
  `manager_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '操作员id',
  `manager_name` char(20) NOT NULL DEFAULT '' COMMENT '操作员名字',
  `operation_model` varchar(100) NOT NULL DEFAULT '' COMMENT '操作模块',
  `order_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '订单id',
  `operation_type` tinyint(2) NOT NULL DEFAULT '1' COMMENT '日志类型1;更改订单状态,2:设置手术费用,3:设置手术时间',
  `operation_desc` varchar(200) DEFAULT '' COMMENT '备注',
  `operation_details` json DEFAULT NULL COMMENT '描述信息',
  `create_time` int(10) NOT NULL DEFAULT '0' COMMENT '操作时间',
  PRIMARY KEY (`operation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='管理员操作日志';

---医生显示列表---
ALTER TABLE `dtsection` ADD `is_doctor_common` TINYINT(1) NOT NULL DEFAULT '0' AFTER `is_common`;


--新加操作日志类型---
ALTER TABLE `order_operation_log`
ADD COLUMN `order_type`  tinyint(6) NOT NULL DEFAULT 0 COMMENT '订单类型' AFTER `order_id`;


-----------------------cms----------

--
-- 表的结构 `info_channel`
--

CREATE TABLE `info_channel` (
  `channel_no` bigint(20) NOT NULL DEFAULT '0' COMMENT '文章频道分类',
  `channel_name` char(10) NOT NULL DEFAULT '' COMMENT '频道名称',
  `sort_no` bigint(20) NOT NULL DEFAULT '0' COMMENT '排序字段',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `user_id` bigint(20) NOT NULL DEFAULT '0',
  `user_name` char(20) NOT NULL DEFAULT '0',
  `update_time` int(11) NOT NULL DEFAULT '0',
  `is_delete` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='频道表';

ALTER TABLE `info_channel`
  ADD PRIMARY KEY (`channel_no`);

CREATE TABLE `info_detail` (
  `material_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '主键',
  `news_content` text COMMENT '资讯内容',
  `photo_content` json DEFAULT NULL COMMENT '图集内容'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='素材分表';

ALTER TABLE `info_detail`
  ADD PRIMARY KEY (`material_id`);


CREATE TABLE `info_material` (
  `material_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '主键',
  `news_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0广告，1资讯',
  `show_type` tinyint(1) NOT NULL DEFAULT '2' COMMENT '0 banner,2列表单图,3列表多图,4开机动画',
  `media_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 图文 1 多图 2 视频 3音频',
  `channel_no` bigint(20) NOT NULL DEFAULT '0' COMMENT '文章频道分类',
  `channel_name` char(50) NOT NULL DEFAULT '' COMMENT '频道名称',
  `author` varchar(100) DEFAULT NULL,
  `info_source` varchar(200) DEFAULT NULL,
  `title` char(150) DEFAULT '' COMMENT '资讯标题',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 待提交 1 已提交 2审核通过 3审核未通过',
  `news_photo` json DEFAULT NULL COMMENT '资讯图片',
  `news_url` char(255) DEFAULT NULL COMMENT '资讯h5详情网页链接',
  `start_time` int(11) NOT NULL DEFAULT '0',
  `end_time` bigint(11) NOT NULL DEFAULT '9999999999',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '修改时间',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `is_top` int(1) DEFAULT '0' COMMENT '0 不推荐，1推荐',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '编辑人id',
  `user_name` varchar(100) DEFAULT NULL,
  `is_delete` int(11) NOT NULL DEFAULT '1' COMMENT '1、未删除，2已删除'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='素材表';

ALTER TABLE `info_material`
  ADD PRIMARY KEY (`material_id`);

CREATE TABLE `info_material_log` (
  `operation_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '主键',
  `manager_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '操作员id',
  `manager_name` char(20) NOT NULL DEFAULT '' COMMENT '操作员名字',
  `operation_type` tinyint(2) NOT NULL DEFAULT '1' COMMENT '日志类型1;编辑,2:提交,3:审核',
  `operation_desc` text COMMENT '备注',
  `material_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '主键',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '修改时间',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `is_delete` int(11) NOT NULL DEFAULT '1' COMMENT '1、未删除，2已删除'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='素材表操作日志表';

ALTER TABLE `info_material_log`
  ADD PRIMARY KEY (`operation_id`);

CREATE TABLE `info_news` (
  `material_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '主键',
  `news_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0广告，1资讯',
  `show_type` tinyint(1) NOT NULL DEFAULT '2' COMMENT '0 banner,2列表单图,3列表多图,4开机动画',
  `media_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 图文 1 多图 2 视频 3音频',
  `title` char(150) DEFAULT '' COMMENT '资讯标题',
  `news_photo` json DEFAULT NULL COMMENT '资讯图片',
  `news_url` char(255) DEFAULT NULL COMMENT '资讯h5详情网页链接',
  `channel_no` bigint(20) NOT NULL DEFAULT '0' COMMENT '文章频道分类',
  `channel_name` char(10) NOT NULL DEFAULT '' COMMENT '频道名称',
  `author` varchar(100) DEFAULT NULL,
  `info_source` varchar(200) DEFAULT NULL,
  `collection_time` int(10) NOT NULL DEFAULT '0' COMMENT '收藏次数',
  `sort_no` bigint(20) NOT NULL DEFAULT '0' COMMENT '排序数字',
  `is_push` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 不推送，1 推送',
  `start_time` int(11) NOT NULL DEFAULT '0',
  `end_time` bigint(11) DEFAULT '9999999999',
  `good_time` bigint(20) NOT NULL DEFAULT '0' COMMENT '点赞次数',
  `publish_time` int(11) NOT NULL DEFAULT '0' COMMENT '发布时间',
  `push_time` int(11) NOT NULL DEFAULT '0' COMMENT '推送时间',
  `del_time` int(11) NOT NULL DEFAULT '0' COMMENT '删除时间',
  `is_publish` tinyint(1) NOT NULL DEFAULT '2' COMMENT '1:已发布2:指定时间发布',
  `keep_time` tinyint(2) NOT NULL DEFAULT '0' COMMENT '开机广告的持续时间',
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  `is_delete` int(11) NOT NULL DEFAULT '1' COMMENT '1、代表未删除，2代表已经删除',
  `user_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '发布人id',
  `user_name` char(30) NOT NULL DEFAULT '' COMMENT '发布人名字'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='发布内容表';

ALTER TABLE `info_news`
  ADD PRIMARY KEY (`material_id`);

CREATE TABLE `tuser_collection` (
  `tc_user_id` bigint(20) NOT NULL,
  `tc_news_id` bigint(20) NOT NULL COMMENT '文章ID',
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  `is_delete` int(1) NOT NULL DEFAULT '1' COMMENT '1 未删除 2 已删除'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户收藏列表';
ALTER TABLE `tuser_collection`
  ADD PRIMARY KEY (`tc_user_id`,`tc_news_id`);


ALTER TABLE `tadmin`
ADD COLUMN `sys_type`  tinyint(2) NOT NULL DEFAULT 1 COMMENT '1:订单系统2:cms系统' AFTER `updated_at`;


ALTER TABLE `tadmin`
ADD COLUMN `roleId`  json NULL  COMMENT '角色id' AFTER `sys_type`;
ALTER TABLE `tversion` ADD `version_code` CHAR(100) NULL DEFAULT NULL AFTER `version_name`;

ALTER TABLE `tadmin`
DROP INDEX `email`;


ALTER TABLE `order_accompany`
ADD COLUMN `is_look`  tinyint(1) NOT NULL DEFAULT 1 COMMENT '1:未被查看;2:已查看' AFTER `is_delete`;


ALTER TABLE `order_commonweal`
ADD COLUMN `is_look`  tinyint(1) NOT NULL DEFAULT 1 COMMENT '1:未被查看;2:已查看' AFTER `is_delete`;


ALTER TABLE `order_overseas`
ADD COLUMN `is_look`  tinyint(1) NOT NULL DEFAULT 1 COMMENT '1:未被查看;2:已查看' AFTER `is_delete`;


ALTER TABLE `order_surgery`
ADD COLUMN `is_look`  tinyint(1) NOT NULL DEFAULT 1 COMMENT '1:未被查看;2:已查看' AFTER `is_delete`;


ALTER TABLE `order_treat`
ADD COLUMN `is_look`  tinyint(1) NOT NULL DEFAULT 1 COMMENT '1:未被查看;2:已查看' AFTER `is_delete`;


-------------------cms -------------------



-------------------shop------------------
CREATE TABLE `tuser_express_address` (
  `address_id` bigint(20) NOT NULL COMMENT '主键ID',
  `user_id` bigint(20) NOT NULL COMMENT '用户ID',
  `user_name` varchar(100) NOT NULL COMMENT '收货人姓名',
  `user_district_id` bigint(20) DEFAULT NULL COMMENT '收货人地区ID',
  `user_district_address` varchar(100) DEFAULT NULL COMMENT '收货人地区信息',
  `user_detail_address` varchar(200) DEFAULT NULL COMMENT '收货人详细地址信息',
  `user_phone` varchar(24) NOT NULL COMMENT '收货人手机号',
  `is_default` int(1) NOT NULL DEFAULT '0' COMMENT '是否默认 1 默认 0 其他',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  `is_delete` int(1) NOT NULL DEFAULT '1' COMMENT '是否删除 1 未删除 2 已删除'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='收货人地址管理';

ALTER TABLE `tuser_express_address`
  ADD PRIMARY KEY (`address_id`);

CREATE TABLE `card_info` (
  `card_no` bigint(20) NOT NULL COMMENT '卡主键ID',
  `card_alias_no` varchar(100) NOT NULL COMMENT '卡号ID',
  `goods_id` bigint(20) NOT NULL COMMENT '商品ID',
  `security_code` varchar(100) NOT NULL DEFAULT '0' COMMENT '激活码',
  `goods_small_image` varchar(200) NOT NULL COMMENT '商品列表图',
  `active_user_id` bigint(20) NOT NULL COMMENT '激活用户Id',
  `active_user_name` varchar(50) NOT NULL COMMENT '激活用户姓名',
  `active_user_type` int(1) NOT NULL COMMENT '激活卡的用户类型：0 普通用户， 1 后台管理人员',
  `goods_service` json NOT NULL COMMENT '服务类型：[{"id":服务ID, "count":"数量"},{"id":服务ID, "count":"数量"}]',
  `apply_user_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '使用卡的用户id',
  `apply_user_name` varchar(50) NOT NULL DEFAULT '0' COMMENT '使用卡的用户姓名',
  `service_user_limit` int(11) NOT NULL COMMENT '限制人数',
  `phone_no` varchar(24) NOT NULL DEFAULT '0' COMMENT '使用卡用户的电话',
  `active_status` int(1) NOT NULL DEFAULT '0' COMMENT '0 未激活 1 已激活',
  `active_type` int(1) NOT NULL DEFAULT '0' COMMENT '0 自己使用 1 给他人激活',
  `active_time` int(11) NOT NULL DEFAULT '0' COMMENT '激活时间',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  `is_delete` int(1) NOT NULL DEFAULT '1' COMMENT '1 未删除 2 已删除'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='卡信息表';

ALTER TABLE `card_info`
  ADD PRIMARY KEY (`card_no`);

CREATE TABLE `card_order_service` (
  `card_order_id` bigint(20) NOT NULL COMMENT '主键ID',
  `persion_user_id` bigint(20) NOT NULL COMMENT '用户ID',
  `card_no` bigint(20) NOT NULL COMMENT '卡号 主键',
  `persion_add_type` int(1) NOT NULL COMMENT '1 正常使用 2 额外添加',
  `persion_type` int(1) NOT NULL COMMENT '1 用户添加 2 后台添加',
  `goods_service_type` int(2) NOT NULL COMMENT '用户的使用消费类型',
  `goods_service_name` varchar(50) NOT NULL COMMENT '用户的使用消费类型名称',
  `user_name` varchar(50) NOT NULL COMMENT '用户ID',
  `user_phone` varchar(24) NOT NULL COMMENT '手机号码',
  `user_card_no` varchar(50) NOT NULL COMMENT '身份证号',
  `user_sex` int(1) NOT NULL COMMENT '0 未知 1 男 2 女',
  `user_district_id` bigint(20) NOT NULL COMMENT '地区ID',
  `user_district_address` varchar(100) NOT NULL COMMENT '地区信息',
  `user_detail_address` varchar(200) NOT NULL COMMENT '详细地址',
  `user_other_info` json NOT NULL COMMENT '其他信息(其他信息，体检项目类型等)',
  `service_status` int(1) NOT NULL COMMENT '0 已激活 其他状态见配置文件',
  `service_process_status` int(1) NOT NULL COMMENT '1 生效 2 失效',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  `is_delete` int(1) NOT NULL DEFAULT '1' COMMENT '1 未删除 2 已删除'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='服务消费订单表';

ALTER TABLE `card_order_service`
  ADD PRIMARY KEY (`card_order_id`);

CREATE TABLE `card_persion_user` (
  `persion_user_id` bigint(20) NOT NULL COMMENT '主键ID',
  `card_no` bigint(20) NOT NULL COMMENT '卡号 主键',
  `persion_type` int(1) NOT NULL DEFAULT '1' COMMENT '1 用户添加 2 后台添加',
  `user_name` varchar(100) NOT NULL COMMENT '用户ID',
  `user_phone` varchar(24) DEFAULT NULL COMMENT '手机号码',
  `user_card_no` varchar(50) DEFAULT NULL COMMENT '身份证号',
  `user_sex` int(1) NOT NULL DEFAULT '0' COMMENT '0 未知 1 男 2 女',
  `user_district_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '地区ID',
  `user_district_address` varchar(100) DEFAULT NULL COMMENT '地区信息',
  `user_detail_address` varchar(200) DEFAULT NULL COMMENT '详细地址',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  `is_delete` int(1) NOT NULL DEFAULT '1' COMMENT '1 未删除 2 已删除'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户受益组管理';

ALTER TABLE `card_persion_user`
  ADD PRIMARY KEY (`persion_user_id`);

CREATE TABLE `goods_info` (
  `goods_id` bigint(20) NOT NULL COMMENT '商品ID',
  `goods_name` varchar(100) NOT NULL COMMENT '商品名称',
  `goods_type` int(1) NOT NULL COMMENT '商品类型',
  `goods_big_image` varchar(200) NOT NULL COMMENT '商品大图',
  `goods_small_image` varchar(200) NOT NULL COMMENT '商品列表图',
  `goods_image` json NOT NULL COMMENT '商品展示图片',
  `goods_amount` int(11) NOT NULL DEFAULT '0' COMMENT '销量',
  `user_id` bigint(20) NOT NULL COMMENT '上架商品人员id',
  `user_name` bigint(20) NOT NULL COMMENT '上架人用户名',
  `goods_onsalt_time` int(11) NOT NULL DEFAULT '0' COMMENT '上架时间',
  `is_onsalt` int(1) NOT NULL COMMENT '是否上架 0 不上架 1 上架',
  `goods_service` json NOT NULL COMMENT '服务类型：[{"id":服务ID, "count":"数量"},{"id":服务ID, "count":"数量"}]',
  `goods_service_limit` int(11) NOT NULL COMMENT '限制使用人数',
  `goods_url` varchar(200) NOT NULL COMMENT '商品html页面网址',
  `detail_url` varchar(200) NOT NULL COMMENT '商品详情html页面网址',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  `is_delete` int(1) NOT NULL DEFAULT '1' COMMENT '1 未删除 2 已删除'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品信息表';

ALTER TABLE `goods_info`
  ADD PRIMARY KEY (`goods_id`);

CREATE TABLE `goods_price` (
  `goods_id` bigint(20) NOT NULL COMMENT '商品ID',
  `original_price` varchar(50) NOT NULL COMMENT '原价',
  `now_price` varchar(50) NOT NULL COMMENT '售价',
  `freight_price` varchar(50) NOT NULL COMMENT '运费',
  `discount` varchar(50) NOT NULL COMMENT '折扣',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  `is_delete` int(1) NOT NULL DEFAULT '1' COMMENT '1 未删除 2 已删除'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品价格表';

ALTER TABLE `goods_price`
  ADD PRIMARY KEY (`goods_id`);

CREATE TABLE `goods_price_log` (
  `price_log_id` bigint(20) NOT NULL COMMENT '价格logID',
  `goods_id` bigint(20) NOT NULL COMMENT '商品ID',
  `original_price` varchar(50) NOT NULL COMMENT '原价',
  `now_price` varchar(50) NOT NULL COMMENT '售价',
  `discount` varchar(50) NOT NULL COMMENT '折扣',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  `is_delete` int(1) NOT NULL DEFAULT '1' COMMENT '1 未删除 2 已删除'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='价格变更日志表';

ALTER TABLE `goods_price_log`
  ADD PRIMARY KEY (`price_log_id`);

CREATE TABLE `order_accompany_detail` (
  `order_id` bigint(20) NOT NULL COMMENT '订单ID',
  `address_id` bigint(20) NOT NULL COMMENT '收货人地址ID',
  `user_id` bigint(20) NOT NULL COMMENT '用户ID',
  `user_name` varchar(50) NOT NULL COMMENT '收货人姓名',
  `user_district_id` bigint(20) NOT NULL COMMENT '收货人地区ID',
  `user_district_address` varchar(100) NOT NULL COMMENT '收货人地区信息',
  `user_detail_address` varchar(200) NOT NULL COMMENT '收货人详细信息',
  `user_phone` varchar(24) NOT NULL COMMENT '收货人手机号',
  `goods_id` bigint(20) NOT NULL COMMENT '商品ID',
  `goods_name` varchar(100) NOT NULL COMMENT '商品名称',
  `goods_type` int(1) NOT NULL COMMENT '商品类型',
  `goods_big_image` varchar(200) NOT NULL COMMENT '商品大图',
  `goods_small_image` varchar(200) NOT NULL COMMENT '商品列表图',
  `goods_image` json NOT NULL COMMENT '商品展示图片',
  `goods_amount` varchar(24) NOT NULL COMMENT '销量',
  `goods_onsalt_time` int(11) NOT NULL COMMENT '上架时间',
  `is_onsalt` int(1) NOT NULL COMMENT '是否上架',
  `goods_service` json NOT NULL COMMENT '服务类型：[{"id":服务ID, "count":"数量"},{"id":服务ID, "count":"数量"}]',
  `goods_service_limit` int(11) NOT NULL COMMENT '限制使用人数',
  `goods_url` varchar(200) NOT NULL COMMENT '商品html页面网址',
  `detail_url` varchar(200) NOT NULL COMMENT '商品详情html页面网址',
  `now_price` varchar(50) NOT NULL COMMENT '现价',
  `freight_price` varchar(50) NOT NULL COMMENT '运费',
  `is_invoice` int(1) NOT NULL COMMENT '是否需要发票0 不需要 1 需要',
  `invoice_type` int(1) NOT NULL COMMENT '发票类型 1 个人 2 公司',
  `invoice_title` varchar(100) NOT NULL COMMENT '发票抬头',
  `invoice_content` varchar(250) NOT NULL COMMENT '发票内容',
  `order_detail_note` text NOT NULL COMMENT '订单备注',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  `is_delete` int(1) NOT NULL DEFAULT '1' COMMENT '1 未删除 2 已删除'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单的详细信息';

ALTER TABLE `order_accompany_detail`
  ADD PRIMARY KEY (`order_id`);

ALTER TABLE `goods_info` ADD `goods_expire_time` INT(11) NOT NULL COMMENT '时效' AFTER `goods_type`;
ALTER TABLE `order_accompany_detail` ADD `goods_expire_time` INT(11) NOT NULL COMMENT '时效' AFTER `goods_type`;
ALTER TABLE `card_info` ADD `active_expire_time` INT(11) NOT NULL DEFAULT '0' COMMENT '到期时间' AFTER `goods_id`;
ALTER TABLE `order_accompany_detail` ADD `is_express` INT(1) NOT NULL DEFAULT '0' COMMENT '是否快递 0 不快递 1快递' AFTER `user_phone`, ADD `express_id` BIGINT(20) NOT NULL DEFAULT '0' COMMENT '快递主键ID' AFTER `is_express`;

CREATE TABLE `order_accompany_detail_express` (
  `express_id` bigint(20) NOT NULL COMMENT '快递主键ID',
  `express_company` varchar(100) NOT NULL COMMENT '快递公司名称',
  `express_com` varchar(100) NOT NULL COMMENT '快递公司编号',
  `express_no` varchar(100) NOT NULL COMMENT '快递单号',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '修改时间',
  `is_delete` int(1) NOT NULL DEFAULT '1' COMMENT '是否删除'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='快递查询表';

ALTER TABLE `order_accompany_detail_express`
  ADD PRIMARY KEY (`express_id`);
ALTER TABLE `card_info` ADD `goods_name` VARCHAR(200) NOT NULL AFTER `goods_id`;

CREATE TABLE `card_info_secret` (
  `secret_id` bigint(20) NOT NULL COMMENT '卡密ID',
  `secret_alias_no` char(200) NOT NULL COMMENT '卡号',
  `secret_buyer_id` bigint(20) NOT NULL COMMENT '购买人ID',
  `security_code` char(200) NOT NULL COMMENT '卡号激活码',
  `secret_phone` varchar(20) NOT NULL COMMENT '激活手机号',
  `secret_phone_secret` varchar(20) NOT NULL COMMENT '激活手机号密码',
  `secret_phone_expire` int(11) NOT NULL COMMENT '激活手机号密码过期时间',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  `is_delete` int(1) NOT NULL DEFAULT '1' COMMENT '是否删除'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='健康卡密表';

ALTER TABLE `card_info_secret`
  ADD PRIMARY KEY (`secret_id`),
  ADD UNIQUE KEY `secret_alias_no` (`secret_alias_no`),
  ADD UNIQUE KEY `security_code` (`security_code`);

ALTER TABLE `card_info` DROP `security_code`;
ALTER TABLE `card_info_secret` ADD `is_active` INT(1) NOT NULL DEFAULT '0' COMMENT '是否激活 0 未激活 1 已激活' AFTER `secret_buyer_id`, ADD `active_type` INT(1) NOT NULL DEFAULT '0' COMMENT '激活方式 1 手机激活 2 卡密激活' AFTER `is_active`;
ALTER TABLE `order_accompany_detail` ADD `buy_number` INT(11) NOT NULL AFTER `user_id`;

ALTER TABLE `card_persion_user` ADD `is_default` INT(1) NOT NULL DEFAULT '0' AFTER `persion_type`;
CREATE TABLE `tuser_service_person` (
  `address_id` bigint(20) NOT NULL COMMENT '主键ID',
  `user_id` bigint(20) NOT NULL COMMENT '用户ID',
  `user_name` varchar(100) NOT NULL COMMENT '姓名',
  `user_sex` int(1) NOT NULL DEFAULT '0' COMMENT '0 未知 1 男 2 女',
  `user_district_id` bigint(20) DEFAULT NULL COMMENT '地区ID',
  `user_card_no` varchar(50) NOT NULL COMMENT '身份证号',
  `user_district_address` varchar(100) DEFAULT NULL COMMENT '地区信息',
  `user_detail_address` varchar(200) DEFAULT NULL COMMENT '详细地址信息',
  `user_phone` varchar(24) NOT NULL COMMENT '手机号',
  `is_default` int(1) NOT NULL DEFAULT '0' COMMENT '是否默认 1 默认 0 其他',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  `is_delete` int(1) NOT NULL DEFAULT '1' COMMENT '是否删除 1 未删除 2 已删除'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='受益人信息管理';

ALTER TABLE `tuser_service_person`
  ADD PRIMARY KEY (`address_id`);
ALTER TABLE `order_accompany_detail` CHANGE `order_detail_note` `order_detail_note` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '订单备注';
ALTER TABLE `card_info_secret` CHANGE `secret_phone` `secret_phone` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '激活手机号', CHANGE `secret_phone_secret` `secret_phone_secret` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '激活手机号密码', CHANGE `secret_phone_expire` `secret_phone_expire` INT(11) NOT NULL DEFAULT '0' COMMENT '激活手机号密码过期时间';
ALTER TABLE `order_accompany_detail` CHANGE `user_district_id` `user_district_id` BIGINT(20) NOT NULL DEFAULT '0' COMMENT '收货人地区ID';





ALTER TABLE `order_accompany_detail`
ADD COLUMN `invoice_header_name`  varchar(100) NOT NULL DEFAULT '' COMMENT '抬头名称' AFTER `invoice_title`;




ALTER TABLE `goods_info`
ADD COLUMN `goods_card_type`  tinyint(2) NOT NULL DEFAULT 1 COMMENT '卡类别:1:电子卡,2:实物卡' AFTER `goods_type`;


ALTER TABLE `card_order_service`
ADD COLUMN `goods_category_type`  smallint(3) NOT NULL DEFAULT 1 COMMENT '服务项类别1:挂号;2:体检;3保险;4:客服;5档案' AFTER `goods_service_type`;
ALTER TABLE `tuser` CHANGE `user_pass` `user_pass` VARCHAR(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '密码';
ALTER TABLE `card_order_service` CHANGE `user_district_id` `user_district_id` BIGINT(20) NULL DEFAULT NULL COMMENT '地区ID';

ALTER TABLE `card_order_service`
ADD COLUMN `service_process_info`  json NULL COMMENT '{"s1":{"status":{"name":"已提交","val":1,"process_status":"1"},"optionlog":[]}}' AFTER `service_process_status`;
ALTER TABLE `card_info` ADD `order_id` BIGINT(20) NOT NULL DEFAULT '0' COMMENT '订单ID' AFTER `goods_name`;


---------------1.5---------------
ALTER TABLE `order_accompany` ADD `order_version` INT(11) NOT NULL DEFAULT '1' COMMENT '当前version版本号' AFTER `order_state`, ADD `order_process` INT(11) NOT NULL DEFAULT '0' COMMENT '订单进行状态0 进行中 1 已完成 2已取消' AFTER `order_version`;
ALTER TABLE `order_commonweal` ADD `order_version` INT(11) NOT NULL DEFAULT '1' COMMENT '当前version版本号' AFTER `order_state`, ADD `order_process` INT(11) NOT NULL DEFAULT '0' COMMENT '订单进行状态0 进行中 1 已完成 2已取消' AFTER `order_version`;
ALTER TABLE `order_overseas` ADD `order_version` INT(11) NOT NULL DEFAULT '1' COMMENT '当前version版本号' AFTER `order_state`, ADD `order_process` INT(11) NOT NULL DEFAULT '0' COMMENT '订单进行状态0 进行中 1 已完成 2已取消' AFTER `order_version`;
ALTER TABLE `order_surgery` ADD `order_version` INT(11) NOT NULL DEFAULT '1' COMMENT '当前version版本号' AFTER `order_state`, ADD `order_process` INT(11) NOT NULL DEFAULT '0' COMMENT '订单进行状态0 进行中 1 已完成 2已取消' AFTER `order_version`;
ALTER TABLE `order_treat` ADD `order_version` INT(11) NOT NULL DEFAULT '1' COMMENT '当前version版本号' AFTER `order_state`, ADD `order_process` INT(11) NOT NULL DEFAULT '0' COMMENT '订单进行状态0 进行中 1 已完成 2已取消' AFTER `order_version`;

CREATE TABLE `order_group_customer` (
 `order_id` bigint(20) NOT NULL COMMENT '订单ID',
 `user_id` bigint(20) NOT NULL COMMENT '用户id',
 `requirement` json NOT NULL COMMENT '用户需求{"hospital_id":"医院ID","hospital_name":"医院名称","hospital_section_id":"医院科室ID","section_id":"科室ID","section_name":"科室名称","doctor_id":"医生ID","doctor_name":"医生姓名"....}',
 `disease_desc` json NOT NULL COMMENT '病情描述{"disease_name":"疾病名称","disease_des":"疾病描述","order_design":"预约备注","order_file":"病情图片"',
 `patient_info` json NOT NULL COMMENT '患者信息{"id_card":"身份证号","order_name":"姓名","order_gender":"用户性别","order_phone":"手机号码","    order_age":"年龄","order_city":"地区code","order_city_name":"地区名称","order_date":"期望就诊时间",""}',
 `process_record` json NOT NULL COMMENT '{"t1"=>{"option_id":"操作者ID","option_type":"操作者类别 用户或者后台管理人员","option_name":"操作者姓名","option_time":"操作时间","option_money":"设置费用或者用户支付的费用"}}',
 `select_info` json NOT NULL COMMENT '{"vip_type":"用户选择的服务类型",....."}',
 `order_type` tinyint(2) NOT NULL COMMENT '订单类型',
 `can_pay` tinyint(1) NOT NULL COMMENT '是否需要支付',
 `pay_money` char(50) NOT NULL COMMENT '支付金额',
 `order_state` tinyint(2) NOT NULL COMMENT '订单状态',
 `create_time` int(11) NOT NULL COMMENT '创建时间',
 `update_time` int(11) NOT NULL COMMENT '修改时间',
 `is_delete` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1代表未删除，2已经删除',
 `is_look` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1:未被查看;2:已查看',
 PRIMARY KEY (`order_id`),
 KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='集团客户订单';

ALTER TABLE `order_group_customer` ADD `order_version` INT(11) NOT NULL DEFAULT '1' COMMENT '当前version版本号' AFTER `order_state`, ADD `order_process` INT(11) NOT NULL DEFAULT '0' COMMENT '订单进行状态0 进行中 1 已完成 2已取消' AFTER `order_version`;


CREATE TABLE `pay_group_customer` (
 `pay_id` bigint(20) NOT NULL COMMENT '支付ID号',
 `user_id` bigint(20) NOT NULL COMMENT '用户id',
 `order_id` bigint(20) NOT NULL COMMENT '订单ID',
 `pay_money` char(50) NOT NULL COMMENT '支付订单金额',
 `pay_type` tinyint(1) NOT NULL COMMENT '1，支付宝 2,微信 3银联',
 `pay_status` tinyint(1) NOT NULL COMMENT '0 未支付， 1支付失败， 2支付成功',
 `pay_account` char(200) NOT NULL COMMENT '支付账号',
 `pay_order_id` char(200) NOT NULL COMMENT '第三方ID',
 `order_type` tinyint(2) NOT NULL COMMENT '订单类型',
 `order_state` tinyint(2) NOT NULL COMMENT '订单状态',
 `requestParams` json NOT NULL COMMENT '第三方回调参数',
 `create_time` int(11) NOT NULL COMMENT '创建时间',
 `update_time` int(11) NOT NULL COMMENT '修改时间',
 `is_delete` int(11) NOT NULL COMMENT '1代表未删除，2已经删除',
 PRIMARY KEY (`pay_id`),
 KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='集团客户订单';


CREATE TABLE `group_customer` (
 `customer_id` bigint(20) NOT NULL COMMENT '主键ID',
 `customer_name` char(200) NOT NULL COMMENT '客户名称',
 `user_id` bigint(20) NOT NULL COMMENT '用户id',
 `items_price_count` char(200) NOT NULL COMMENT '所有项目费用总计',
 `items_time_count` char(200) NOT NULL COMMENT '所有项目次数总计',
 `create_time` int(11) NOT NULL COMMENT '创建时间',
 `update_time` int(11) NOT NULL COMMENT '修改时间',
 `is_delete` tinyint(1) NOT NULL COMMENT '1代表未删除，2已经删除',
 PRIMARY KEY (`customer_id`),
 KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='集团客户表';



CREATE TABLE `group_customer_items` (
 `items_id` bigint(20) NOT NULL COMMENT '主键ID',
 `customer_id` bigint(20) NOT NULL COMMENT '客户id',
 `items_name` char(200) NOT NULL COMMENT '项目名称',
 `items_price` char(20) NOT NULL COMMENT '项目费用',
 `items_price_sum` char(200) NOT NULL COMMENT '项目费用总计',
 `items_time_sum` char(200) NOT NULL COMMENT '项目次数总计',
 `create_time` int(11) NOT NULL COMMENT '创建时间',
 `update_time` int(11) NOT NULL COMMENT '修改时间',
 `is_delete` tinyint(1) NOT NULL COMMENT '1代表未删除，2已经删除',
 PRIMARY KEY (`items_id`),
 KEY `customer_id` (`customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='客户项目表';


CREATE TABLE `group_customer_order` (
 `order_id` bigint(20) NOT NULL COMMENT '主键ID',
 `customer_id` bigint(20) NOT NULL COMMENT '客户id',
 `customer_name` char(200) NOT NULL COMMENT '客户名称',
 `items_id` bigint(20) NOT NULL COMMENT  '项目id',
 `items_name` char(200) NOT NULL COMMENT '项目名称',
 `items_price` char(20) NOT NULL COMMENT '项目费用',
 `create_time` int(11) NOT NULL COMMENT  '创建时间',
 `update_time` int(11) NOT NULL COMMENT  '修改时间',
 `is_delete` tinyint(1) NOT NULL COMMENT '1代表未删除，2已经删除',
 PRIMARY KEY (`order_id`),
 KEY `customer_id` (`customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='客户订单统计表';

ALTER TABLE `group_customer_items`
ADD COLUMN `items_type`  tinyint(6) NOT NULL DEFAULT 0 COMMENT '项目类型' AFTER `items_time_sum`;

ALTER TABLE `group_customer_items` ADD INDEX ( `customer_id` );


ALTER TABLE `tadmin`
ADD COLUMN  `orgId` bigint(20) NOT NULL DEFAULT '0' COMMENT '机构编号' AFTER `roleId`;

---------------1.7---------------
ALTER TABLE `order_accompany` ADD `doctor_reply` JSON NOT NULL COMMENT '医生回复 is_supply 是否已补充报告 retry_status 是否需要重新上传 retry_info 需要重新上传的图片 exception_info异常信息 summary_info总结信息 advise_info 建议信息 report_doctor_id 解读报告医生ID report_doctor_name 解读报告医生姓名' AFTER `select_info`;
ALTER TABLE `order_commonweal` ADD `doctor_reply` JSON NOT NULL COMMENT '医生回复 is_supply 是否已补充报告 retry_status 是否需要重新上传 retry_info 需要重新上传的图片 exception_info异常信息 summary_info总结信息 advise_info 建议信息 report_doctor_id 解读报告医生ID report_doctor_name 解读报告医生姓名' AFTER `select_info`;
ALTER TABLE `order_group_customer` ADD `doctor_reply` JSON NOT NULL COMMENT '医生回复 is_supply 是否已补充报告 retry_status 是否需要重新上传 retry_info 需要重新上传的图片 exception_info异常信息 summary_info总结信息 advise_info 建议信息 report_doctor_id 解读报告医生ID report_doctor_name 解读报告医生姓名' AFTER `select_info`;
ALTER TABLE `order_overseas` ADD `doctor_reply` JSON NOT NULL COMMENT '医生回复 is_supply 是否已补充报告 retry_status 是否需要重新上传 retry_info 需要重新上传的图片 exception_info异常信息 summary_info总结信息 advise_info 建议信息 report_doctor_id 解读报告医生ID report_doctor_name 解读报告医生姓名' AFTER `select_info`;
ALTER TABLE `order_surgery` ADD `doctor_reply` JSON NOT NULL COMMENT '医生回复 is_supply 是否已补充报告 retry_status 是否需要重新上传 retry_info 需要重新上传的图片 exception_info异常信息 summary_info总结信息 advise_info 建议信息 report_doctor_id 解读报告医生ID report_doctor_name 解读报告医生姓名' AFTER `select_info`;
ALTER TABLE `order_treat` ADD `doctor_reply` JSON NOT NULL COMMENT '医生回复 is_supply 是否已补充报告 retry_status 是否需要重新上传 retry_info 需要重新上传的图片 exception_info异常信息 summary_info总结信息 advise_info 建议信息 report_doctor_id 解读报告医生ID report_doctor_name 解读报告医生姓名' AFTER `select_info`;


ALTER TABLE `order_accompany` CHANGE `disease_desc` `disease_desc` JSON NOT NULL COMMENT '病情描述{\"disease_name\":\"疾病名称\",\"disease_des\":\"疾病描述\",\"order_design\":\"预约备注\",\"order_file\":\"病情图片\" check_organization 检查机构 report_check_time 检查时间';
ALTER TABLE `order_commonweal` CHANGE `disease_desc` `disease_desc` JSON NOT NULL COMMENT '病情描述{\"disease_name\":\"疾病名称\",\"disease_des\":\"疾病描述\",\"order_design\":\"预约备注\",\"order_file\":\"病情图片\" check_organization 检查机构 report_check_time 检查时间';
ALTER TABLE `order_group_customer` CHANGE `disease_desc` `disease_desc` JSON NOT NULL COMMENT '病情描述{\"disease_name\":\"疾病名称\",\"disease_des\":\"疾病描述\",\"order_design\":\"预约备注\",\"order_file\":\"病情图片\" check_organization 检查机构 report_check_time 检查时间';
ALTER TABLE `order_overseas` CHANGE `disease_desc` `disease_desc` JSON NOT NULL COMMENT '病情描述{\"disease_name\":\"疾病名称\",\"disease_des\":\"疾病描述\",\"order_design\":\"预约备注\",\"order_file\":\"病情图片\" check_organization 检查机构 report_check_time 检查时间';
ALTER TABLE `order_surgery` CHANGE `disease_desc` `disease_desc` JSON NOT NULL COMMENT '病情描述{\"disease_name\":\"疾病名称\",\"disease_des\":\"疾病描述\",\"order_design\":\"预约备注\",\"order_file\":\"病情图片\" check_organization 检查机构 report_check_time 检查时间';
ALTER TABLE `order_treat` CHANGE `disease_desc` `disease_desc` JSON NOT NULL COMMENT '病情描述{\"disease_name\":\"疾病名称\",\"disease_des\":\"疾病描述\",\"order_design\":\"预约备注\",\"order_file\":\"病情图片\" check_organization 检查机构 report_check_time 检查时间';

ALTER TABLE `order_operation_log` CHANGE `operation_desc` `operation_desc` TEXT NULL DEFAULT NULL COMMENT '备注';


--------------1.8 ----------------


ALTER TABLE `goods_info` CHANGE `user_name` `user_name` CHAR(100) NOT NULL COMMENT '上架人用户名';


CREATE TABLE `overseas_category` (
  `oc_id` bigint(20) NOT NULL COMMENT '主键ID',
  `oc_name` char(200) NOT NULL COMMENT '类别名称',
  `oc_parent_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '父类ID 顶级类别 父类为0',
  `oc_parent_name` char(200) DEFAULT NULL COMMENT '父类名称',
  `oc_user_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '创建人员id',
  `oc_user_name` char(100) NOT NULL DEFAULT '' COMMENT '创建人用户名',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  `is_delete` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否删除 1未删除 2已删除'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='海外商品分类表';

CREATE TABLE `overseas_goods_info` (
  `goods_id` bigint(20) NOT NULL COMMENT '商品ID',
  `goods_name` char(200) NOT NULL COMMENT '商品名称',
  `is_sale` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 上架 0 下架',
  `hospital_name` char(50) NOT NULL COMMENT '医院名称',
  `hospital_desc` varchar(250) NOT NULL COMMENT '医院描述',
  `goods_tag` varchar(30) NOT NULL COMMENT '商品标签',
  `goods_index_location` int(11) NOT NULL DEFAULT '0' COMMENT '商品首页位置1-6',
  `goods_index_image` varchar(250) NOT NULL COMMENT '首页图片',
  `banner_image` varchar(100) DEFAULT NULL COMMENT '商品banner图片',
  `goods_image` json NOT NULL COMMENT '商品详情图片',
  `list_image` varchar(100) NOT NULL COMMENT '商品列表图片',
  `oc_id` bigint(20) NOT NULL COMMENT '商品分类ID',
  `oc_name` char(200) NOT NULL COMMENT '商品分类名称',
  `oc_parent_id` bigint(20) NOT NULL COMMENT '商品分类父类ID',
  `oc_parent_name` char(200) NOT NULL COMMENT '商品分类父类名称',
  `sale_price` char(50) NOT NULL COMMENT '商品出售价格',
  `favoure_price` char(50) NOT NULL COMMENT '商品优惠价格',
  `goods_point` json NOT NULL COMMENT '项目特点',
  `goods_country` char(200) NOT NULL COMMENT '项目国家城市',
  `share_title` char(200) NOT NULL COMMENT '分享标题',
  `share_desc` char(255) NOT NULL COMMENT '分享描述',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  `is_delete` tinyint(1) NOT NULL COMMENT '是否删除 1未删除 2已删除'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='海外医疗商品表';

ALTER TABLE `overseas_goods_info`
  ADD PRIMARY KEY (`goods_id`);

CREATE TABLE `overseas_goods_info_log` (
  `goods_log_id` bigint(20) NOT NULL COMMENT '商品修改记录ID',
  `goods_id` bigint(20) NOT NULL COMMENT '商品ID',
  `goods_name` char(200) NOT NULL COMMENT '商品名称',
  `is_sale` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 上架 0 下架',
  `hospital_name` char(50) NOT NULL COMMENT '医院名称',
  `hospital_desc` varchar(250) NOT NULL COMMENT '医院描述',
  `goods_tag` varchar(30) NOT NULL COMMENT '商品标签',
  `goods_index_location` int(11) NOT NULL DEFAULT '0' COMMENT '商品首页位置1-6',
  `goods_index_image` varchar(250) NOT NULL COMMENT '首页图片',
  `banner_image` varchar(100) DEFAULT NULL COMMENT '商品banner图片',
  `goods_image` json NOT NULL COMMENT '商品详情图片',
  `list_image` varchar(100) NOT NULL COMMENT '商品列表图片',
  `oc_id` bigint(20) NOT NULL COMMENT '商品分类ID',
  `oc_name` char(200) NOT NULL COMMENT '商品分类名称',
  `oc_parent_id` bigint(20) NOT NULL COMMENT '商品分类父类ID',
  `oc_parent_name` char(200) NOT NULL COMMENT '商品分类父类名称',
  `sale_price` char(50) NOT NULL COMMENT '商品出售价格',
  `favoure_price` char(50) NOT NULL COMMENT '商品优惠价格',
  `goods_point` json NOT NULL COMMENT '项目特点',
  `goods_country` char(200) NOT NULL COMMENT '项目国家城市',
  `share_title` char(200) NOT NULL COMMENT '分享标题',
  `share_desc` char(255) NOT NULL COMMENT '分享描述',
  `oc_user_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '创建人员id',
  `oc_user_name` char(100) NOT NULL DEFAULT '' COMMENT '创建人用户名',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  `is_delete` tinyint(1) NOT NULL COMMENT '是否删除 1未删除 2已删除'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='海外医疗商品修改记录表';

ALTER TABLE `overseas_goods_info_log`
  ADD PRIMARY KEY (`goods_log_id`);

ALTER TABLE `overseas_goods_info` CHANGE `is_delete` `is_delete` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '是否删除 1未删除 2已删除';
ALTER TABLE `overseas_goods_info_log` CHANGE `is_delete` `is_delete` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '是否删除 1未删除 2已删除';

  ALTER TABLE `overseas_goods_info`
ADD COLUMN `goods_desc`  varchar(200) NOT NULL DEFAULT '' COMMENT '商品描述' AFTER `hospital_desc`;


ALTER TABLE `overseas_goods_info_log`
ADD COLUMN `goods_desc`  varchar(200) NOT NULL DEFAULT '' COMMENT '商品描述' AFTER `hospital_desc`;

-----------后台搜索版本-------------
ALTER TABLE `dthospital_sections` ADD `feature_sort` INT(11) NOT NULL DEFAULT '0' COMMENT '非特色科室为0 特色科室大于0' AFTER `hospital_name`;
ALTER TABLE `dtdoctor` ADD `visit_time` VARCHAR(200) NULL DEFAULT '8100820083008400850086008700' COMMENT '出诊日期' AFTER `hospital_id`;
ALTER TABLE `dthospital_sections` ADD `district_id` BIGINT(20) NOT NULL DEFAULT '0' COMMENT '地区信息' AFTER `hospital_name`;
update dthospital_sections s, dthospital c set s.district_id = c.district_id where s.hospital_id = c.hospital_id;
ALTER TABLE `dtdoctor` ADD `outpatient_type` VARCHAR(200) NOT NULL DEFAULT '8100820083008400850086008700' COMMENT '81周一 0 普通门诊 1特需门诊 2专科门诊 3专家门诊' AFTER `hospital_id`;
ALTER TABLE `dtdoctor` ADD `is_app_show` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '是否在app显示' AFTER `doctor_other_id`;
ALTER TABLE `dthospital_sections` ADD `is_app_show` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '是否在app显示' AFTER `hospital_section_other_id`;

-----------会员卡激活------------------
--------卡服务信息表与card_order_service主键ID一致------------
CREATE TABLE `order_card_service` (
  `order_id` bigint(20) NOT NULL COMMENT '订单ID',
  `user_id` bigint(20) NOT NULL COMMENT '用户id',
  `requirement` json NOT NULL COMMENT '用户需求{"hospital_id":"医院ID","hospital_name":"医院名称","hospital_section_id":"医院科室ID","section_id":"科室ID","section_name":"科室名称","doctor_id":"医生ID","doctor_name":"医生姓名"....}',
  `disease_desc` json NOT NULL COMMENT '病情描述{"disease_name":"疾病名称","disease_des":"疾病描述","order_design":"预约备注","order_file":"病情图片" check_organization 检查机构 report_check_time 检查时间',
  `patient_info` json NOT NULL COMMENT '患者信息{"id_card":"身份证号","order_name":"姓名","order_gender":"用户性别","order_phone":"手机号码","	order_age":"年龄","order_city":"地区code","order_city_name":"地区名称","order_date":"期望就诊时间",""}',
  `process_record` json NOT NULL COMMENT '{"t1"=>{"option_id":"操作者ID","option_type":"操作者类别 用户或者后台管理人员","option_name":"操作者姓名","option_time":"操作时间","option_money":"设置费用或者用户支付的费用"}}',
  `select_info` json NOT NULL COMMENT '{"vip_type":"用户选择的服务类型",....."}',
  `doctor_reply` json NOT NULL COMMENT '医生回复 retry_status 是否需要重新上传 retry_info 需要重新上传的图片 exception_info异常信息 summary_info总结信息 advise_info 建议信息 report_doctor_id 解读报告医生ID report_doctor_name 解读报告医生姓名',
  `order_type` tinyint(2) NOT NULL COMMENT '订单类型',
  `can_pay` tinyint(1) NOT NULL COMMENT '是否需要支付',
  `pay_money` char(50) NOT NULL COMMENT '支付金额',
  `order_state` tinyint(2) NOT NULL COMMENT '订单状态',
  `order_version` int(11) NOT NULL DEFAULT '1' COMMENT '当前version版本号',
  `order_process` int(11) NOT NULL DEFAULT '0' COMMENT '订单进行状态0 进行中 1 已完成 2已取消',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '修改时间',
  `is_delete` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1代表未删除，2已经删除',
  `is_look` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1:未被查看;2:已查看'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='海外就医';
ALTER TABLE `order_card_service`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);
--------所有的订单列表------------
CREATE TABLE `order_multi_backend` (
  `order_id` bigint(20) NOT NULL COMMENT '订单ID',
  `user_id` bigint(20) NOT NULL COMMENT '用户id',
  `requirement` json NOT NULL COMMENT '用户需求{"hospital_id":"医院ID","hospital_name":"医院名称","hospital_section_id":"医院科室ID","section_id":"科室ID","section_name":"科室名称","doctor_id":"医生ID","doctor_name":"医生姓名"....}',
  `disease_desc` json NOT NULL COMMENT '病情描述{"disease_name":"疾病名称","disease_des":"疾病描述","order_design":"预约备注","order_file":"病情图片" check_organization 检查机构 report_check_time 检查时间',
  `patient_info` json NOT NULL COMMENT '患者信息{"id_card":"身份证号","order_name":"姓名","order_gender":"用户性别","order_phone":"手机号码","	order_age":"年龄","order_city":"地区code","order_city_name":"地区名称","order_date":"期望就诊时间",""}',
  `process_record` json NOT NULL COMMENT '{"t1"=>{"option_id":"操作者ID","option_type":"操作者类别 用户或者后台管理人员","option_name":"操作者姓名","option_time":"操作时间","option_money":"设置费用或者用户支付的费用"}}',
  `select_info` json NOT NULL COMMENT '{"vip_type":"用户选择的服务类型",....."}',
  `doctor_reply` json NOT NULL COMMENT '医生回复 retry_status 是否需要重新上传 retry_info 需要重新上传的图片 exception_info异常信息 summary_info总结信息 advise_info 建议信息 report_doctor_id 解读报告医生ID report_doctor_name 解读报告医生姓名',
  `order_type` tinyint(2) NOT NULL COMMENT '订单类型',
  `can_pay` tinyint(1) NOT NULL COMMENT '是否需要支付',
  `pay_money` char(50) NOT NULL COMMENT '支付金额',
  `order_state` tinyint(2) NOT NULL COMMENT '订单状态',
  `order_version` int(11) NOT NULL DEFAULT '1' COMMENT '当前version版本号',
  `order_process` int(11) NOT NULL DEFAULT '0' COMMENT '订单进行状态0 进行中 1 已完成 2已取消',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '修改时间',
  `is_delete` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1代表未删除，2已经删除',
  `is_look` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1:未被查看;2:已查看'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='海外就医';
ALTER TABLE `order_multi_backend`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);
-------------------支付订单汇总表------------------
CREATE TABLE `pay_multi_backend` (
  `pay_id` bigint(20) NOT NULL COMMENT '支付ID号',
  `user_id` bigint(20) NOT NULL COMMENT '用户id',
  `order_id` bigint(20) NOT NULL COMMENT '订单ID',
  `pay_money` char(50) NOT NULL COMMENT '支付订单金额',
  `pay_type` tinyint(1) NOT NULL COMMENT '1，支付宝 2,微信 3银联',
  `pay_status` tinyint(1) NOT NULL COMMENT '0 未支付， 1支付失败， 2支付成功',
  `pay_account` char(200) NOT NULL COMMENT '支付账号',
  `pay_order_id` char(200) NOT NULL COMMENT '第三方ID',
  `order_type` tinyint(2) NOT NULL COMMENT '订单类型',
  `order_state` tinyint(2) NOT NULL COMMENT '订单状态',
  `requestParams` json NOT NULL COMMENT '第三方回调参数',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '修改时间',
  `is_delete` int(11) NOT NULL COMMENT '1代表未删除，2已经删除'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='vip陪诊订单';
ALTER TABLE `pay_multi_backend`
  ADD PRIMARY KEY (`pay_id`),
  ADD KEY `user_id` (`user_id`);
------------------退款表----------------------
CREATE TABLE `pay_multi_backend_refund` (
  `refund_id` bigint(20) NOT NULL COMMENT '退款ID号',
  `refund_money` char(50) NOT NULL COMMENT '退款金额',
  `option_user_id` bigint(20) NOT NULL COMMENT '操作人ID',
  `option_user_name` char(50) NOT NULL COMMENT '操作人姓名',
  `check_user_id` bigint(20) NOT NULL COMMENT '审核人ID',
  `check_user_name` char(50) NOT NULL COMMENT '审核人姓名',
  `backend_refund_status` char(50) NOT NULL COMMENT '后台申请状态',
  `refund_status` char(50) NOT NULL COMMENT '是否申请退款成功',
  `pay_id` bigint(20) NOT NULL COMMENT '支付ID号',
  `user_id` bigint(20) NOT NULL COMMENT '用户id',
  `order_id` bigint(20) NOT NULL COMMENT '订单ID',
  `pay_money` char(50) NOT NULL COMMENT '支付订单金额',
  `pay_type` tinyint(1) NOT NULL COMMENT '1，支付宝 2,微信 3银联',
  `pay_status` tinyint(1) NOT NULL COMMENT '0 未支付， 1支付失败， 2支付成功',
  `pay_account` char(200) NOT NULL COMMENT '支付账号',
  `pay_order_id` char(200) NOT NULL COMMENT '第三方ID',
  `order_type` tinyint(2) NOT NULL COMMENT '订单类型',
  `order_state` tinyint(2) NOT NULL COMMENT '订单状态',
  `requestParams` json NOT NULL COMMENT '第三方回调参数',
  `request_refund_params` json NOT NULL COMMENT '第三方退款回调参数',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '修改时间',
  `is_delete` int(11) NOT NULL COMMENT '1代表未删除，2已经删除'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='vip陪诊订单';
ALTER TABLE `pay_multi_backend_refund`
  ADD PRIMARY KEY (`refund_id`),
  ADD KEY `user_id` (`user_id`);
------------------退款log表----------------------
CREATE TABLE `pay_multi_backend_refund_log` (
  `refund_log_id` bigint(20) NOT NULL COMMENT '退款log ID号',
  `refund_id` bigint(20) NOT NULL COMMENT '退款ID号',
  `refund_money` char(50) NOT NULL COMMENT '退款金额',
  `option_user_id` bigint(20) NOT NULL COMMENT '操作人ID',
  `option_user_name` char(50) NOT NULL COMMENT '操作人姓名',
  `check_user_id` bigint(20) NOT NULL COMMENT '审核人ID',
  `check_user_name` char(50) NOT NULL COMMENT '审核人姓名',
  `backend_refund_status` char(50) NOT NULL COMMENT '后台申请状态',
  `refund_status` char(50) NOT NULL COMMENT '是否申请退款成功',
  `pay_id` bigint(20) NOT NULL COMMENT '支付ID号',
  `user_id` bigint(20) NOT NULL COMMENT '用户id',
  `order_id` bigint(20) NOT NULL COMMENT '订单ID',
  `pay_money` char(50) NOT NULL COMMENT '支付订单金额',
  `pay_type` tinyint(1) NOT NULL COMMENT '1，支付宝 2,微信 3银联',
  `pay_status` tinyint(1) NOT NULL COMMENT '0 未支付， 1支付失败， 2支付成功',
  `pay_account` char(200) NOT NULL COMMENT '支付账号',
  `pay_order_id` char(200) NOT NULL COMMENT '第三方ID',
  `order_type` tinyint(2) NOT NULL COMMENT '订单类型',
  `order_state` tinyint(2) NOT NULL COMMENT '订单状态',
  `requestParams` json NOT NULL COMMENT '第三方回调参数',
  `request_refund_params` json NOT NULL COMMENT '第三方退款回调参数',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '修改时间',
  `is_delete` int(11) NOT NULL COMMENT '1代表未删除，2已经删除'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='vip陪诊订单';
ALTER TABLE `pay_multi_backend_refund_log`
  ADD PRIMARY KEY (`refund_log_id`),
  ADD KEY `user_id` (`user_id`);
CREATE TABLE `pay_order_service` (
  `pay_id` bigint(20) NOT NULL COMMENT '支付ID号',
  `user_id` bigint(20) NOT NULL COMMENT '用户id',
  `order_id` bigint(20) NOT NULL COMMENT '订单ID',
  `pay_money` char(50) NOT NULL COMMENT '支付订单金额',
  `pay_type` tinyint(1) NOT NULL COMMENT '1，支付宝 2,微信 3银联',
  `pay_status` tinyint(1) NOT NULL COMMENT '0 未支付， 1支付失败， 2支付成功',
  `pay_account` char(200) NOT NULL COMMENT '支付账号',
  `pay_order_id` char(200) NOT NULL COMMENT '第三方ID',
  `order_type` tinyint(2) NOT NULL COMMENT '订单类型',
  `order_state` tinyint(2) NOT NULL COMMENT '订单状态',
  `requestParams` json NOT NULL COMMENT '第三方回调参数',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '修改时间',
  `is_delete` int(11) NOT NULL COMMENT '1代表未删除，2已经删除'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='vip陪诊订单';
ALTER TABLE `pay_order_service`
  ADD PRIMARY KEY (`pay_id`),
  ADD KEY `user_id` (`user_id`);

ALTER TABLE `pay_multi_backend` ADD `refund_status` tinyint(1)  NOT NULL DEFAULT 0 COMMENT '1:退款成功2:退款失败3:提交退款申请' AFTER `is_delete`;

ALTER TABLE `card_order_service` ADD COLUMN `card_alias_no`  bigint(20) NOT NULL DEFAULT 0 COMMENT '会员卡短号' AFTER `card_no`;

UPDATE `card_order_service` as c,`card_info` as i SET c.`card_alias_no`=i.`card_alias_no` WHERE c.`card_no`=i.`card_no`;

------------------------------在线问诊-----------------------------

CREATE TABLE `order_inquiry_online` (
  `order_id` bigint(20) NOT NULL COMMENT '订单ID',
  `user_id` bigint(20) NOT NULL COMMENT '用户id',
  `requirement` json NOT NULL COMMENT '用户需求{"hospital_id":"医院ID","hospital_name":"医院名称","hospital_section_id":"医院科室ID","section_id":"科室ID","section_name":"科室名称","doctor_id":"医生ID","doctor_name":"医生姓名"....}',
  `disease_desc` json NOT NULL COMMENT '病情描述{"disease_name":"疾病名称","disease_des":"疾病描述","order_design":"预约备注","order_file":"病情图片" check_organization 检查机构 report_check_time 检查时间',
  `patient_info` json NOT NULL COMMENT '患者信息{"id_card":"身份证号","order_name":"姓名","order_gender":"用户性别","order_phone":"手机号码","  order_age":"年龄","order_city":"地区code","order_city_name":"地区名称","order_date":"期望就诊时间",""}',
  `process_record` json NOT NULL COMMENT '{"t1"=>{"option_id":"操作者ID","option_type":"操作者类别 用户或者后台管理人员","option_name":"操作者姓名","option_time":"操作时间","option_money":"设置费用或者用户支付的费用"}}',
  `select_info` json NOT NULL COMMENT '{"vip_type":"用户选择的服务类型",....."}',
  `doctor_reply` json NOT NULL COMMENT '医生回复 retry_status 是否需要重新上传 retry_info 需要重新上传的图片 exception_info异常信息 summary_info总结信息 advise_info 建议信息 report_doctor_id 解读报告医生ID report_doctor_name 解读报告医生姓名',
  `order_type` tinyint(2) NOT NULL COMMENT '订单类型',
  `can_pay` tinyint(1) NOT NULL COMMENT '是否需要支付',
  `pay_money` char(50) NOT NULL COMMENT '支付金额',
  `order_state` tinyint(2) NOT NULL COMMENT '订单状态',
  `order_version` int(11) NOT NULL DEFAULT '1' COMMENT '当前version版本号',
  `order_process` int(11) NOT NULL DEFAULT '0' COMMENT '订单进行状态0 进行中 1 已完成 2已取消',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '修改时间',
  `is_delete` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1代表未删除，2已经删除',
  `is_look` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1:未被查看;2:已查看'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='在线问诊';
ALTER TABLE `order_inquiry_online` ADD PRIMARY KEY( `order_id`);


CREATE TABLE `pay_inquiry_online` (
  `pay_id` bigint(20) NOT NULL COMMENT '支付ID号',
  `user_id` bigint(20) NOT NULL COMMENT '用户id',
  `order_id` bigint(20) NOT NULL COMMENT '订单ID',
  `pay_money` char(50) NOT NULL COMMENT '支付订单金额',
  `pay_type` tinyint(1) NOT NULL COMMENT '1，支付宝 2,微信 3银联',
  `pay_status` tinyint(1) NOT NULL COMMENT '0 未支付， 1支付失败， 2支付成功',
  `pay_account` char(200) NOT NULL COMMENT '支付账号',
  `pay_order_id` char(200) NOT NULL COMMENT '第三方ID',
  `order_type` tinyint(2) NOT NULL COMMENT '订单类型',
  `order_state` tinyint(2) NOT NULL COMMENT '订单状态',
  `requestParams` json NOT NULL COMMENT '第三方回调参数',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '修改时间',
  `is_delete` int(11) NOT NULL COMMENT '1代表未删除，2已经删除'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='在线问诊';

ALTER TABLE `pay_inquiry_online` ADD PRIMARY KEY(`pay_id`);

ALTER TABLE `tuser` ADD `access_token` VARCHAR(200) NULL DEFAULT NULL AFTER `user_mobile`;

CREATE TABLE `tuser_doctor_collection` (
  `tc_user_id` bigint(20) NOT NULL,
  `tc_doctor_id` bigint(20) NOT NULL COMMENT '文章ID',
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  `is_delete` int(1) NOT NULL DEFAULT '1' COMMENT '1 未删除 2 已删除'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户收藏列表';
ALTER TABLE `tuser_doctor_collection`
  ADD PRIMARY KEY (`tc_user_id`,`tc_doctor_id`);


-----------------------------------优惠卷-------------------------

CREATE TABLE `order_coupon` (
  `cid` bigint(20) NOT NULL COMMENT '主键ID',
  `user_id` bigint(20) NOT NULL COMMENT '用户ID',
  `coupon_name` char(50) NOT NULL DEFAULT '' COMMENT '优惠卷名称',
  `coupon_type` tinyint(2) NOT NULL DEFAULT '1' COMMENT '优惠卷类型',
  `order_money` char(50) NOT NULL DEFAULT '0.00' COMMENT '优惠卷金额',
  `order_type` tinyint(2) NOT NULL DEFAULT '0' COMMENT '使用订单类型',
  `order_desc` char(150) NOT NULL DEFAULT '' COMMENT '使用订单类型描述',
  `use_rule` tinyint(2) NOT NULL DEFAULT '1' COMMENT '使用规则,1:通用2:满减',
  `rule_desc` char(150) NOT NULL DEFAULT '' COMMENT '使用规则描述',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1:正常2:已使用;',
  `expiry_time` int(11) NOT NULL DEFAULT '0' COMMENT '有效期',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`cid`),
  KEY `user_id` (`user_id`) USING BTREE,
  KEY `expiry_time` (`expiry_time`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单优惠劵';


ALTER TABLE `order_accompany`
ADD COLUMN `coupon_id`  bigint(20) NOT NULL DEFAULT 0 COMMENT '优惠卷编号' AFTER `is_look`;

ALTER TABLE `order_card_service`
ADD COLUMN `coupon_id`  bigint(20) NOT NULL DEFAULT 0 COMMENT '优惠卷编号' AFTER `is_look`;

ALTER TABLE `order_commonweal`
ADD COLUMN `coupon_id`  bigint(20) NOT NULL DEFAULT 0 COMMENT '优惠卷编号' AFTER `is_look`;


ALTER TABLE `order_group_customer`
ADD COLUMN `coupon_id`  bigint(20) NOT NULL DEFAULT 0 COMMENT '优惠卷编号' AFTER `is_look`;

ALTER TABLE `order_inquiry_online`
ADD COLUMN `coupon_id`  bigint(20) NOT NULL DEFAULT 0 COMMENT '优惠卷编号' AFTER `is_look`;


ALTER TABLE `order_multi_backend`
ADD COLUMN `coupon_id`  bigint(20) NOT NULL DEFAULT 0 COMMENT '优惠卷编号' AFTER `is_look`;

ALTER TABLE `order_overseas`
ADD COLUMN `coupon_id`  bigint(20) NOT NULL DEFAULT 0 COMMENT '优惠卷编号' AFTER `is_look`;


ALTER TABLE `order_surgery`
ADD COLUMN `coupon_id`  bigint(20) NOT NULL DEFAULT 0 COMMENT '优惠卷编号' AFTER `is_look`;


ALTER TABLE `order_treat`
ADD COLUMN `coupon_id`  bigint(20) NOT NULL DEFAULT 0 COMMENT '优惠卷编号' AFTER `is_look`;





ALTER TABLE `pay_accompany`
ADD COLUMN `coupon_id`  bigint(20) NOT NULL DEFAULT 0 COMMENT '优惠卷编号' AFTER `is_delete`;

ALTER TABLE `pay_commonweal`
ADD COLUMN `coupon_id`  bigint(20) NOT NULL DEFAULT 0 COMMENT '优惠卷编号' AFTER `is_delete`;

ALTER TABLE `pay_group_customer`
ADD COLUMN `coupon_id`  bigint(20) NOT NULL DEFAULT 0 COMMENT '优惠卷编号' AFTER `is_delete`;

ALTER TABLE `pay_inquiry_online`
ADD COLUMN `coupon_id`  bigint(20) NOT NULL DEFAULT 0 COMMENT '优惠卷编号' AFTER `is_delete`;

ALTER TABLE `pay_multi_backend`
ADD COLUMN `coupon_id`  bigint(20) NOT NULL DEFAULT 0 COMMENT '优惠卷编号' AFTER `is_delete`;


ALTER TABLE `pay_multi_backend_refund`
ADD COLUMN `coupon_id`  bigint(20) NOT NULL DEFAULT 0 COMMENT '优惠卷编号' AFTER `is_delete`;



ALTER TABLE `pay_multi_backend_refund_log`
ADD COLUMN `coupon_id`  bigint(20) NOT NULL DEFAULT 0 COMMENT '优惠卷编号' AFTER `is_delete`;


ALTER TABLE `pay_order_service`
ADD COLUMN `coupon_id`  bigint(20) NOT NULL DEFAULT 0 COMMENT '优惠卷编号' AFTER `is_delete`;


ALTER TABLE `pay_surgery`
ADD COLUMN `coupon_id`  bigint(20) NOT NULL DEFAULT 0 COMMENT '优惠卷编号' AFTER `is_delete`;


ALTER TABLE `pay_treat`
ADD COLUMN `coupon_id`  bigint(20) NOT NULL DEFAULT 0 COMMENT '优惠卷编号' AFTER `is_delete`;


CREATE TABLE `tuser_opinion` (
  `id` bigint(20) NOT NULL COMMENT '主键',
  `user_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '用户id',
  `user_name` varchar(16) NOT NULL DEFAULT '' COMMENT '用户名',
  `desc`  text NULL COMMENT '反馈内容',
  `images` json DEFAULT NULL COMMENT '反馈图集',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户反馈表';


ALTER TABLE `tuser_service_person`
ADD COLUMN `user_age`  int(3) NOT NULL DEFAULT 0 COMMENT '年龄' AFTER `user_sex`;



ALTER TABLE `tuser_service_person`
MODIFY COLUMN `user_name`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '姓名' AFTER `user_id`,
MODIFY COLUMN `user_card_no`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '身份证号' AFTER `user_district_id`,
MODIFY COLUMN `user_phone`  varchar(24) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '手机号' AFTER `user_detail_address`;


ALTER TABLE `dtdoctor`
ADD COLUMN `price`  varchar(10) NOT NULL DEFAULT '30' COMMENT '医生问诊价格' AFTER `is_delete`;


ALTER TABLE `dtdoctor`
CHANGE COLUMN  `price`  varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0' COMMENT '医生问诊价格' AFTER `is_delete`;


ALTER TABLE `at_doctor_info`
CHANGE COLUMN `price`  varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0' COMMENT '医生问诊价格' AFTER `is_delete`;



ALTER TABLE `order_accompany`
ADD COLUMN `is_free`  tinyint(1) NOT NULL DEFAULT 1 COMMENT '1:收费2:免费' AFTER `coupon_id`;


ALTER TABLE `order_card_service`
ADD COLUMN `is_free`  tinyint(1) NOT NULL DEFAULT 1 COMMENT '1:收费2:免费' AFTER `coupon_id`;


ALTER TABLE `order_commonweal`
ADD COLUMN `is_free`  tinyint(1) NOT NULL DEFAULT 1 COMMENT '1:收费2:免费' AFTER `coupon_id`;



ALTER TABLE `order_group_customer`
ADD COLUMN `is_free`  tinyint(1) NOT NULL DEFAULT 1 COMMENT '1:收费2:免费' AFTER `coupon_id`;



ALTER TABLE `order_inquiry_online`
ADD COLUMN `is_free`  tinyint(1) NOT NULL DEFAULT 1 COMMENT '1:收费2:免费' AFTER `coupon_id`;



ALTER TABLE `order_multi_backend`
ADD COLUMN `is_free`  tinyint(1) NOT NULL DEFAULT 1 COMMENT '1:收费2:免费' AFTER `coupon_id`;



ALTER TABLE `order_overseas`
ADD COLUMN `is_free`  tinyint(1) NOT NULL DEFAULT 1 COMMENT '1:收费2:免费' AFTER `coupon_id`;



ALTER TABLE `order_surgery`
ADD COLUMN `is_free`  tinyint(1) NOT NULL DEFAULT 1 COMMENT '1:收费2:免费' AFTER `coupon_id`;


ALTER TABLE `order_treat`
ADD COLUMN `is_free`  tinyint(1) NOT NULL DEFAULT 1 COMMENT '1:收费2:免费' AFTER `coupon_id`;



---------------------------------------医生端------------------------------------------------


ALTER TABLE `order_accompany`
ADD COLUMN `doctor_id`  bigint(20) NOT NULL DEFAULT 0 COMMENT '医生id' AFTER `user_id`;



ALTER TABLE `order_commonweal`
ADD COLUMN `doctor_id`  bigint(20) NOT NULL DEFAULT 0 COMMENT '医生id' AFTER `user_id`;


ALTER TABLE `order_card_service`
ADD COLUMN `doctor_id`  bigint(20) NOT NULL DEFAULT 0 COMMENT '医生id' AFTER `user_id`;



ALTER TABLE `order_group_customer`
ADD COLUMN `doctor_id`  bigint(20) NOT NULL DEFAULT 0 COMMENT '医生id' AFTER `user_id`;



ALTER TABLE `order_inquiry_online`
ADD COLUMN `doctor_id`  bigint(20) NOT NULL DEFAULT 0 COMMENT '医生id' AFTER `user_id`;



ALTER TABLE `order_multi_backend`
ADD COLUMN `doctor_id`  bigint(20) NOT NULL DEFAULT 0 COMMENT '医生id' AFTER `user_id`;



ALTER TABLE `order_overseas`
ADD COLUMN `doctor_id`  bigint(20) NOT NULL DEFAULT 0 COMMENT '医生id' AFTER `user_id`;



ALTER TABLE `order_surgery`
ADD COLUMN `doctor_id`  bigint(20) NOT NULL DEFAULT 0 COMMENT '医生id' AFTER `user_id`;


ALTER TABLE `order_treat`
ADD COLUMN `doctor_id`  bigint(20) NOT NULL DEFAULT 0 COMMENT '医生id' AFTER `user_id`;


ALTER TABLE `at_doctor_info`
ADD COLUMN `doctor_position_desc`  varchar(30) NOT NULL DEFAULT '' COMMENT '医生职称描述' AFTER `doctor_position`;

ALTER TABLE `dtdoctor`
ADD COLUMN `doctor_position_desc`  varchar(30) NOT NULL DEFAULT '' COMMENT '医生职称描述' AFTER `doctor_position`;


CREATE TABLE `at_doctor_cards` (
  `cards_id` bigint(20) NOT NULL COMMENT 'id',
  `doctor_id` bigint(20) NOT NULL COMMENT '医生ID',
  `bank_card` varchar(64) NOT NULL DEFAULT '' COMMENT '银行卡号',
  `bank_name` varchar(150) NOT NULL DEFAULT '' COMMENT '银行名称',
  `opening_bank` varchar(64) NOT NULL DEFAULT '' COMMENT '开户行名称',
  `user_name` varchar(64) NOT NULL DEFAULT '' COMMENT '持卡人姓名',
  `user_card` varchar(64) NOT NULL DEFAULT '' COMMENT '持卡人身份证',
  `card_state` tinyint(2) NOT NULL DEFAULT '1' COMMENT '卡状态',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`cards_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='医生银行卡表';



ALTER TABLE `order_accompany`
  ADD COLUMN `is_reply`  tinyint(1) NOT NULL DEFAULT 1 COMMENT '1:未回复2:回复' AFTER `is_free`;


ALTER TABLE `order_card_service`
  ADD COLUMN `is_reply`  tinyint(1) NOT NULL DEFAULT 1 COMMENT '1:未回复2:回复' AFTER `is_free`;


ALTER TABLE `order_commonweal`
  ADD COLUMN `is_reply`  tinyint(1) NOT NULL DEFAULT 1 COMMENT '1:未回复2:回复' AFTER `is_free`;



ALTER TABLE `order_group_customer`
  ADD COLUMN `is_reply`  tinyint(1) NOT NULL DEFAULT 1 COMMENT '1:未回复2:回复' AFTER `is_free`;



ALTER TABLE `order_inquiry_online`
  ADD COLUMN `is_reply`  tinyint(1) NOT NULL DEFAULT 1 COMMENT '1:未回复2:回复' AFTER `is_free`;



ALTER TABLE `order_multi_backend`
  ADD COLUMN `is_reply`  tinyint(1) NOT NULL DEFAULT 1 COMMENT '1:未回复2:回复' AFTER `is_free`;



ALTER TABLE `order_overseas`
  ADD COLUMN `is_reply`  tinyint(1) NOT NULL DEFAULT 1 COMMENT '1:未回复2:回复' AFTER `is_free`;



ALTER TABLE `order_surgery`
  ADD COLUMN `is_reply`  tinyint(1) NOT NULL DEFAULT 1 COMMENT '1:未回复2:回复' AFTER `is_free`;


ALTER TABLE `order_treat`
  ADD COLUMN `is_reply`  tinyint(1) NOT NULL DEFAULT 1 COMMENT '1:未回复2:回复' AFTER `is_free`;


ALTER TABLE  `at_doctor_authentication`
  ADD COLUMN `auth_desc` text NULL COMMENT '审核备注' AFTER `update_time`;

ALTER TABLE  `tuser`
  ADD COLUMN   `is_coupon` int(2) NOT NULL DEFAULT 0 COMMENT '优惠卷提醒' AFTER `role`;




ALTER TABLE `tuser_service_person`
  ADD COLUMN `user_medical_no` varchar(100) NOT NULL DEFAULT 0 COMMENT '医保号' AFTER `user_card_no`;




ALTER TABLE `tuser_express_address`
  ADD COLUMN `user_card_no` varchar(64) NOT NULL COMMENT '收货人身份证号' AFTER `user_phone`;




CREATE TABLE `order_goods_detail` (
  `order_id` bigint(20) NOT NULL COMMENT '订单ID',
  `address_id` bigint(20) NOT NULL COMMENT '收货人地址ID',
  `user_id` bigint(20) NOT NULL COMMENT '用户ID',
  `buy_number` int(11) NOT NULL,
  `user_name` varchar(50) NOT NULL COMMENT '收货人姓名',
  `user_district_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '收货人地区ID',
  `user_district_address` varchar(100) NOT NULL COMMENT '收货人地区信息',
  `user_detail_address` varchar(200) NOT NULL COMMENT '收货人详细信息',
  `user_phone` varchar(24) NOT NULL COMMENT '收货人手机号',
  `is_express` int(1) NOT NULL DEFAULT '0' COMMENT '是否快递 0 不快递 1快递',
  `express_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '快递主键ID',
  `goods_id` bigint(20) NOT NULL COMMENT '商品ID',
  `goods_name` varchar(100) NOT NULL COMMENT '商品名称',
  `goods_type` int(1) NOT NULL COMMENT '商品类型',
  `goods_expire_time` int(11) NOT NULL COMMENT '时效',
  `goods_big_image` varchar(200) NOT NULL COMMENT '商品大图',
  `goods_small_image` varchar(200) NOT NULL COMMENT '商品列表图',
  `goods_image` json NOT NULL COMMENT '商品展示图片',
  `goods_amount` varchar(24) NOT NULL COMMENT '销量',
  `goods_onsalt_time` int(11) NOT NULL COMMENT '上架时间',
  `is_onsalt` int(1) NOT NULL COMMENT '是否上架',
  `goods_service` json NOT NULL COMMENT '服务类型：[{"id":服务ID, "count":"数量"},{"id":服务ID, "count":"数量"}]',
  `goods_service_limit` int(11) NOT NULL COMMENT '限制使用人数',
  `goods_url` varchar(200) NOT NULL COMMENT '商品html页面网址',
  `detail_url` varchar(200) NOT NULL COMMENT '商品详情html页面网址',
  `now_price` varchar(50) NOT NULL COMMENT '现价',
  `freight_price` varchar(50) NOT NULL COMMENT '运费',
  `is_invoice` int(1) NOT NULL COMMENT '是否需要发票0 不需要 1 需要',
  `invoice_type` int(1) NOT NULL COMMENT '发票类型 1 个人 2 公司',
  `invoice_title` varchar(100) NOT NULL COMMENT '发票抬头',
  `invoice_header_name` varchar(100) NOT NULL DEFAULT '' COMMENT '抬头名称',
  `invoice_content` varchar(250) NOT NULL COMMENT '发票内容',
  `order_detail_note` text COMMENT '订单备注',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  `is_delete` int(1) NOT NULL DEFAULT '1' COMMENT '1 未删除 2 已删除',
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品订单的详细信息';

CREATE TABLE `pay_goods` (
  `pay_id` bigint(20) NOT NULL COMMENT '支付ID号',
  `user_id` bigint(20) NOT NULL COMMENT '用户id',
  `order_id` bigint(20) NOT NULL COMMENT '订单ID',
  `pay_money` char(50) NOT NULL COMMENT '支付订单金额',
  `pay_type` tinyint(1) NOT NULL COMMENT '1，支付宝 2,微信 3银联',
  `pay_status` tinyint(1) NOT NULL COMMENT '0 未支付， 1支付失败， 2支付成功',
  `pay_account` char(200) NOT NULL COMMENT '支付账号',
  `pay_order_id` char(200) NOT NULL COMMENT '第三方ID',
  `order_type` tinyint(2) NOT NULL COMMENT '订单类型',
  `order_state` tinyint(2) NOT NULL COMMENT '订单状态',
  `requestParams` json NOT NULL COMMENT '第三方回调参数',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '修改时间',
  `is_delete` int(11) NOT NULL COMMENT '1代表未删除，2已经删除',
  `coupon_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '优惠卷编号',
  `refund_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1:退款成功2:退款失败3:提交退款申请',
  PRIMARY KEY (`pay_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品支付订单';

CREATE TABLE `order_goods` (
  `order_id` bigint(20) NOT NULL COMMENT '订单ID',
  `user_id` bigint(20) NOT NULL COMMENT '用户id',
  `doctor_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '医生id',
  `requirement` json NOT NULL COMMENT '用户需求{"hospital_id":"医院ID","hospital_name":"医院名称","hospital_section_id":"医院科室ID","section_id":"科室ID","section_name":"科室名称","doctor_id":"医生ID","doctor_name":"医生姓名"....}',
  `disease_desc` json NOT NULL COMMENT '病情描述{"disease_name":"疾病名称","disease_des":"疾病描述","order_design":"预约备注","order_file":"病情图片" check_organization 检查机构 report_check_time 检查时间',
  `patient_info` json NOT NULL COMMENT '患者信息{"id_card":"身份证号","order_name":"姓名","order_gender":"用户性别","order_phone":"手机号码","	order_age":"年龄","order_city":"地区code","order_city_name":"地区名称","order_date":"期望就诊时间",""}',
  `process_record` json NOT NULL COMMENT '{"t1"=>{"option_id":"操作者ID","option_type":"操作者类别 用户或者后台管理人员","option_name":"操作者姓名","option_time":"操作时间","option_money":"设置费用或者用户支付的费用"}}',
  `select_info` json NOT NULL COMMENT '{"vip_type":"用户选择的服务类型",....."}',
  `doctor_reply` json NOT NULL COMMENT '医生回复 retry_status 是否需要重新上传 retry_info 需要重新上传的图片 exception_info异常信息 summary_info总结信息 advise_info 建议信息 report_doctor_id 解读报告医生ID report_doctor_name 解读报告医生姓名',
  `order_type` tinyint(2) NOT NULL COMMENT '订单类型',
  `can_pay` tinyint(1) NOT NULL COMMENT '是否需要支付',
  `pay_money` char(50) NOT NULL COMMENT '支付金额',
  `order_state` tinyint(2) NOT NULL COMMENT '订单状态',
  `order_version` int(11) NOT NULL DEFAULT '1' COMMENT '当前version版本号',
  `order_process` int(11) NOT NULL DEFAULT '0' COMMENT '订单进行状态0 进行中 1 已完成 2已取消',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '修改时间',
  `is_delete` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1代表未删除，2已经删除',
  `is_look` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1:未被查看;2:已查看',
  `coupon_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '优惠卷编号',
  `is_free` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1:收费2:免费',
  `is_reply` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1:未回复2:回复',
  PRIMARY KEY (`order_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品订单';


ALTER TABLE  `goods_info`
  ADD COLUMN `goods_index_location` int(10) NOT NULL DEFAULT 0 COMMENT '商品首页位置1-6' AFTER `goods_small_image`,
  ADD COLUMN `banner_image` json NULL COMMENT '商品banner图片' AFTER `goods_image`;

ALTER TABLE  `goods_info`
  ADD COLUMN `goods_tag` varchar(50) DEFAULT   0 COMMENT '商品标签' AFTER `goods_index_location`;


ALTER TABLE `at_doctor_info` ADD `is_app_show` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '是否在app显示' AFTER `doctor_other_id`;

