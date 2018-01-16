CREATE TABLE `at_doctor_login` (
  `user_id` bigint(20) NOT NULL COMMENT '主键',
  `user_name` varchar(16) NOT NULL COMMENT '账号（唯一，系统生成）',
  `user_pass` varchar(32) DEFAULT NULL COMMENT '密码',
  `user_img` varchar(160) DEFAULT NULL COMMENT '头像',
  `user_mobile` varchar(24) DEFAULT NULL COMMENT '手机（唯一，用于APP登录）',
  `access_token` varchar(200) DEFAULT NULL COMMENT '融云聊天access_token',
  `session_key` varchar(120) DEFAULT NULL COMMENT '登录key（每次登录生成新的保存，mdb5）',
  `user_regtime` datetime DEFAULT NULL COMMENT '注册时间',
  `role` int(5) DEFAULT '0' COMMENT '角色默认0（0=用户、1=医生、2=医院管理者）',
  `user_cid` varchar(32) NOT NULL DEFAULT '' COMMENT '用户个推CID',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `update_time` int(11) NOT NULL DEFAULT '0',
  `is_delete` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 有效 2失效'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='医生用户登录表';
ALTER TABLE `at_doctor_login`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_name` (`user_name`),
  ADD UNIQUE KEY `user_mobile` (`user_mobile`);


CREATE TABLE `at_doctor_info` (
  `doctor_id` bigint(20) NOT NULL COMMENT '医生ID',
  `hospital_id` bigint(20) DEFAULT NULL COMMENT '所属医院',
  `outpatient_type` varchar(200) NOT NULL DEFAULT '8100820083008400850086008700' COMMENT '81周一 0 普通门诊 1特需门诊 2专科门诊 3专家门诊',
  `visit_time` varchar(200) DEFAULT '8100820083008400850086008700' COMMENT '出诊日期',
  `district_id` int(11) NOT NULL DEFAULT '0',
  `hospital_name` varchar(400) DEFAULT NULL COMMENT '所属医院名称',
  `hospital_level` int(11) NOT NULL DEFAULT '0',
  `doctor_name` varchar(200) DEFAULT NULL COMMENT '医生名',
  `doctor_position` int(100) DEFAULT NULL COMMENT '医生职位（1科室主任，2副主任，3主医医生 ）',
  `doctor_des` text COMMENT '医生简介',
  `doctor_head` varchar(400) DEFAULT NULL COMMENT '医生头像照片地址',
  `good_at` text COMMENT '擅长领域（列表展示）',
  `honor` text COMMENT '荣誉',
  `work_experience` text COMMENT '执业经历',
  `section_info` text COMMENT 'a:section_id-b:section_name@a:section_parent_id-b:section_parent_name||a:section_id-b:section_name@a:section_parent_id-b:section_parent_name sphinx use',
  `hospital_section_info` text COMMENT 'a:section_id-b:hospital_section_name@a:section_parent_id-b:hospital_section_parent_name||sphinx use',
  `can_disease` text COMMENT 'a:disease_id-b:disease_name@a:disease_id-b:disease_name sphinx use',
  `is_top` int(2) NOT NULL COMMENT '是否推荐首页，1推荐，默认0',
  `is_authentication` int(1) NOT NULL DEFAULT '1' COMMENT '是否认证通过，1通过，默认0 未通过',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '（创建时间）',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '（更新时间）',
  `doctor_other_id` int(11) NOT NULL DEFAULT '0',
  `is_delete` int(2) DEFAULT '1' COMMENT '1、代表未删除，2代表已经删除',
  `price` varchar(10) NOT NULL DEFAULT '30' COMMENT '医生问诊价格'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='医生基本信息表';

ALTER TABLE `at_doctor_info`
  ADD PRIMARY KEY (`doctor_id`);

CREATE TABLE `at_doctor_authentication` (
  `doctor_id` bigint(20) NOT NULL COMMENT '医生ID',
  `doctor_head` text COMMENT '医生头像',
  `doctor_certificate` text COMMENT '执业证书',
  `doctor_card` text COMMENT '职称证书或胸牌',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '（创建时间）',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '（更新时间）',
  `is_delete` int(2) DEFAULT '1' COMMENT '1、代表未删除，2代表已经删除'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='医生认证资料表';

ALTER TABLE `at_doctor_authentication`
  ADD PRIMARY KEY (`doctor_id`);

