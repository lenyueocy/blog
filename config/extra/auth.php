<?php
// +----------------------------------------------------------------------
// | 冷月博客 thinkphp5 版本
// +----------------------------------------------------------------------
// | Copyright (c) 2013~2016 http://loveteemo.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: long <admin@loveteemo.com>
// +----------------------------------------------------------------------
return [

	// +----------------------------------------------------------------------
	// | 常用配置 这里的对其方式需要改成 tab
	// +----------------------------------------------------------------------
	'qqgroup'		=>	'492708759',
	'qqjoin'		=>	'http://jq.qq.com/?_wv=1027&k=dSjBgy',
	'baiduurl'		=>	'http://koubei.baidu.com/w/lenyue.cn',
	'githuburl'		=>	'https://github.com/Lenyueocy',
	'weibourl'		=>	'http://weibo.com/iteemo',
	'blogname'		=>	'冷月博客',
	'framework'		=>	'ThinkPHP 5',
	'author'		=>	'long',
	'docurl'		=>	'http://doc.loveteemo.com/',
	'adminname'		=>	'小小小猫',
	'comview'		=>	1,
	'dehits'		=>	5,
    'baiduapi'      =>  '73df7bf1e7ee3b02e8c1bf77c3f7c281',

	// 后台授权用户
	'access'		=>	[
//		'7C8F797F30B08554A6E39A537F9A324B'
		'C6BA60D554A39CE3CEB7A4402D8F95FF'
	],

	// QQ 互联配置
	'qqconnect' => [
	    //正式环境app
//		'appid'		=>	'101476699',
//		'appkey'	=>	'215213b692ba4279e63689b1d9fc2078',
        //test需要的app
        'appid'		=>	'101476765',
        'appkey'	=>	'd7dcd4d5786768d7516c92036375de53',
        // log www 和 不带www 会有一个出现域名不匹配
        'callback'  =>  'http://'.$_SERVER['HTTP_HOST'].'/index/base/callback',
		'scope'		=>	'get_user_info'
	],
    'IpInfoApi'=>'http://ip.taobao.com/service/getIpInfo.php',

];