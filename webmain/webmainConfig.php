<?php
if(!defined('HOST'))die('not access');
//[管理员]在2018-08-06 17:03:58通过[系统→系统工具→系统设置]，保存修改了配置文件
return array(
	'url'	=> 'http://yun.guanruigroup.com/',	//系统URL
	'localurl'	=> 'http://yun.guanruigroup.com/',	//本地系统URL，用于服务器上浏览地址
	'title'	=> '冠瑞集团云办公系统',	//系统默认标题
	'apptitle'	=> '冠瑞集团云办公系统',	//APP上和手机网页版上的标题
	'db_host'	=> 'sh-cdb-4fqdl091.sql.tencentcdb.com:63432',	//数据库地址
	'db_user'	=> 'oatest',	//数据库用户名
	'db_pass'	=> 'greach1699',	//数据库密码
	'db_base'	=> 'oatest',	//数据库名称
	'db_engine'	=> 'MyISAM',
	'perfix'	=> 'xinhu_',	//数据库表名前缀
	'qom'	=> 'xinhu_',	//session、cookie前缀
	'highpass'	=> '',	//超级管理员密码，可用于登录任何帐号
	'db_drive'	=> 'mysql',	//操作数据库驱动有mysql,mysqli,pdo三种
	'randkey'	=> 'aslpvgotydmcfwubrzhxjekqin',	//系统随机字符串密钥
	'asynkey'	=> 'f4284bb2cec8274d69e0a61fcabd5647',	//这是异步任务key
	'openkey'	=> '5cf7007af9c76b8b7a8f582360690e9d',	//对外接口openkey
	'updir'	=> 'upload',
	'sqllog'	=> false,	//是否记录sql日志保存upload/sqllog下
	'asynsend'	=> '1',	//是否异步发送提醒消息，0同步，1自己服务端异步，2官网VIP用户异步
	'install'	=> true,	//已安装，不要去掉啊
	'reimtitle'	=> '冠瑞集团云办公系统',	//REIM即时通信上标题
	'xinhukey'	=> '7d1afc11a0670c87ea1eb036b0a5deac',	//信呼官网key，用于在线升级使用
	'bcolorxiang'	=> '',	//单据详情页面上默认展示线条的颜色
	'officeyl'	=> '0',	//文档Excel.Doc预览类型,0自己部署插件，1使用官网支持任何平台
	'debug'	=> false,	//为true调试开发模式,false上线模式
	'reim_show'	=> true,	//首页是否显示REIM
	'mobile_show'	=> true,	//首页是否显示手机版
	'loginyzm'	=> '0',	//登录方式:0仅使用帐号+密码,1帐号+密码/手机+验证码,2帐号+密码+验证码,3仅使用手机+验证码

);